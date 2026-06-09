@extends('layouts.app')

@section('title', 'لوحة تحكم المسؤول - المركز الطبي')

@section('styles')
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endsection

@section('content')
    <div class="container" style="max-width: 1200px; margin: 2rem auto; padding: 0 1rem;">
        <h1 style="color: #1f2937; margin-bottom: 0.5rem;">لوحة تحكم المسؤول</h1>
        <p style="color: #6b7280; margin-bottom: 2rem;">مرحباً {{ Auth::user()->name }}</p>
        
        <!-- Stats Cards -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
            <div style="background: white; padding: 1.5rem; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                <h3 style="color: #6b7280; font-size: 0.9rem; margin-bottom: 0.5rem;">إجمالي الأطباء</h3>
                <p style="color: #3b82f6; font-size: 2rem; font-weight: bold;">{{ \App\Models\Doctor::count() }}</p>
            </div>
            <div style="background: white; padding: 1.5rem; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                <h3 style="color: #6b7280; font-size: 0.9rem; margin-bottom: 0.5rem;">إجمالي المرضى</h3>
                <p style="color: #10b981; font-size: 2rem; font-weight: bold;">{{ \App\Models\Patient::count() }}</p>
            </div>
            <div style="background: white; padding: 1.5rem; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                <h3 style="color: #6b7280; font-size: 0.9rem; margin-bottom: 0.5rem;">المواعيد المعلقة</h3>
                <p style="color: #f59e0b; font-size: 2rem; font-weight: bold;">{{ \App\Models\Appointment::where('status', 'pending')->count() }}</p>
            </div>
            <div style="background: white; padding: 1.5rem; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                <h3 style="color: #6b7280; font-size: 0.9rem; margin-bottom: 0.5rem;">إجمالي المواعيد</h3>
                <p style="color: #8b5cf6; font-size: 2rem; font-weight: bold;">{{ \App\Models\Appointment::count() }}</p>
            </div>
        </div>

        <!-- Quick Links -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-bottom: 2rem;">
            <a href="{{ route('doctors.index') }}" style="background: linear-gradient(135deg, #3b82f6, #1d4ed8); color: white; padding: 1rem; border-radius: 10px; text-align: center; text-decoration: none; font-weight: 600;">
                إدارة الأطباء
            </a>
            <a href="{{ route('patients.index') }}" style="background: linear-gradient(135deg, #10b981, #059669); color: white; padding: 1rem; border-radius: 10px; text-align: center; text-decoration: none; font-weight: 600;">
                إدارة المرضى
            </a>
            <a href="{{ route('appointments.index') }}" style="background: linear-gradient(135deg, #f59e0b, #d97706); color: white; padding: 1rem; border-radius: 10px; text-align: center; text-decoration: none; font-weight: 600;">
                عرض المواعيد
            </a>
        </div>

        <!-- Recent Appointments -->
        <div style="background: white; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); padding: 1.5rem;">
            <h2 style="color: #1f2937; margin-bottom: 1rem;">أحدث المواعيد</h2>
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="border-bottom: 2px solid #e5e7eb;">
                        <th style="padding: 0.75rem; text-align: right;">المريض</th>
                        <th style="padding: 0.75rem; text-align: right;">الطبيب</th>
                        <th style="padding: 0.75rem; text-align: right;">التاريخ</th>
                        <th style="padding: 0.75rem; text-align: right;">الحالة</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse(\App\Models\Appointment::with(['patient.user', 'doctor.user'])->orderBy('appointment_date', 'desc')->take(10)->get() as $appointment)
                    <tr style="border-bottom: 1px solid #e5e7eb;">
                        <td style="padding: 0.75rem;">{{ $appointment->patient->user->name ?? '-' }}</td>
                        <td style="padding: 0.75rem;">د. {{ $appointment->doctor->user->name ?? '-' }}</td>
                        <td style="padding: 0.75rem;">{{ $appointment->appointment_date ? $appointment->appointment_date->format('Y-m-d H:i') : '-' }}</td>
                        <td style="padding: 0.75rem;">
                            <span style="padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.875rem; 
                                @if($appointment->status == 'pending') background: #fef3c7; color: #92400e;
                                @elseif($appointment->status == 'confirmed') background: #dbeafe; color: #1e40af;
                                @else background: #d1fae5; color: #065f46; @endif">
                                @if($appointment->status == 'pending') معلق
                                @elseif($appointment->status == 'confirmed') مؤكد
                                @else مكتمل @endif
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" style="padding: 1rem; text-align: center; color: #6b7280;">لا توجد مواعيد</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
