<?php

namespace App\Http\Controllers;

use App\Models\Poli;
use Illuminate\Http\Request;

class PoliController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;
        $startDate = $request->startDate;
        $endDate = $request->endDate;

        $polis = Poli::query()
            ->orderByDesc('id')
            ->when($search, function ($query, $search) {
                $query->where('nama_poli', 'like', "%$search%")
                    ->orWhere('kode_poli', 'like', "%$search%");
            })
            ->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate]);
            })
            ->paginate(5)
            ->withQueryString();

        return inertia('Poli/Index', [
            'polis' => $polis,
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
            'nama_poli' => 'required',
            'kode_poli' => 'required'
        ], [
            'nama_poli.required' => 'nama tidak boleh kosong',
            'kode_poli.required' => 'kode tidak boleh kosong'
        ]);

        Poli::create([
            'nama_poli' => $request->nama_poli,
            'kode_poli' => $request->kode_poli
        ]);

        return to_route('poli.index')->with('message', 'data poli berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_poli' => 'required',
            'kode_poli' => 'required'
        ], [
            'nama_poli.required' => 'nama tidak boleh kosong',
            'kode_poli.required' => 'kode tidak boleh kosong'
        ]);

        $poli = Poli::findOr($id);

        $poli->update([
            'nama_poli' => $request->nama_poli,
            'kode_poli' => $request->kode_poli
        ]);

        return to_route('poli.index')->with('message', 'data poli berhasil diubah');
    }

    public function destroy($id)
    {
        $poli = Poli::findOr($id);
        $poli->delete();

        return to_route('poli.index')->with('message', 'data poli berhasil dihapus');
    }
}
