<?php

use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\ConsultationController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Doctor\DoctorController;
use App\Http\Controllers\Secretary\SecretaryController;
use Illuminate\Support\Facades\Route;

// ─── Public routes ────────────────────────────────────────────
Route::get('/', fn() => redirect()->route('login'));

// ─── Authentication ───────────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/login',    [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login',   [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register',[AuthController::class, 'register']);
    Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// ─── Admin routes ─────────────────────────────────────────────
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    // User management
    Route::get('/users',              [AdminController::class, 'users'])->name('users');
    Route::get('/users/create',       [AdminController::class, 'createUser'])->name('users.create');
    Route::post('/users',             [AdminController::class, 'storeUser'])->name('users.store');
    Route::get('/users/{user}/edit',  [AdminController::class, 'editUser'])->name('users.edit');
    Route::put('/users/{user}',       [AdminController::class, 'updateUser'])->name('users.update');
    Route::patch('/users/{user}/toggle', [AdminController::class, 'toggleUser'])->name('users.toggle');
    Route::delete('/users/{user}',    [AdminController::class, 'destroyUser'])->name('users.destroy');

    // Doctor availability
    Route::get('/doctors/{doctor}/availability',   [AdminController::class, 'doctorAvailability'])->name('doctors.availability');
    Route::post('/doctors/{doctor}/availability',  [AdminController::class, 'updateAvailability'])->name('doctors.availability.update');
});

// ─── Doctor routes ────────────────────────────────────────────
Route::prefix('doctor')->name('doctor.')->middleware(['auth', 'role:doctor'])->group(function () {
    Route::get('/dashboard', [DoctorController::class, 'dashboard'])->name('dashboard');
});

// ─── Secretary routes ─────────────────────────────────────────
Route::prefix('secretary')->name('secretary.')->middleware(['auth', 'role:secretary'])->group(function () {
    Route::get('/dashboard', [SecretaryController::class, 'dashboard'])->name('dashboard');
});

// ─── Patient own dashboard ────────────────────────────────────
Route::prefix('patient')->name('patient.')->middleware(['auth', 'role:patient'])->group(function () {
    Route::get('/dashboard', [PatientController::class, 'dashboard'])->name('dashboard');
});

// ─── Shared authenticated routes ──────────────────────────────
Route::middleware('auth')->group(function () {

    // ── Patients ──────────────────────────────────────────────
    Route::prefix('patients')->name('patients.')->middleware('role:admin,doctor,secretary')->group(function () {
        Route::get('/',          [PatientController::class, 'index'])->name('index');
        Route::get('/{patient}', [PatientController::class, 'show'])->name('show');
        Route::get('/{patient}/edit', [PatientController::class, 'edit'])->name('edit');
        Route::put('/{patient}', [PatientController::class, 'update'])->name('update');
    });

    // Patient can view their own profile
    Route::get('/my-profile',        [PatientController::class, 'show'])->name('patients.my-profile')
         ->middleware('role:patient')
         ->defaults('patient', null);

    // ── Appointments ──────────────────────────────────────────
    Route::prefix('appointments')->name('appointments.')->group(function () {
        Route::get('/',                    [AppointmentController::class, 'index'])->name('index');
        Route::get('/calendar',            [AppointmentController::class, 'calendar'])->name('calendar');
        Route::get('/create',              [AppointmentController::class, 'create'])->name('create');
        Route::post('/',                   [AppointmentController::class, 'store'])->name('store');
        Route::get('/{appointment}',       [AppointmentController::class, 'show'])->name('show');
        Route::get('/{appointment}/edit',  [AppointmentController::class, 'edit'])->name('edit');
        Route::put('/{appointment}',       [AppointmentController::class, 'update'])->name('update');
        Route::patch('/{appointment}/confirm', [AppointmentController::class, 'confirm'])->name('confirm');
        Route::patch('/{appointment}/cancel',  [AppointmentController::class, 'cancel'])->name('cancel');
        Route::get('/slots/available', [AppointmentController::class, 'availableSlots'])->name('slots');
    });

    // ── Consultations ─────────────────────────────────────────
    Route::prefix('consultations')->name('consultations.')->group(function () {
        Route::get('/appointment/{appointment}/create', [ConsultationController::class, 'create'])->name('create');
        Route::post('/appointment/{appointment}',       [ConsultationController::class, 'store'])->name('store');
        Route::get('/{consultation}',                  [ConsultationController::class, 'show'])->name('show');
        Route::get('/{consultation}/edit',             [ConsultationController::class, 'edit'])->name('edit');
        Route::put('/{consultation}',                  [ConsultationController::class, 'update'])->name('update');
        Route::get('/patient/{patient}/history',       [ConsultationController::class, 'patientHistory'])->name('patient-history');
    });

    // ── PDF Exports ───────────────────────────────────────────
    Route::get('/prescriptions/{prescription}/pdf', [ConsultationController::class, 'exportPrescriptionPdf'])->name('prescriptions.pdf');
    Route::get('/consultations/{consultation}/pdf', [ConsultationController::class, 'exportConsultationPdf'])->name('consultations.pdf');
});
