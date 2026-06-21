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
        
        <!-- Stats Cards Row 1 -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.25rem; margin-bottom: 1.25rem;">
            <div style="background: white; padding: 1.25rem; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); border-right: 4px solid #3b82f6;">
                <h3 style="color: #6b7280; font-size: 0.85rem; margin-bottom: 0.5rem;">إجمالي الأطباء</h3>
                <p style="color: #3b82f6; font-size: 2rem; font-weight: bold; margin: 0;">{{ $stats['doctors'] }}</p>
            </div>
            <div style="background: white; padding: 1.25rem; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); border-right: 4px solid #10b981;">
                <h3 style="color: #6b7280; font-size: 0.85rem; margin-bottom: 0.5rem;">إجمالي المرضى</h3>
                <p style="color: #10b981; font-size: 2rem; font-weight: bold; margin: 0;">{{ $stats['patients'] }}</p>
            </div>
            <div style="background: white; padding: 1.25rem; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); border-right: 4px solid #f59e0b;">
                <h3 style="color: #6b7280; font-size: 0.85rem; margin-bottom: 0.5rem;">مواعيد اليوم</h3>
                <p style="color: #f59e0b; font-size: 2rem; font-weight: bold; margin: 0;">{{ $stats['today_appointments'] }}</p>
            </div>
            <div style="background: white; padding: 1.25rem; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); border-right: 4px solid #8b5cf6;">
                <h3 style="color: #6b7280; font-size: 0.85rem; margin-bottom: 0.5rem;">إجمالي المواعيد</h3>
                <p style="color: #8b5cf6; font-size: 2rem; font-weight: bold; margin: 0;">{{ $stats['total_appointments'] }}</p>
            </div>
        </div>

        <!-- Stats Cards Row 2 -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 1.25rem; margin-bottom: 2rem;">
            <div style="background: white; padding: 1rem; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); text-align: center;">
                <p style="color: #6b7280; font-size: 0.8rem; margin-bottom: 0.25rem;">معلقة</p>
                <p style="color: #f59e0b; font-size: 1.5rem; font-weight: bold; margin: 0;">{{ $stats['pending_appointments'] }}</p>
            </div>
            <div style="background: white; padding: 1rem; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); text-align: center;">
                <p style="color: #6b7280; font-size: 0.8rem; margin-bottom: 0.25rem;">مؤكدة</p>
                <p style="color: #3b82f6; font-size: 1.5rem; font-weight: bold; margin: 0;">{{ $stats['confirmed_appointments'] }}</p>
            </div>
            <div style="background: white; padding: 1rem; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); text-align: center;">
                <p style="color: #6b7280; font-size: 0.8rem; margin-bottom: 0.25rem;">مكتملة</p>
                <p style="color: #10b981; font-size: 1.5rem; font-weight: bold; margin: 0;">{{ $stats['completed_appointments'] }}</p>
            </div>
            <div style="background: white; padding: 1rem; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); text-align: center;">
                <p style="color: #6b7280; font-size: 0.8rem; margin-bottom: 0.25rem;">ملغاة</p>
                <p style="color: #ef4444; font-size: 1.5rem; font-weight: bold; margin: 0;">{{ $stats['cancelled_appointments'] }}</p>
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

        <!-- Charts Row -->
        <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 1.5rem; margin-bottom: 2rem;">
            <!-- Monthly Chart -->
            <div style="background: white; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); padding: 1.5rem;">
                <h2 style="color: #1f2937; margin-bottom: 1rem; font-size: 1rem;">المواعيد الشهرية ({{ now()->year }})</h2>
                <canvas id="monthlyChart" height="100"></canvas>
            </div>
            <!-- Patient Types Pie Chart -->
            <div style="background: white; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); padding: 1.5rem;">
                <h2 style="color: #1f2937; margin-bottom: 1rem; font-size: 1rem;">توزيع فئات المرضى</h2>
                <canvas id="patientTypeChart" height="160"></canvas>
            </div>
        </div>

        <!-- Top Doctors -->
        <div style="background: white; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); padding: 1.5rem; margin-bottom: 2rem;">
            <h2 style="color: #1f2937; margin-bottom: 1rem; font-size: 1rem;">أكثر الأطباء نشاطاً هذا الشهر</h2>
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="border-bottom: 2px solid #e5e7eb; text-align: right;">
                        <th style="padding: 0.5rem;">#</th>
                        <th style="padding: 0.5rem;">الطبيب</th>
                        <th style="padding: 0.5rem;">التخصص</th>
                        <th style="padding: 0.5rem;">المواعيد هذا الشهر</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($topDoctors as $i => $doctor)
                    <tr style="border-bottom: 1px solid #f3f4f6;">
                        <td style="padding: 0.5rem; color: #9ca3af;">{{ $i + 1 }}</td>
                        <td style="padding: 0.5rem; font-weight: 600;">د. {{ $doctor->user->name }}</td>
                        <td style="padding: 0.5rem; color: #6b7280;">{{ $doctor->specialty }}</td>
                        <td style="padding: 0.5rem;">
                            <span style="background: #dbeafe; color: #1e40af; padding: 0.2rem 0.6rem; border-radius: 9999px; font-size: 0.875rem; font-weight: 700;">
                                {{ $doctor->month_appointments }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="4" style="padding: 1rem; text-align: center; color: #9ca3af;">لا توجد بيانات</td></tr>
                    @endforelse
                </tbody>
            </table>
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
                    @forelse($recentAppointments as $appointment)
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

<script>
// Monthly Appointments Chart
new Chart(document.getElementById('monthlyChart'), {
    type: 'bar',
    data: {
        labels: ['يناير','فبراير','مارس','أبريل','مايو','يونيو','يوليو','أغسطس','سبتمبر','أكتوبر','نوفمبر','ديسمبر'],
        datasets: [{
            label: 'المواعيد',
            data: @json($monthlyData),
            backgroundColor: 'rgba(59,130,246,0.7)',
            borderColor: '#3b82f6',
            borderWidth: 1,
            borderRadius: 4,
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } }
    }
});

// Patient Types Pie Chart
new Chart(document.getElementById('patientTypeChart'), {
    type: 'doughnut',
    data: {
        labels: @json($patientTypeNames),
        datasets: [{
            data: @json($patientTypeData),
            backgroundColor: ['#3b82f6','#ec4899','#10b981','#f59e0b'],
            borderWidth: 2,
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { position: 'bottom', labels: { font: { family: 'Cairo' }, padding: 8 } }
        }
    }
});
</script>
@endsection
