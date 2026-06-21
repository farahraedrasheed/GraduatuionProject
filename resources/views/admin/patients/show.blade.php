@extends('layouts.app')

@section('title', 'تفاصيل المريض - المركز الطبي')

@section('styles')
    @vite('resources/css/app.css')
    <style>
        body { font-family: 'Cairo', sans-serif; }
    </style>
@endsection

@section('content')
    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-white rounded-lg shadow">
                <div class="p-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-6">المعلومات الشخصية</h2>
                    
                    <div class="space-y-4">
                        <div class="flex justify-between border-b pb-3">
                            <span class="text-gray-500">الاسم:</span>
                            <span class="font-bold">{{ $patient->user->name }}</span>
                        </div>
                        <div class="flex justify-between border-b pb-3">
                            <span class="text-gray-500">البريد الإلكتروني:</span>
                            <span>{{ $patient->user->email }}</span>
                        </div>
                        <div class="flex justify-between border-b pb-3">
                            <span class="text-gray-500">رقم الهاتف:</span>
                            <span>{{ $patient->phone }}</span>
                        </div>
                        <div class="flex justify-between border-b pb-3">
                            <span class="text-gray-500">تاريخ الميلاد:</span>
                            <span>{{ $patient->date_of_birth }}</span>
                        </div>
                        <div class="flex justify-between border-b pb-3">
                            <span class="text-gray-500">الجنس:</span>
                            <span>{{ $patient->gender == 'male' ? 'ذكر' : 'أنثى' }}</span>
                        </div>
                        <div class="flex justify-between border-b pb-3">
                            <span class="text-gray-500">فصيلة الدم:</span>
                            <span class="px-2 py-1 bg-red-100 text-red-800 rounded">{{ $patient->blood_type ?? 'غير محدد' }}</span>
                        </div>
                        @if($patient->address)
                        <div class="flex justify-between border-b pb-3">
                            <span class="text-gray-500">العنوان:</span>
                            <span>{{ $patient->address }}</span>
                        </div>
                        @endif
                    </div>
                    
                    <div class="flex justify-start mt-6 space-x-4">
                        <a href="{{ route('patients.edit', $patient) }}" class="px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600">تعديل</a>
                        <form method="POST" action="{{ route('patients.destroy', $patient) }}" style="display: inline;" onsubmit="return confirm('هل أنت متأكد من حذف هذا المريض؟')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">حذف</button>
                        </form>
                        <a href="{{ route('patients.index') }}" class="px-4 py-2 border rounded-lg text-gray-700 hover:bg-gray-50">رجوع</a>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow">
                <div class="p-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-6">سجل المواعيد</h2>
                    
                    @if($patient->appointments->count() > 0)
                    <div class="space-y-3">
                        @foreach($patient->appointments as $appointment)
                        <div class="border rounded-lg p-4">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="font-bold">د. {{ $appointment->doctor->user->name }}</p>
                                    <p class="text-sm text-gray-500">{{ $appointment->doctor->specialty }}</p>
                                </div>
                                <span class="px-2 py-1 rounded text-xs 
                                    @if($appointment->status == 'pending') bg-yellow-100 text-yellow-800
                                    @elseif($appointment->status == 'confirmed') bg-blue-100 text-blue-800
                                    @else bg-green-100 text-green-800 @endif">
                                    @if($appointment->status == 'pending') معلق
                                    @elseif($appointment->status == 'confirmed') مؤكد
                                    @elseif($appointment->status == 'completed') مكتمل
                                    @else ملغي @endif
                                </span>
                            </div>
                            <p class="text-sm text-gray-600 mt-2">{{ $appointment->appointment_date ? $appointment->appointment_date->format('Y-m-d H:i') : '-' }}</p>
                            @if($appointment->notes)
                            <p class="text-sm text-gray-500 mt-1">ملاحظات: {{ $appointment->notes }}</p>
                            @endif
                        </div>
                        @endforeach
                    </div>
                    @else
                    <p class="text-gray-500 text-center py-8">لا توجد مواعيد سابقة</p>
                    @endif
                </div>
            </div>
            
            @if($patient->medical_history)
            <div class="bg-white rounded-lg shadow md:col-span-2">
                <div class="p-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-4">السيرة الطبية</h2>
                    <p class="text-gray-700">{{ $patient->medical_history }}</p>
                </div>
            </div>
            @endif
        </div>
    </div>
@endsection
