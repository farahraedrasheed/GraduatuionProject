<?php

namespace Database\Seeders;

use App\Models\Doctor;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::create([
            'name' => 'مدير النظام',
            'email' => 'admin@clinic.com',
            'password' => bcrypt('admin123'),
            'role' => 'admin',
        ]);

        $doctor = User::create([
            'name' => 'د. أحمد محمد',
            'email' => 'doctor@clinic.com',
            'password' => bcrypt('doctor123'),
            'role' => 'doctor',
        ]);

        Doctor::create([
            'user_id' => $doctor->id,
            'specialty' => 'طب عام',
            'license_number' => 'DOC001',
            'bio' => 'طبيب عام بخبرة 10 سنوات',
            'is_active' => true,
        ]);

        $patient = User::create([
            'name' => 'احمد الخالدي',
            'email' => 'patient@clinic.com',
            'password' => bcrypt('patient123'),
            'role' => 'patient',
        ]);

        $patient->patient()->create([
            'date_of_birth' => '1990-01-15',
            'gender' => 'male',
            'phone' => '0599999999',
            'blood_type' => 'O+',
        ]);
    }
}