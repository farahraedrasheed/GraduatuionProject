<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function appointments(Request $request): View
    {
        $query = Appointment::with(['patient.user', 'doctor.user']);

        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->doctor_id) {
            $query->where('doctor_id', $request->doctor_id);
        }

        if ($request->date_from) {
            $query->whereDate('appointment_date', '>=', $request->date_from);
        }

        if ($request->date_to) {
            $query->whereDate('appointment_date', '<=', $request->date_to);
        }

        $appointments = $query->orderBy('appointment_date', 'desc')->get();
        $doctors = Doctor::with('user')->get();

        $stats = [
            'total' => $appointments->count(),
            'completed' => $appointments->where('status', 'completed')->count(),
            'pending' => $appointments->where('status', 'pending')->count(),
            'cancelled' => $appointments->where('status', 'cancelled')->count(),
        ];

        return view('admin.reports.appointments', compact('appointments', 'doctors', 'stats'));
    }

    public function patients(Request $request): View
    {
        $query = Patient::with('user');

        if ($request->gender) {
            $query->where('gender', $request->gender);
        }

        if ($request->blood_type) {
            $query->where('blood_type', $request->blood_type);
        }

        $patients = $query->orderBy('created_at', 'desc')->get();

        return view('admin.reports.patients', compact('patients'));
    }

    public function doctors(Request $request): View
    {
        $query = Doctor::with('user');

        if ($request->specialty) {
            $query->where('specialty', $request->specialty);
        }

        if ($request->is_active !== null && $request->is_active !== '') {
            $query->where('is_active', $request->is_active);
        }

        $doctors = $query->orderBy('created_at', 'desc')->get();

        return view('admin.reports.doctors', compact('doctors'));
    }
}