<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit(Request $request): View
    {
        $user = $request->user();
        $profile = null;

        if ($user->role === 'doctor' && $user->doctor) {
            $profile = $user->doctor;
        } elseif ($user->role === 'patient' && $user->patient) {
            $profile = $user->patient;
        }

        return view('profile.edit', [
            'user' => $user,
            'profile' => $profile,
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
        ]);

        $user->fill($validated);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        if ($user->role === 'patient' && $user->patient) {
$patientData = $request->validate([
                'name' => 'required|string',
                'phone' => 'required|string',
                'date_of_birth' => 'required|date',
                'gender' => 'required|in:male,female',
                'blood_type' => 'nullable|string',
                'address' => 'nullable|string',
                'patient_type' => 'nullable|string',
                'chronic_disease_type' => 'nullable|string',
                'chronic_disease_type2' => 'nullable|string',
            ]);

            $user->update(['name' => $patientData['name']]);
            unset($patientData['name']);
            $user->patient->update($patientData);
        }

        if ($user->role === 'doctor' && $user->doctor) {
            $doctorData = $request->validate([
                'specialty' => 'required|string',
                'bio' => 'nullable|string',
            ]);

            $user->doctor->update($doctorData);
        }

        return Redirect::route('profile.edit')->with('status', 'تم تحديث الملف الشخصي بنجاح');
    }

    public function destroy(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => 'required|current_password',
        ], [], ['password' => __('Password')]);

        $user = $request->user();
        Auth::logout();
        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'current_password' => 'required|current_password',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return Redirect::route('profile.edit')->with('password_status', 'تم تغيير كلمة السر بنجاح');
    }
}