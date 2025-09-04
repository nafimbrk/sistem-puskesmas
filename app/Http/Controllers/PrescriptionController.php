<?php

namespace App\Http\Controllers;

use App\Models\Prescription;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PrescriptionController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;
        $startDate = $request->startDate;
        $endDate = $request->endDate;
        $status = $request->status ?? 'semua';

        $prescriptions = Prescription::query()
            ->with(['user', 'queue'])
            ->orderByDesc('id')
            ->when($search, function ($query, $search) {
                $query->where('diagnosis', 'like', "%$search%")
                    ->orWhere('prescription', 'like', "%$search%")
                    ->orWhereHas('queue', function (Builder $query) use ($search) {
                        $query->where('nomor_antrian', 'like', "%$search%");
                    })
                    ->orWhereHas('user', function (Builder $query) use ($search) {
                        $query->where('name', 'like', "%$search%");
                    });
            })
            ->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate]);
            })
            ->when($status && $status !== 'semua', function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->paginate(5)
            ->withQueryString();

        // $patients = Patient::select('id', 'nama')->get();
        // $polis = Poli::select('id', 'nama_poli')->get();

        return inertia('Prescription/Index', [
            'prescriptions' => $prescriptions,
            'filters' => [
                'search' => $search,
                'startDate' => $startDate,
                'endDate' => $endDate,
                'status' => $status
            ],
            // 'patients' => $patients,
            // 'polis' => $polis
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'queue_id' => 'required|exists:queues,id',
            'diagnosis' => 'required|string',
            'prescription' => 'required|string',
            'canvas_image' => 'nullable|string',
        ]);

        $canvasImage = null;

        if ($request->canvas_image) {
            $image = $request->canvas_image; // base64 string
            $imageName = 'prescription_' . time() . '.png';

            // Decode base64
            $imageData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $image));

            // Simpan ke storage/public/prescriptions
            Storage::disk('public')->put('prescriptions/' . $imageName, $imageData);

            $canvasImage = 'prescriptions/' . $imageName;
        }

        Prescription::create([
            'queue_id'     => $request->queue_id,
            'user_id'      => Auth::id(),
            'diagnosis'    => $request->diagnosis,
            'prescription' => $request->prescription,
            'canvas_image' => $canvasImage,
        ]);

        return to_route('prescription.index')->with('success', 'Prescription berhasil ditambahkan!');
    }

    public function ubahStatus($id)
    {
        $prescription = Prescription::findOr($id);

        $prescription->update([
            'status' => 'proses'
        ]);
    }

    public function ubahStatuss($id)
    {
        $prescription = Prescription::findOr($id);

        $prescription->update([
            'status' => 'selesai'
        ]);
    }
}
