<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Patient;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'in:patient,doctor'],
        ];

        if ($request->role === 'patient') {
            $rules['phone'] = ['required', 'string'];
            $rules['date_of_birth'] = ['required', 'date', 'before:today'];
            $rules['gender'] = ['required', 'in:male,female'];
            $rules['patient_type'] = ['required', 'in:child,pregnant,chronic_disease,other'];
        }

        if ($request->role === 'doctor') {
            $rules['specialty'] = ['required', 'string'];
        }

        $request->validate($rules);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role ?? 'patient',
        ]);

        if ($request->role === 'patient') {
            Patient::create([
                'user_id' => $user->id,
                'phone' => $request->phone,
                'date_of_birth' => $request->date_of_birth,
                'gender' => $request->gender,
                'patient_type' => $request->patient_type,
                'chronic_disease_type' => $request->chronic_disease_type,
                'chronic_disease_type2' => $request->chronic_disease_type2,
            ]);
        } else {
            $user->doctor()->create([
                'specialty' => $request->specialty,
                'license_number' => 'DOC' . $user->id,
                'bio' => '',
                'is_active' => true,
            ]);
        }

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}