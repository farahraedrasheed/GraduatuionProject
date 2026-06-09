<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\File;

class SettingsController extends Controller
{
    public function index(): View
    {
        return view('admin.settings.index');
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'clinic_name' => 'required|string|max:255',
            'clinic_phone' => 'required|string|max:20',
            'clinic_email' => 'required|email',
            'appointment_duration' => 'required|integer|min:15|max:120',
            'cancellation_hours' => 'required|integer|min:1|max:48',
        ]);

        $envFile = base_path('.env');
        $envContent = File::get($envFile);

        $settings = [
            'CLINIC_NAME' => $validated['clinic_name'],
            'CLINIC_PHONE' => $validated['clinic_phone'],
            'CLINIC_EMAIL' => $validated['clinic_email'],
            'CLINIC_APPOINTMENT_DURATION' => $validated['appointment_duration'],
            'CLINIC_CANCELLATION_HOURS' => $validated['cancellation_hours'],
        ];

        foreach ($settings as $key => $value) {
            $pattern = "/^{$key}=.*/m";
            if (preg_match($pattern, $envContent)) {
                $envContent = preg_replace("/^{$key}=.*/m", "{$key}={$value}", $envContent);
            } else {
                $envContent .= "\n{$key}={$value}";
            }
        }

        File::put($envFile, $envContent);

        foreach ($validated as $key => $value) {
            config(['clinic.' . $key => $value]);
        }

        return back()->with('success', 'تم تحديث الإعدادات بنجاح');
    }
}