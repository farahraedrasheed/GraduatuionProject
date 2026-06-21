<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\MedicalReport;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PatientRecordController extends Controller
{
    public function show(Patient $patient): View
    {
        $patient->load('user');
        $doctor = $this->authUser()->doctor;
        
        $appointments = Appointment::where('patient_id', $patient->id)
            ->with('doctor.user')
            ->orderBy('created_at', 'desc')
            ->get();
        
        $lastAppointment = $appointments->first();
        
        $reports = MedicalReport::where('patient_id', $patient->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('doctor.patients.show', compact('patient', 'appointments', 'lastAppointment', 'doctor', 'reports'));
    }

    public function index(Request $request): View
    {
        $doctor = $this->authUser()->doctor;
        
        $patientIds = Appointment::where('doctor_id', $doctor->id)
            ->distinct()
            ->pluck('patient_id')
            ->toArray();
        
        if (empty($patientIds)) {
            $patients = collect();
        } else {
            $query = Patient::whereIn('id', $patientIds);
            
            if ($request->filled('search')) {
                $search = $request->search;
                $patientIdsWithSearch = Patient::whereIn('id', $patientIds)
                    ->whereHas('user', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%")
                          ->orWhere('phone', 'like', "%{$search}%");
                    })
                    ->pluck('id')
                    ->toArray();
                
                $query = Patient::whereIn('id', $patientIdsWithSearch);
            }
            
            $patients = $query->get();
            
            $patients = $patients->map(function($patient) {
                $patient->user_name = $patient->user->name ?? '';
                return $patient;
            })->sortBy('user_name')->values();
        }
        
        return view('doctor.patients.index', compact('patients', 'doctor'));
    }
}