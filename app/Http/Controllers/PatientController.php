<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;
        $startDate = $request->startDate;
        $endDate = $request->endDate;

        $patients = Patient::query()
            ->orderByDesc('id')
            ->when($search, function ($query, $search) {
                $query->where('nama', 'like', "%$search%")
                    ->orWhere('nik', 'like', "%$search%")
                    ->orWhere('no_hp', 'like', "%$search%");
            })
            ->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate]);
            })
            ->paginate(5)
            ->withQueryString();

        return inertia('Patient/Index', [
            'patients' => $patients,
            'filters' => [
                'search' => $search,
                'startDate' => $startDate,
                'endDate' => $endDate,
            ]
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'nik' => 'required',
            'tanggal_lahir' => 'required'
        ], [
            'nama.required' => 'nama tidak boleh kosong',
            'nik.required' => 'nik tidak boleh kosong',
            'tanggal_lahir.required' => 'tanggal lahir tidak boleh kosong'
        ]);

        Patient::create([
            'nama' => $request->nama,
            'nik' => $request->nik,
            'no_hp' => $request->no_hp,
            'tanggal_lahir' => $request->tanggal_lahir
        ]);

        return to_route('patient.index')->with('message', 'data pasien berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required',
            'nik' => 'required',
            'tanggal_lahir' => 'required'
        ], [
            'nama.required' => 'nama tidak boleh kosong',
            'nik.required' => 'nik tidak boleh kosong',
            'tanggal_lahir.required' => 'tanggal lahir tidak boleh kosong'
        ]);

        $patient = Patient::findOr($id);

        $patient->update([
            'nama' => $request->nama,
            'nik' => $request->nik,
            'no_hp' => $request->no_hp,
            'tanggal_lahir' => $request->tanggal_lahir
        ]);

        return to_route('patient.index')->with('message', 'data pasien berhasil diubah');
    }

    public function destroy($id)
    {
        $patient = Patient::findOr($id);
        $patient->delete();

        return to_route('patient.index')->with('message', 'data pasien berhasil dihapus');
    }
}
