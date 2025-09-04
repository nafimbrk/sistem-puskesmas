<?php

use App\Http\Controllers\PatientController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PoliController;
use App\Http\Controllers\PrescriptionController;
use App\Http\Controllers\QueueController;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard')->middleware('auth');

Route::get('/patient', [PatientController::class, 'index'])->name('patient.index')->middleware('auth');
Route::post('/patient', [PatientController::class, 'store'])->name('patient.store')->middleware('auth');
Route::put('/patient/{id}', [PatientController::class, 'update'])->name('patient.update')->middleware('auth');
Route::delete('/patient/{id}', [PatientController::class, 'destroy'])->name('patient.destroy')->middleware('auth');

Route::get('/poli', [PoliController::class, 'index'])->name('poli.index')->middleware('auth');
Route::post('/poli', [PoliController::class, 'store'])->name('poli.store')->middleware('auth');
Route::put('/poli/{id}', [PoliController::class, 'update'])->name('poli.update')->middleware('auth');
Route::delete('/poli/{id}', [PoliController::class, 'destroy'])->name('poli.destroy')->middleware('auth');

Route::get('/queue', [QueueController::class, 'index'])->name('queue.index')->middleware('auth');
Route::post('/queue', [QueueController::class, 'store'])->name('queue.store')->middleware('auth');
Route::put('/queue/{id}', [QueueController::class, 'update'])->name('queue.update')->middleware('auth');
Route::delete('/queue/{id}', [QueueController::class, 'destroy'])->name('queue.destroy')->middleware('auth');
Route::put('/status/{id}', [QueueController::class, 'ubahStatus'])->name('queue.ubahStatus')->middleware('auth');
Route::put('/statuss/{id}', [QueueController::class, 'ubahStatuss'])->name('queue.ubahStatuss')->middleware('auth');
Route::get('/queue/prescription/{queueId}', [QueueController::class, 'createPrescription'])->name('queue.createPrescription')->middleware('auth');

Route::get('/prescription', [PrescriptionController::class, 'index'])->name('prescription.index')->middleware('auth');
Route::post('/prescription', [PrescriptionController::class, 'store'])->name('prescription.store')->middleware('auth');
Route::put('/prescription/{id}', [PrescriptionController::class, 'update'])->name('prescription.update')->middleware('auth');
Route::delete('/prescription/{id}', [PrescriptionController::class, 'destroy'])->name('prescription.destroy')->middleware('auth');
Route::put('/statusRsp/{id}', [PrescriptionController::class, 'ubahStatus'])->name('prescription.ubahStatus')->middleware('auth');
Route::put('/statussRsp/{id}', [PrescriptionController::class, 'ubahStatuss'])->name('prescription.ubahStatuss')->middleware('auth');

Route::get('/login', [AuthController::class, 'login'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'loginStore'])->name('login.store')->middleware('guest');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');
Route::get('/register', [AuthController::class, 'register'])->name('register')->middleware('guest');
Route::post('/register', [AuthController::class, 'registerStore'])->name('register.store')->middleware('guest');
