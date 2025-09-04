<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\Poli;
use App\Models\Prescription;
use App\Models\Queue;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalPatient = Patient::get()->count();
        $totalPoli = Poli::get()->count();
        $totalQueue = Queue::get()->count();
        $totalPrescription = Prescription::get()->count();
        return inertia('Dashboard', compact('totalPatient', 'totalPoli', 'totalQueue', 'totalPrescription'));
    }
}
