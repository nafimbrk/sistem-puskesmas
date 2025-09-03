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
            ->when($search, function ($query, $search) {
                $query->where('nama_poli', 'like', "%$search%")
                    ->orWhere('kode_poli', 'like', "%$search%");
            })
            ->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate]);
            })
            ->paginate(2)
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
}
