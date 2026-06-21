<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\DoctorSchedule;
use App\Models\Patient;
use App\Models\User;
use App\Notifications\AdminNotification;
use App\Notifications\AppointmentStatusNotification;
use App\Notifications\DoctorAppointmentNotification;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AppointmentController extends Controller
{
    public function index(Request $request): View
    {
        $appointments = Appointment::with(['patient.user', 'doctor.user'])
            ->orderBy('appointment_date', 'desc')
            ->paginate(15)->withQueryString();

        return view('admin.appointments.index', compact('appointments'));
    }

    public function doctorIndex(Request $request): View
    {
        $doctor = $this->authUser()->doctor;

        $query = Appointment::where('doctor_id', $doctor->id)
            ->with('patient.user');
            
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('patient.user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        $appointments = $query->orderBy('created_at', 'desc')->paginate(12)->withQueryString();

        return view('doctor.appointments.index', compact('appointments', 'doctor'));
    }

    public function doctorCreate(Request $request): View
    {
        $doctor = $this->authUser()->doctor;

        $treatedPatientIds = Appointment::where('doctor_id', $doctor->id)
            ->distinct()
            ->pluck('patient_id')
            ->toArray();

        $patients = \App\Models\Patient::with('user')
            ->get()
            ->map(function($p) use ($treatedPatientIds) {
                $p->is_previous = in_array($p->id, $treatedPatientIds);
                $p->user_name = $p->user->name ?? '';
                return $p;
            })
            ->sortBy(function($p) {
                return [$p->is_previous ? 0 : 1, $p->user_name];
            })
            ->values();

        return view('doctor.appointments.create', compact('patients', 'doctor'));
    }

    public function doctorStore(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'requested_date' => 'required|date|after:today',
            'disease_type' => 'required|string',
            'patient_description' => 'nullable|string',
            'prescribed_medications' => 'nullable|string',
            'medicine_name' => 'nullable|string',
            'doctor_notes' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $doctor = $this->authUser()->doctor;

        $patient = Patient::findOrFail($validated['patient_id']);
        $error = $this->validateServiceForPatient($patient, $validated['disease_type']);
        if ($error) {
            return back()->withErrors(['disease_type' => $error])->withInput();
        }

        $appointment = Appointment::create([
            'patient_id' => $validated['patient_id'],
            'doctor_id' => $doctor->id,
            'requested_date' => $validated['requested_date'],
            'appointment_date' => null,
            'disease_type' => $validated['disease_type'],
            'patient_description' => $validated['patient_description'] ?? null,
            'medicine_name' => $validated['medicine_name'] ?? null,
            'doctor_notes' => $validated['doctor_notes'] ?? null,
            'notes' => $validated['notes'],
            'status' => 'pending',
        ]);

        $appointment->patient->user->notify(new AppointmentStatusNotification($appointment, 'new_appointment'));
        $this->authUser()->notify(new DoctorAppointmentNotification($appointment, 'appointment_booked'));

        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            $admin->notify(AdminNotification::appointmentCreated($appointment));
        }

        return back()->with('success', 'تم إرسال طلب الموعد للمريض');
    }

    public function patientAppointments(Request $request): View
    {
        $patient = $this->authUser()->patient;
        
        $appointments = Appointment::where('patient_id', $patient->id)
            ->with('doctor.user', 'medicalRecords')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('patient.appointments.index', compact('appointments'));
    }

    public function acceptAppointment(Appointment $appointment): RedirectResponse
    {
        $appointment->update(['status' => 'confirmed']);
        $appointment->doctor->user->notify(new DoctorAppointmentNotification($appointment, 'accepted'));
        return back()->with('success', 'تم قبول الموعد');
    }

    public function declineAppointment(Appointment $appointment): RedirectResponse
    {
        $appointment->update(['status' => 'cancelled']);
        $appointment->doctor->user->notify(new DoctorAppointmentNotification($appointment, 'declined'));
        return back()->with('success', 'تم رفض الموعد');
    }

    public function cancelConfirmed(Appointment $appointment): RedirectResponse
    {
        if ($appointment->status !== 'confirmed') {
            return back()->withErrors(['error' => 'يمكن إلغاء الموعد المؤكد فقط']);
        }

        $appointment->update(['status' => 'cancelled']);
        $appointment->doctor->user->notify(new DoctorAppointmentNotification($appointment, 'cancelled_by_patient'));
        return back()->with('success', 'تم إلغاء الموعد');
    }

    public function create(Request $request): View
    {
        $doctors = Doctor::where('is_active', true)->with('user')->get();
        return view('patient.appointments.create', compact('doctors'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'requested_date' => 'required|date|after:today',
            'notes' => 'nullable|string',
        ]);

        $doctor = $this->authUser()->doctor;

        Appointment::create([
            'patient_id' => $validated['patient_id'],
            'doctor_id' => $doctor->id,
            'requested_date' => $validated['requested_date'],
            'appointment_date' => null,
            'notes' => $validated['notes'],
            'status' => 'pending',
        ]);

        return back()->with('success', 'تم إرسال طلب الموعد للمريض');
    }

    public function edit(Appointment $appointment): View
    {
        $doctor = $this->authUser()->doctor;

        if ($appointment->doctor_id !== $doctor->id) {
            abort(403);
        }

        $treatedPatientIds = Appointment::where('doctor_id', $doctor->id)
            ->distinct()
            ->pluck('patient_id')
            ->toArray();

        $patients = Patient::with('user')
            ->get()
            ->map(function($p) use ($treatedPatientIds) {
                $p->is_previous = in_array($p->id, $treatedPatientIds);
                $p->user_name = $p->user->name ?? '';
                return $p;
            })
            ->sortBy(function($p) {
                return [$p->is_previous ? 0 : 1, $p->user_name];
            })
            ->values();

        return view('doctor.appointments.edit', compact('appointment', 'patients', 'doctor'));
    }

    public function update(Request $request, Appointment $appointment): RedirectResponse
    {
        $doctor = $this->authUser()->doctor;

        if ($appointment->doctor_id !== $doctor->id) {
            abort(403);
        }

        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'requested_date' => 'required|date',
            'disease_type' => 'required|string',
            'patient_description' => 'nullable|string',
            'medicine_name' => 'nullable|string',
            'doctor_notes' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $patient = Patient::findOrFail($validated['patient_id']);
        $error = $this->validateServiceForPatient($patient, $validated['disease_type']);
        if ($error) {
            return back()->withErrors(['disease_type' => $error])->withInput();
        }

        $appointment->update($validated);

        return redirect()->route('doctor.appointments')
            ->with('success', 'تم تحديث الموعد بنجاح');
    }

    public function destroy(Appointment $appointment): RedirectResponse
    {
        $doctor = $this->authUser()->doctor;

        if ($appointment->doctor_id !== $doctor->id) {
            abort(403);
        }

        $appointment->delete();

        return redirect()->route('doctor.appointments')
            ->with('success', 'تم حذف الموعد بنجاح');
    }

    public function confirm(Request $request, Appointment $appointment): RedirectResponse
    {
        $validated = $request->validate([
            'appointment_date' => 'required|date|after:now',
        ]);

        $appointmentDate = Carbon::parse($validated['appointment_date']);
        $doctor = $appointment->doctor;

        // Conflict Detection: check for another confirmed appointment within ±1 hour
        $conflict = Appointment::where('doctor_id', $doctor->id)
            ->where('id', '!=', $appointment->id)
            ->where('status', 'confirmed')
            ->whereBetween('appointment_date', [
                $appointmentDate->copy()->subHour(),
                $appointmentDate->copy()->addHour(),
            ])
            ->first();

        if ($conflict) {
            return back()->withErrors([
                'appointment_date' => 'يوجد تعارض مع موعد ' . $conflict->patient->user->name . ' في ' . $conflict->appointment_date->format('H:i') . ' - يجب أن يكون الفارق ساعة على الأقل',
            ]);
        }

        // Schedule-Aware Validation: check if the time falls within doctor working hours
        $dayMap = [
            0 => 'sunday', 1 => 'monday', 2 => 'tuesday',
            3 => 'wednesday', 4 => 'thursday', 5 => 'friday', 6 => 'saturday',
        ];
        $dayOfWeek = $dayMap[$appointmentDate->dayOfWeek];

        $schedule = DoctorSchedule::where('doctor_id', $doctor->id)
            ->where('day', $dayOfWeek)
            ->where('is_active', true)
            ->first();

        if ($schedule) {
            $aptTime  = $appointmentDate->format('H:i');
            $start    = Carbon::parse($schedule->getRawOriginal('start_time'))->format('H:i');
            $end      = Carbon::parse($schedule->getRawOriginal('end_time'))->format('H:i');

            if ($aptTime < $start || $aptTime > $end) {
                return back()->withErrors([
                    'appointment_date' => 'الوقت خارج ساعات عمل الطبيب (' . $start . ' - ' . $end . ')',
                ]);
            }
        }

        $appointment->update([
            'appointment_date' => $validated['appointment_date'],
            'status' => 'confirmed',
        ]);

        $appointment->patient->user->notify(new AppointmentStatusNotification($appointment, 'confirmed'));

        return back()->with('success', 'تم تأكيد الموعد');
    }

    public function editDate(Request $request, Appointment $appointment): RedirectResponse
    {
        $validated = $request->validate([
            'appointment_date' => 'required|date|after:now',
        ]);

        $oldDate = $appointment->appointment_date?->format('Y-m-d H:i');
        $appointment->update(['appointment_date' => $validated['appointment_date']]);
        $appointment->refresh();

        $appointment->patient->user->notify(new AppointmentStatusNotification($appointment, 'edited'));

        return back()->with('success', 'تم تعديل موعد المريض من ' . ($oldDate ?? '-') . ' إلى ' . $appointment->appointment_date->format('Y-m-d H:i'));
    }

    public function complete(Request $request, Appointment $appointment): RedirectResponse
    {
        if (!$appointment->appointment_date || $appointment->appointment_date->isFuture()) {
            return back()->with('error', 'لا يمكن إكمال موعد قبل تاريخه المقرر');
        }

        $appointment->update(['status' => 'completed']);
        $appointment->patient->user->notify(new AppointmentStatusNotification($appointment, 'completed'));
        return back()->with('success', 'تم إكمال الموعد');
    }

    public function cancel(Request $request, Appointment $appointment): RedirectResponse
    {
        $appointment->update(['status' => 'cancelled']);
        $appointment->patient->user->notify(new AppointmentStatusNotification($appointment, 'cancelled'));

        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            $admin->notify(AdminNotification::appointmentCancelled($appointment));
        }

        return back()->with('success', 'تم إلغاء الموعد');
    }

    public function addNotes(Request $request, Appointment $appointment): RedirectResponse
    {
        $validated = $request->validate([
            'medicine_name' => 'nullable|string',
            'doctor_notes' => 'nullable|string',
        ]);

        $appointment->update($validated);
        $appointment->patient->user->notify(new AppointmentStatusNotification($appointment, 'notes_added'));

        return back()->with('success', 'تم حفظ الملاحظات');
    }

    public function updateStatus(Request $request, Appointment $appointment): RedirectResponse
    {
        $validated = $request->validate([
            'status' => 'required|in:confirmed,completed,cancelled',
        ]);

        $appointment->update(['status' => $validated['status']]);

        $appointment->patient->user->notify(new AppointmentStatusNotification($appointment, $validated['status']));

        return back()->with('success', 'تم تحديث حالة الموعد');
    }

    private function getPatientAgeInMonths(Patient $patient): ?int
    {
        if ($patient->date_of_birth) {
            return Carbon::parse($patient->date_of_birth)->diffInMonths(Carbon::now());
        }
        if ($patient->age !== null) {
            return $patient->age * 12;
        }
        return null;
    }

    private function validateServiceForPatient(Patient $patient, string $diseaseType): ?string
    {
        $ageInMonths = $this->getPatientAgeInMonths($patient);

        // Pregnancy services → female only
        if (in_array($diseaseType, ['فحص حمل', 'فحص ما قبل الحمل', 'استشارة حمل'])) {
            if ($patient->gender !== 'female') {
                return 'خدمات الحمل مخصصة للإناث فقط';
            }
        }

        // Child vaccinations
        $childVaccinations = [
            'تطعيم حديثي الولادة' => [0, 1],
            'تطعيم الشهر الثاني'  => [1, 3],
            'تطعيم الشهر الرابع'  => [3, 5],
            'تطعيم الشهر السادس'  => [5, 7],
            'تطعيم الشهر التاسع'  => [8, 10],
            'تطعيم السنة الأولى'  => [11, 14],
            'تطعيم الصف الأول'    => [60, 84],
        ];

        if (isset($childVaccinations[$diseaseType])) {
            if ($patient->patient_type !== 'child') {
                return 'التطعيمات مخصصة للأطفال فقط';
            }
            [$min, $max] = $childVaccinations[$diseaseType];
            if ($ageInMonths !== null && ($ageInMonths < $min || $ageInMonths > $max)) {
                return 'عمر المريض لا يتناسب مع هذا التطعيم';
            }
        }

        // Chronic disease services
        $chronicServices = ['أمراض القلب', 'السكري والغدد', 'أمراض الدم', 'متابعة الأمراض المزمنة'];
        if (in_array($diseaseType, $chronicServices)) {
            if ($patient->patient_type !== 'chronic_disease') {
                return 'هذه الخدمة مخصصة لمرضى الأمراض المزمنة';
            }
        }

        return null;
    }
}