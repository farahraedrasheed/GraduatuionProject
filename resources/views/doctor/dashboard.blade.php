@extends('layouts.app')

@section('title', 'لوحة تحكم الطبيب - المركز الطبي')

@section('styles')
    @vite('resources/css/app.css')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endsection

@section('content')
    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <h1 style="color: #1f2937; margin-bottom: 0.5rem;">لوحة تحكم الطبيب</h1>
        <p style="color: #6b7280; margin-bottom: 0.25rem;">د. {{ $doctor->user->name }}</p>
        <p style="color: #6b7280; margin-bottom: 2rem;">التخصص: {{ $doctor->specialty ?? '-' }}</p>
        
        <!-- Stats Cards -->
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

        <!-- Quick Links -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-bottom: 2rem;">
            <a href="{{ route('doctor.appointments') }}" style="background: linear-gradient(135deg, #3b82f6, #1d4ed8); color: white; padding: 1rem; border-radius: 10px; text-align: center; text-decoration: none; font-weight: 600;">
                مواعيدي
            </a>
            <a href="{{ route('doctor.patients') }}" style="background: linear-gradient(135deg, #10b981, #059669); color: white; padding: 1rem; border-radius: 10px; text-align: center; text-decoration: none; font-weight: 600;">
                مرضاي
            </a>
            <a href="{{ route('doctor.schedule') }}" style="background: linear-gradient(135deg, #8b5cf6, #7c3aed); color: white; padding: 1rem; border-radius: 10px; text-align: center; text-decoration: none; font-weight: 600;">
                جدول المواعيد
            </a>
        </div>

        <!-- Today's Appointments -->
        <div style="background: white; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); padding: 1.5rem;">
            <h2 style="color: #1f2937; margin-bottom: 1rem;">مواعيد اليوم</h2>
            @if(count($today_appointments) > 0)
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="border-bottom: 2px solid #e5e7eb;">
                        <th style="padding: 0.75rem; text-align: right;">المريض</th>
                        <th style="padding: 0.75rem; text-align: right;">الوقت</th>
                        <th style="padding: 0.75rem; text-align: right;">الحالة</th>
                        <th style="padding: 0.75rem; text-align: right;">الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($today_appointments as $appointment)
                    <tr style="border-bottom: 1px solid #e5e7eb;">
                        <td style="padding: 0.75rem;">{{ $appointment->patient->user->name ?? '-' }}</td>
                        <td style="padding: 0.75rem;">{{ $appointment->appointment_date ? $appointment->appointment_date->format('H:i') : '-' }}</td>
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
                        <td style="padding: 0.75rem;">
                            <a href="{{ route('doctor.patients.show', $appointment->patient) }}" style="color: #3b82f6; text-decoration: none;">عرض</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <p style="text-align: center; color: #6b7280; padding: 1rem;">لا توجد مواعيد اليوم</p>
            @endif
        </div>
    </div>
@endsection
