@extends('layouts.app')

@section('title', 'السجلات الطبية - لوحة تحكم المريض')

@section('content')
    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <!-- Header with Basic Info -->
        <div class="bg-gradient-to-br from-gray-400 to-gray-500 rounded-lg shadow-lg p-6 mb-6 text-white">
            <h2 class="text-2xl font-bold mb-4">السجلات الطبية</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                <div><span class="text-gray-200">العمر:</span> {{ auth()->user()->patient->age ?? '-' }} سنة</div>
                <div><span class="text-gray-200">الجنس:</span> {{ auth()->user()->patient->gender == 'male' ? 'ذكر' : 'أنثى' }}</div>
                <div><span class="text-gray-200">الهاتف:</span> {{ auth()->user()->patient->phone ?? '-' }}</div>
                <div><span class="text-gray-200">فصيلة الدم:</span> {{ auth()->user()->patient->blood_type ?? 'غير محددة' }}</div>
            </div>
        </div>

        <!-- Current Health Status -->
        @if(auth()->user()->patient->patient_type == 'chronic_disease')
        <div class="bg-white rounded-lg shadow p-4 mb-6">
            <h3 class="font-bold text-gray-800 mb-2">حالتك الصحية:</h3>
            <p class="text-lg">
                @if(auth()->user()->patient->chronic_disease_type)
                    {{ auth()->user()->patient->chronic_disease_type }}
                @endif
                @if(auth()->user()->patient->chronic_disease_type2)
                    @if(auth()->user()->patient->chronic_disease_type) + @endif
                    {{ auth()->user()->patient->chronic_disease_type2 }}
                @endif
            </p>
        </div>
        @elseif(auth()->user()->patient->patient_type == 'child')
        <div class="bg-white rounded-lg shadow p-4 mb-6">
            <h3 class="font-bold text-gray-800 mb-2">حالتك:</h3>
            <p class="text-lg"> طفل (تطعيمات الأطفال)</p>
        </div>
        @elseif(auth()->user()->patient->patient_type == 'pregnant')
        <div class="bg-white rounded-lg shadow p-4 mb-6">
            <h3 class="font-bold text-gray-800 mb-2">حالتك:</h3>
            <p class="text-lg"> حامل (متابعة الحمل والولادة)</p>
        </div>
        @endif

        <!-- Medical Records History -->
        <div class="bg-white rounded-lg shadow">
            <div class="p-6">
                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1rem;">
                    <h2 class="text-xl font-bold text-gray-800" style="margin:0;">سجل زياراتك</h2>
                    <a href="{{ route('patient.medical-records.print') }}" target="_blank"
                       style="background:#3b82f6;color:white;border-radius:6px;padding:0.5rem 1rem;text-decoration:none;font-size:0.875rem;font-weight:600;">
                        🖨️ طباعة / تصدير PDF
                    </a>
                </div>
                <div class="space-y-4">
                    @foreach($records as $record)
                    <div class="border rounded-lg p-4">
                        <div class="flex justify-between items-start mb-3">
                            <div>
                                <p class="font-bold text-lg">د. {{ $record->doctor->user->name }}</p>
                                <p class="text-sm text-gray-500">{{ $record->doctor->specialty }}</p>
                            </div>
                            <div class="text-left">
                                <p class="text-sm text-gray-500">{{ $record->created_at->format('Y-m-d') }}</p>
                            </div>
                        </div>

                        <div class="bg-yellow-50 border border-yellow-200 rounded p-3 mb-3">
                            <p class="font-bold text-yellow-800">التشخيص:</p>
                            <p class="text-yellow-700">{{ $record->diagnosis }}</p>
                        </div>

                        @if($record->prescription)
                        <div class="bg-blue-50 border border-blue-200 rounded p-3 mb-3">
                            <p class="font-bold text-blue-800">الوصفة الطبية:</p>
                            <p class="text-blue-700 whitespace-pre-line">{{ $record->prescription }}</p>
                        </div>
                        @endif

                        @if($record->notes)
                        <div class="bg-gray-50 border rounded p-3">
                            <p class="font-bold text-gray-700">ملاحظات:</p>
                            <p class="text-gray-600">{{ $record->notes }}</p>
                        </div>
                        @endif
                    </div>
                    @endforeach

                    @if($records->isEmpty())
                    <div class="text-center py-12">
                        <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <p class="text-gray-500">لا توجد سجلات طبية سابقة</p>
                        <p class="text-gray-400 text-sm">قم بزيارة الطبيب للحصول على سجل طبي</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
