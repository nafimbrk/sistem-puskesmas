<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalPatient = Patient::get()->count();
        return inertia('Dashboard', compact('totalPatient'));
    }
}
