<?php

use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\DoctorDashboardController;
use App\Http\Controllers\DoctorScheduleController;
use App\Http\Controllers\MedicalRecordController;
use App\Http\Controllers\MedicalReportController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\PatientDashboardController;
use App\Http\Controllers\PatientRecordController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/about', function () {
    return view('about');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        $user = auth()->user();
        
        return match($user->role) {
            'admin' => redirect()->route('admin.dashboard'),
            'doctor' => redirect()->route('doctor.dashboard'),
            'patient' => redirect()->route('patient.dashboard'),
            default => redirect('/'),
        };
    })->name('dashboard');

    Route::middleware(['role:admin'])->group(function () {
        Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
        Route::resource('doctors', DoctorController::class);
        Route::resource('patients', PatientController::class);
        Route::get('/admin/appointments', [AppointmentController::class, 'index'])->name('appointments.index');
    });

    Route::middleware(['role:doctor'])->group(function () {
        Route::get('/doctor/dashboard', [DoctorDashboardController::class, 'index'])->name('doctor.dashboard');
        Route::get('/doctor/patients', [PatientRecordController::class, 'index'])->name('doctor.patients');
        Route::get('/doctor/patient/{patient}', [PatientRecordController::class, 'show'])->name('doctor.patients.show');
        Route::get('/doctor/appointments', [AppointmentController::class, 'doctorIndex'])->name('doctor.appointments');
        
        Route::get('/doctor/appointments/create', [AppointmentController::class, 'doctorCreate'])->name('doctor.appointments.create');
        Route::post('/doctor/appointments', [AppointmentController::class, 'doctorStore'])->name('doctor.appointments.store');
        
        Route::get('/doctor/appointments/{appointment}/edit', [AppointmentController::class, 'edit'])->name('doctor.appointments.edit');
        Route::put('/doctor/appointments/{appointment}', [AppointmentController::class, 'update'])->name('doctor.appointments.update');
        Route::delete('/doctor/appointments/{appointment}', [AppointmentController::class, 'destroy'])->name('doctor.appointments.destroy');
        Route::patch('/doctor/appointments/{appointment}/confirm', [AppointmentController::class, 'confirm'])->name('appointments.confirm');
        Route::patch('/doctor/appointments/{appointment}/complete', [AppointmentController::class, 'complete'])->name('appointments.complete');
        Route::patch('/doctor/appointments/{appointment}/cancel', [AppointmentController::class, 'cancel'])->name('appointments.cancel');
        Route::patch('/doctor/appointments/{appointment}/edit-date', [AppointmentController::class, 'editDate'])->name('appointments.edit-date');
        
        Route::post('/doctor/appointments/{appointment}/notes', [AppointmentController::class, 'addNotes'])->name('appointments.addNotes');
        
        Route::get('/doctor/appointments/{appointment}/record', [MedicalRecordController::class, 'create'])->name('medical-records.create');
        Route::post('/doctor/appointments/{appointment}/record', [MedicalRecordController::class, 'store'])->name('medical-records.store');
        Route::get('/doctor/patients/{patient}/history', [MedicalRecordController::class, 'patientHistory'])->name('medical-records.patient-history');
        Route::get('/doctor/patients/{patient}/add-record', [MedicalRecordController::class, 'createForPatient'])->name('medical-records.create-for-patient');
        Route::post('/doctor/patients/{patient}/add-record', [MedicalRecordController::class, 'storeForPatient'])->name('medical-records.store-for-patient');
        Route::get('/doctor/schedule', [DoctorScheduleController::class, 'index'])->name('doctor.schedule');
        Route::post('/doctor/schedule', [DoctorScheduleController::class, 'store'])->name('doctor.schedule.store');
        Route::patch('/doctor/schedule/{schedule}', [DoctorScheduleController::class, 'update'])->name('doctor.schedule.update');
        Route::delete('/doctor/schedule/{schedule}', [DoctorScheduleController::class, 'destroy'])->name('doctor.schedule.destroy');

        Route::get('/doctor/patient/report/{report}/download', [MedicalReportController::class, 'doctorDownload'])->name('doctor.patients.report.download');
    });

    Route::middleware(['role:patient'])->group(function () {
        Route::get('/patient/dashboard', [PatientDashboardController::class, 'index'])->name('patient.dashboard');
        Route::get('/patient/appointments', [AppointmentController::class, 'patientAppointments'])->name('patient.appointments');
        Route::patch('/patient/appointments/{appointment}/accept', [AppointmentController::class, 'acceptAppointment'])->name('patient.appointments.accept');
        Route::patch('/patient/appointments/{appointment}/decline', [AppointmentController::class, 'declineAppointment'])->name('patient.appointments.decline');
        Route::patch('/patient/appointments/{appointment}/cancel-confirmed', [AppointmentController::class, 'cancelConfirmed'])->name('patient.appointments.cancel-confirmed');
        Route::get('/patient/medical-records', [MedicalRecordController::class, 'patientIndex'])->name('patient.medical-records');
        Route::get('/patient/medical-records/print', [MedicalRecordController::class, 'printView'])->name('patient.medical-records.print');
        Route::get('/patient/reports', [\App\Http\Controllers\MedicalReportController::class, 'patientIndex'])->name('patient.reports');
        Route::post('/patient/reports/upload', [\App\Http\Controllers\MedicalReportController::class, 'upload'])->name('patient.reports.upload');
        Route::get('/patient/reports/{report}/download', [\App\Http\Controllers\MedicalReportController::class, 'download'])->name('patient.reports.download');
        Route::delete('/patient/reports/{report}', [\App\Http\Controllers\MedicalReportController::class, 'destroy'])->name('patient.reports.destroy');
    });

    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.read-all');
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';