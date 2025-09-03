<?php

use App\Http\Controllers\PatientController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PoliController;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard')->middleware('auth');

Route::get('/patient', [PatientController::class, 'index'])->name('patient.index')->middleware('auth');
Route::post('/patient', [PatientController::class, 'store'])->name('patient.store')->middleware('auth');
Route::put('/patient/{id}', [PatientController::class, 'update'])->name('patient.update')->middleware('auth');
Route::delete('/patient/{id}', [PatientController::class, 'destroy'])->name('patient.destroy')->middleware('auth');

Route::get('/poli', [PoliController::class, 'index'])->name('poli.index')->middleware('auth');
Route::post('/poli', [PoliController::class, 'store'])->name('poli.store')->middleware('auth');
Route::put('/poli/{id}', [PoliController::class, 'update'])->name('poli.update')->middleware('auth');
Route::delete('/poli/{id}', [PoliController::class, 'destroy'])->name('poli.destroy')->middleware('auth');







Route::get('/login', [AuthController::class, 'login'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'loginStore'])->name('login.store')->middleware('guest');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');
Route::get('/register', [AuthController::class, 'register'])->name('register')->middleware('guest');
Route::post('/register', [AuthController::class, 'registerStore'])->name('register.store')->middleware('guest');

