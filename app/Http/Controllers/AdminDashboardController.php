<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Patient;
use Carbon\Carbon;
use Illuminate\View\View;

class AdminDashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            'doctors'               => Doctor::count(),
            'patients'              => Patient::count(),
            'total_appointments'    => Appointment::count(),
            'pending_appointments'  => Appointment::where('status', 'pending')->count(),
            'confirmed_appointments'=> Appointment::where('status', 'confirmed')->count(),
            'completed_appointments'=> Appointment::where('status', 'completed')->count(),
            'cancelled_appointments'=> Appointment::where('status', 'cancelled')->count(),
            'today_appointments'    => Appointment::whereDate('appointment_date', today())->count(),
        ];

        $monthlyAppointments = Appointment::selectRaw('MONTH(appointment_date) as month, COUNT(*) as count')
            ->whereYear('appointment_date', Carbon::now()->year)
            ->groupBy('month')
            ->pluck('count', 'month')
            ->toArray();

        $monthlyData = [];
        for ($i = 1; $i <= 12; $i++) {
            $monthlyData[] = $monthlyAppointments[$i] ?? 0;
        }

        // Patient type distribution
        $patientTypes = Patient::selectRaw('patient_type, COUNT(*) as count')
            ->groupBy('patient_type')
            ->pluck('count', 'patient_type')
            ->toArray();

        $patientTypeLabels = ['child' => 'أطفال', 'pregnant' => 'حوامل', 'chronic_disease' => 'أمراض مزمنة', 'other' => 'أخرى'];
        $patientTypeData   = [];
        $patientTypeNames  = [];
        foreach ($patientTypeLabels as $key => $label) {
            $patientTypeNames[] = $label;
            $patientTypeData[]  = $patientTypes[$key] ?? 0;
        }

        // Top 5 doctors by appointment count this month
        $topDoctors = Doctor::withCount(['appointments as month_appointments' => function ($q) {
            $q->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year);
        }])->with('user')->orderByDesc('month_appointments')->take(5)->get();

        $recentAppointments = Appointment::with(['patient.user', 'doctor.user'])
            ->orderBy('appointment_date', 'desc')
            ->take(10)
            ->get();

        $recentDoctors = Doctor::with('user')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'stats', 'monthlyData', 'recentAppointments', 'recentDoctors',
            'patientTypeData', 'patientTypeNames', 'topDoctors'
        ));
    }
}