<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\Poli;
use App\Models\Queue;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class QueueController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;
        $startDate = $request->startDate;
        $endDate = $request->endDate;
        $status = $request->status ?? 'semua';
        $poli = $request->poli ?? 'semua';

        $queues = Queue::query()
            ->with(['patient', 'poli'])
            ->orderByDesc('id')
            ->when($search, function ($query, $search) {
                $query->where('nomor_antrian', 'like', "%$search%")
                    ->orWhereHas('patient', function (Builder $query) use ($search) {
                        $query->where('nama', 'like', "%$search%");
                    })
                    ->orWhereHas('poli', function (Builder $query) use ($search) {
                        $query->where('nama_poli', 'like', "%$search%");
                    });
            })
            ->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate]);
            })
            ->when($status !== 'semua', function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->when($poli && $poli !== 'semua', function ($query) use ($poli) {
                $query->whereHas('poli', function ($q) use ($poli) {
                    $q->where('nama_poli', $poli);
                });
            })
            ->paginate(5)
            ->withQueryString();

        $patients = Patient::select('id', 'nama')->get();
        $polis = Poli::select('id', 'nama_poli')->get();

        return inertia('Queue/Index', [
            'queues' => $queues,
            'filters' => [
                'search' => $search,
                'startDate' => $startDate,
                'endDate' => $endDate,
                'status' => $status
            ],
            'patients' => $patients,
            'polis' => $polis
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'patient_id' => 'required',
            'poli_id' => 'required'
        ], [
            'patient_id.required' => 'Harus pilih pasien',
            'poli_id.required' => 'Harus pilih poli'
        ]);

        $lastNumber = Queue::whereDate('created_at', now()->toDateString())
            ->max('nomor_antrian');

        $nextNumber = $lastNumber ? $lastNumber + 1 : 1;

        Queue::create([
            'patient_id' => $request->patient_id,
            'poli_id' => $request->poli_id,
            'nomor_antrian' => $nextNumber,
            'status' => 'menunggu'
        ]);

        return to_route('queue.index')->with('message', 'Data antrian berhasil dibuat');
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'patient_id' => 'required',
            'poli_id' => 'required'
        ], [
            'patient_id.required' => 'Harus pilih pasien',
            'poli_id.required' => 'Harus pilih poli'
        ]);

        $queue = Queue::findOrFail($id);

        $queue->update([
            'patient_id' => $request->patient_id,
            'poli_id'   => $request->poli_id,
        ]);

        return to_route('queue.index')->with('message', 'Data antrian berhasil diperbarui');
    }


    public function destroy($id)
    {
        $queue = Queue::findOr($id);
        $queue->delete();

        return to_route('queue.index')->with('message', 'data antrian berhasil dihapus');
    }

    public function ubahStatus($id)
    {
        $queue = Queue::findOr($id);

        $queue->update([
            'status' => 'dipanggil'
        ]);
    }

    public function ubahStatuss($id)
    {
        $queue = Queue::findOr($id);

        $queue->update([
            'status' => 'selesai'
        ]);
    }

    public function createPrescription($queueId)
    {
        return inertia('Prescription/Create', compact('queueId'));
    }
}
