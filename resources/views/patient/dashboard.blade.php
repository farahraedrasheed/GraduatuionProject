@extends('layouts.app')

@section('title', 'لوحة تحكم المريض - المركز الطبي')

@section('styles')
    @vite('resources/css/app.css')
    <style>
        body { font-family: 'Cairo', sans-serif; }
    </style>
@endsection

@section('content')
    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <h1 style="color: #1f2937; margin-bottom: 0.5rem;">لوحة تحكم المريض</h1>
        <p style="color: #6b7280; margin-bottom: 2rem;">
            @if($patient->patient_type == 'child') فئة المريض: طفل (طب الأطفال)
            @elseif($patient->patient_type == 'pregnant') فئة المريض: حامل (نساء وتوليد)
            @elseif($patient->patient_type == 'chronic_disease') فئة المريض: مريض مزمن
            @else فئة المريض: أخرى @endif
        </p>
        
        <!-- Quick Actions -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-bottom: 2rem;">
            <a href="{{ route('patient.appointments') }}" style="background: linear-gradient(135deg, #3b82f6, #1d4ed8); color: white; padding: 1.5rem; border-radius: 10px; text-align: center; text-decoration: none;">
                <svg style="width: 2rem; height: 2rem; margin: 0 auto 0.5rem; display: block;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <p style="font-weight: bold;">مواعيدي</p>
            </a>
            <a href="{{ route('patient.medical-records') }}" style="background: linear-gradient(135deg, #10b981, #059669); color: white; padding: 1.5rem; border-radius: 10px; text-align: center; text-decoration: none;">
                <svg style="width: 2rem; height: 2rem; margin: 0 auto 0.5rem; display: block;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <p style="font-weight: bold;">السجلات الطبية</p>
            </a>
            <a href="{{ route('profile.edit') }}" style="background: linear-gradient(135deg, #8b5cf6, #7c3aed); color: white; padding: 1.5rem; border-radius: 10px; text-align: center; text-decoration: none;">
                <svg style="width: 2rem; height: 2rem; margin: 0 auto 0.5rem; display: block;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                <p style="font-weight: bold;">ملفي الشخصي</p>
            </a>
        </div>

        <!-- Patient Info -->
        <div style="background: white; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); padding: 1.5rem; margin-bottom: 2rem;">
            <h2 style="color: #1f2937; margin-bottom: 1rem;">معلوماتك الشخصية</h2>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
                <div><span style="color: #6b7280;">العمر:</span> {{ is_numeric($patient->age) ? $patient->age . ' سنة' : $patient->age }}</div>
                <div><span style="color: #6b7280;">تاريخ الميلاد:</span> {{ $patient->date_of_birth ? $patient->date_of_birth->format('Y-m-d') : '-' }}</div>
                <div><span style="color: #6b7280;">الجنس:</span> {{ $patient->gender == 'male' ? 'ذكر' : 'أنثى' }}</div>
                <div><span style="color: #6b7280;">الهاتف:</span> {{ $patient->phone }}</div>
                @if($patient->patient_type == 'chronic_disease')
                <div><span style="color: #6b7280;">الأمراض:</span>
                    @if($patient->chronic_disease_type)
                        {{ $patient->chronic_disease_type }}
                    @endif
                    @if($patient->chronic_disease_type2)
                        @if($patient->chronic_disease_type) + @endif
                        {{ $patient->chronic_disease_type2 }}
                    @endif
                </div>
                @endif
            </div>
        </div>

        <!-- Appointments Stats -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
            <div style="background: white; padding: 1.5rem; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                <h3 style="color: #6b7280; font-size: 0.9rem; margin-bottom: 0.5rem;">مواعيد معلقة</h3>
                <p style="color: #f59e0b; font-size: 2rem; font-weight: bold;">{{ $stats['pending'] ?? 0 }}</p>
            </div>
            <div style="background: white; padding: 1.5rem; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                <h3 style="color: #6b7280; font-size: 0.9rem; margin-bottom: 0.5rem;">مواعيد مؤكدة</h3>
                <p style="color: #3b82f6; font-size: 2rem; font-weight: bold;">{{ $stats['confirmed'] ?? 0 }}</p>
            </div>
            <div style="background: white; padding: 1.5rem; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                <h3 style="color: #6b7280; font-size: 0.9rem; margin-bottom: 0.5rem;">مواعيد مكتملة</h3>
                <p style="color: #10b981; font-size: 2rem; font-weight: bold;">{{ $stats['completed'] ?? 0 }}</p>
            </div>
        </div>

        <!-- Recent Appointments -->
        <div style="background: white; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); padding: 1.5rem;">
            <h2 style="color: #1f2937; margin-bottom: 1rem;">مواعيدك الأخيرة</h2>
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="border-bottom: 2px solid #e5e7eb;">
                        <th style="padding: 0.75rem; text-align: right;">الطبيب</th>
                        <th style="padding: 0.75rem; text-align: right;">التخصص</th>
                        <th style="padding: 0.75rem; text-align: right;">التاريخ</th>
                        <th style="padding: 0.75rem; text-align: right;">الحالة</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($appointments as $appointment)
                    <tr style="border-bottom: 1px solid #e5e7eb;">
                        <td style="padding: 0.75rem;">د. {{ $appointment->doctor->user->name ?? '-' }}</td>
                        <td style="padding: 0.75rem;">{{ $appointment->doctor->specialty ?? '-' }}</td>
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
