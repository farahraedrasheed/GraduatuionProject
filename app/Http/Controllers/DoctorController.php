<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\User;
use App\Notifications\NewDoctorRegisteredNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class DoctorController extends Controller
{
    public function index(Request $request): View
    {
        $query = Doctor::with('user');

        if ($request->search) {
            $query->whereHas('user', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            })->orWhere('specialty', 'like', '%' . $request->search . '%');
        }

        if ($request->specialty) {
            $query->where('specialty', 'like', '%' . $request->specialty . '%');
        }

        $doctors = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();
        return view('admin.doctors.index', compact('doctors'));
    }

    public function create(): View
    {
        return view('admin.doctors.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8',
            'specialty' => 'required|string|max:255',
            'license_number' => 'required|string|unique:doctors',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'doctor',
        ]);

        Doctor::create([
            'user_id' => $user->id,
            'specialty' => $validated['specialty'],
            'license_number' => $validated['license_number'],
        ]);

        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            $admin->notify(new NewDoctorRegisteredNotification($user));
        }

        return redirect()->route('doctors.index')->with('success', 'تم إضافة الطبيب بنجاح');
    }

    public function show(Doctor $doctor): View
    {
        $doctor->load(['user', 'patients.user']);
        return view('admin.doctors.show', compact('doctor'));
    }

    public function edit(Doctor $doctor): View
    {
        $doctor->load('user');
        return view('admin.doctors.edit', compact('doctor'));
    }

    public function update(Request $request, Doctor $doctor): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'specialty' => 'required|string|max:255',
            'license_number' => 'required|string|unique:doctors,license_number,'.$doctor->id,
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        $doctor->user->update(['name' => $validated['name']]);
        $doctor->update($validated);

        return redirect()->route('doctors.index')->with('success', 'تم تحديث البيانات');
    }

    public function destroy(Doctor $doctor): RedirectResponse
    {
        $user = $doctor->user;
        $doctor->delete();
        $user->delete();

        return redirect()->route('doctors.index')->with('success', 'تم حذف الطبيب');
    }
}