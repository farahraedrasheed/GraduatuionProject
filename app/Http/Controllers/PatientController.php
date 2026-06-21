<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class PatientController extends Controller
{
    public function index(Request $request): View
    {
        $query = Patient::with('user');
        
        if ($request->search) {
            $query->whereHas('user', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->gender) {
            $query->where('gender', $request->gender);
        }

        $patients = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();
        return view('admin.patients.index', compact('patients'));
    }

    public function create(): View
    {
        return view('admin.patients.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name'                  => 'required|string|max:255',
            'email'                 => 'required|email|unique:users',
            'password'              => 'required|string|min:8',
            'phone'                 => 'required|string',
            'date_of_birth'         => 'required|date|before:today',
            'gender'                => 'required|in:male,female',
            'patient_type'          => 'required|in:child,pregnant,chronic_disease,other',
            'blood_type'            => 'nullable|string',
            'address'               => 'nullable|string',
            'chronic_disease_type'  => 'nullable|string',
            'chronic_disease_type2' => 'nullable|string',
        ]);

        if ($request->patient_type === 'pregnant' && $request->gender !== 'female') {
            return back()->withErrors(['gender' => 'فقط الإناث يمكن تسجيلهن كحوامل'])->withInput();
        }
        if ($request->patient_type === 'child') {
            $maxBirth = now()->subYears(12)->format('Y-m-d');
            if ($request->date_of_birth <= $maxBirth) {
                return back()->withErrors(['date_of_birth' => 'عمر المريض يتجاوز 12 سنة، لا يمكن تسجيله كطفل'])->withInput();
            }
        }

        $user = User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role'     => 'patient',
        ]);

        Patient::create([
            'user_id'               => $user->id,
            'phone'                 => $validated['phone'],
            'date_of_birth'         => $validated['date_of_birth'],
            'gender'                => $validated['gender'],
            'patient_type'          => $validated['patient_type'],
            'blood_type'            => $validated['blood_type'] ?? null,
            'address'               => $validated['address'] ?? null,
            'chronic_disease_type'  => $validated['chronic_disease_type'] ?? null,
            'chronic_disease_type2' => $validated['chronic_disease_type2'] ?? null,
        ]);

        return redirect()->route('patients.index')->with('success', 'تم إضافة المريض بنجاح');
    }

    public function show(Patient $patient): View
    {
        $patient->load(['user', 'appointments.doctor.user']);
        return view('admin.patients.show', compact('patient'));
    }

    public function edit(Patient $patient): View
    {
        $patient->load('user');
        return view('admin.patients.edit', compact('patient'));
    }

    public function update(Request $request, Patient $patient): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string',
            'date_of_birth' => 'required|date',
            'gender' => 'required|in:male,female',
            'blood_type' => 'nullable|string',
            'address' => 'nullable|string',
            'medical_history' => 'nullable|string',
        ]);

        $patient->user->update(['name' => $validated['name']]);
        $patient->update($validated);

        return redirect()->route('patients.index')->with('success', 'تم تحديث بيانات المريض');
    }

    public function destroy(Patient $patient): RedirectResponse
    {
        $user = $patient->user;
        $patient->delete();
        $user->delete();

        return redirect()->route('patients.index')->with('success', 'تم حذف المريض');
    }
}