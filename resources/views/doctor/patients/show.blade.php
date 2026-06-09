@extends('layouts.app')

@section('title', 'سجل المريض - المركز الطبي')

@section('content')
    <div style="max-width: 1200px; margin: 0 auto;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 0.5rem;">
            <h2 style="font-size: 1.25rem; font-weight: 700; color: #1f2937;">سجل المريض</h2>
            <div style="display: flex; gap: 0.5rem;">
                <a href="{{ route('medical-records.create-for-patient', $patient) }}" class="btn btn-primary" style="font-size: 0.85rem; padding: 0.5rem 1rem;">+ إضافة سجل طبي</a>
                <a href="{{ route('doctor.patients') }}" style="color: #6b7280; text-decoration: none;">← العودة</a>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1rem; margin-bottom: 1.5rem;">
            <div style="background: #fff; padding: 1.25rem; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                <p style="color: #6b7280; font-size: 0.85rem; margin-bottom: 0.25rem;">اسم المريض</p>
                <p style="font-size: 1.1rem; font-weight: 700; color: #1f2937;">{{ $patient->user->name }}</p>
            </div>
            <div style="background: #fff; padding: 1.25rem; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                <p style="color: #6b7280; font-size: 0.85rem; margin-bottom: 0.25rem;">العمر</p>
                <p style="font-size: 1.1rem; font-weight: 700; color: #1f2937;">{{ $patient->age }} سنة</p>
            </div>
            <div style="background: #fff; padding: 1.25rem; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                <p style="color: #6b7280; font-size: 0.85rem; margin-bottom: 0.25rem;">الجنس</p>
                <p style="font-size: 1.1rem; font-weight: 700; color: #1f2937;">{{ $patient->gender == 'male' ? 'ذكر' : 'أنثى' }}</p>
            </div>
            <div style="background: #fff; padding: 1.25rem; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                <p style="color: #6b7280; font-size: 0.85rem; margin-bottom: 0.25rem;">رقم الهاتف</p>
                <p style="font-size: 1.1rem; font-weight: 700; color: #1f2937;">{{ $patient->phone ?? '-' }}</p>
            </div>
            <div style="background: #fff; padding: 1.25rem; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                <p style="color: #6b7280; font-size: 0.85rem; margin-bottom: 0.25rem;">العمر</p>
                <p style="font-size: 1.1rem; font-weight: 700; color: #1f2937;">{{ $patient->age }} سنة</p>
            </div>
            <div style="background: #fff; padding: 1.25rem; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                <p style="color: #6b7280; font-size: 0.85rem; margin-bottom: 0.25rem;">نوع المرض / الفئة</p>
                <p style="font-size: 1.1rem; font-weight: 700; color: #1f2937;">
                    @if($patient->patient_type == 'child')
                        طفل (طب الأطفال)
                    @elseif($patient->patient_type == 'pregnant')
                        حامل (نساء وتوليد)
                    @elseif($patient->patient_type == 'chronic_disease')
                        @if($patient->chronic_disease_type)
                            @if($patient->chronic_disease_type == 'الكبد الوبائي')
                                الكبد الوبائي (الباطنة)
                            @else
                                {{ $patient->chronic_disease_type }}
                            @endif
                        @endif
                        @if($patient->chronic_disease_type2)
                            @if($patient->chronic_disease_type) + @endif
                            @if($patient->chronic_disease_type2 == 'الكبد الوبائي')
                                الكبد الوبائي (الباطنة)
                            @else
                                {{ $patient->chronic_disease_type2 }}
                            @endif
                        @endif
                        @if(!$patient->chronic_disease_type && !$patient->chronic_disease_type2)
                            مريض مزمن
                        @endif
                    @elseif($patient->patient_type == 'other')
                        أخرى
                    @else
                        -
                    @endif
                </p>
            </div>
        </div>

        @if($lastAppointment)
        <div style="background: #eff6ff; border-radius: 8px; padding: 1.5rem; margin-bottom: 1.5rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
            <p style="color: #6b7280; font-size: 0.85rem; margin-bottom: 0.5rem;">آخر موعد</p>
            <p style="font-weight: 700;">د. {{ $lastAppointment->doctor->user->name }}</p>
            <p style="font-size: 0.9rem; color: #6b7280;">{{ $lastAppointment->disease_type ?? '-' }}</p>
            <p style="font-size: 0.9rem; color: #6b7280;">{{ $lastAppointment->medicine_name ?? 'بدون دواء' }}</p>
            <p style="font-size: 0.9rem; color: #6b7280;">{{ $lastAppointment->doctor_notes ?? 'لا توجد ملاحظات' }}</p>
        </div>
        @endif

        {{-- التقارير الطبية --}}
        <div style="background: #fff; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); padding: 1.5rem; margin-bottom: 1.5rem;">
            <h3 style="font-size: 1.1rem; font-weight: 700; color: #1f2937; margin-bottom: 1rem;">التقارير الطبية</h3>
            @if($reports->isEmpty())
                <p style="color: #6b7280;">لا توجد تقارير طبية مرفوعة</p>
            @else
                <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                    @foreach($reports as $report)
                    <div style="display: flex; justify-content: space-between; align-items: center; border: 1px solid #e5e7eb; border-radius: 8px; padding: 0.75rem 1rem;">
                        <div>
                            <p style="font-weight: 600; color: #1f2937;">{{ $report->original_name }}</p>
                            @if($report->description)
                                <p style="font-size: 0.85rem; color: #6b7280;">{{ $report->description }}</p>
                            @endif
                            <p style="font-size: 0.8rem; color: #9ca3af;">{{ $report->created_at->format('Y-m-d') }}</p>
                        </div>
                        <a href="{{ route('doctor.patients.report.download', $report) }}" class="btn btn-sm btn-primary" style="font-size: 0.8rem; padding: 0.35rem 0.75rem; text-decoration: none;">تحميل</a>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>

        <div style="background: #fff; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); padding: 1.5rem;">
            <h3 style="font-size: 1.1rem; font-weight: 700; color: #1f2937; margin-bottom: 1rem;">سجل المواعيد</h3>
            @if($appointments->isEmpty())
                <p style="color: #6b7280;">لا توجد مواعيد</p>
            @else
                <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                    @foreach($appointments as $appointment)
                    <div style="border: 1px solid #e5e7eb; border-radius: 8px; padding: 1rem;">
                        <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 0.5rem;">
                            <div>
                                <span style="font-weight: 700;">د. {{ $appointment->doctor->user->name }}</span>
                                <span style="font-size: 0.85rem; color: #6b7280; margin-right: 0.5rem;">{{ $appointment->created_at->format('Y-m-d') }}</span>
                            </div>
                            <span class="badge
                                @if($appointment->status == 'pending') badge-pending
                                @elseif($appointment->status == 'confirmed') badge-confirmed
                                @elseif($appointment->status == 'completed') badge-completed
                                @else badge-cancelled @endif">
                                @if($appointment->status == 'pending') معلق
                                @elseif($appointment->status == 'confirmed') مؤكد
                                @elseif($appointment->status == 'completed') مكتمل
                                @else ملغي @endif
                            </span>
                        </div>
                        <p style="font-size: 0.9rem;"><strong>المرض:</strong> {{ $appointment->disease_type ?? '-' }}</p>
                        <p style="font-size: 0.9rem;"><strong>الوصف:</strong> {{ $appointment->patient_description ?? '-' }}</p>
                        <p style="font-size: 0.9rem;"><strong>الدواء:</strong> {{ $appointment->medicine_name ?? '-' }}</p>
                        <p style="font-size: 0.9rem;"><strong>ملاحظات الطبيب:</strong> {{ $appointment->doctor_notes ?? '-' }}</p>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
@endsection
