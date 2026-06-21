@extends('layouts.app')

@section('title', 'تفاصيل الطبيب - المركز الطبي')

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
                            <span class="font-bold">{{ $doctor->user->name }}</span>
                        </div>
                        <div class="flex justify-between border-b pb-3">
                            <span class="text-gray-500">البريد الإلكتروني:</span>
                            <span>{{ $doctor->user->email }}</span>
                        </div>
                        <div class="flex justify-between border-b pb-3">
                            <span class="text-gray-500">التخصص:</span>
                            <span>{{ $doctor->specialty }}</span>
                        </div>
                        <div class="flex justify-between border-b pb-3">
                            <span class="text-gray-500">رقم الترخيص:</span>
                            <span class="font-mono">{{ $doctor->license_number }}</span>
                        </div>
                        <div class="flex justify-between border-b pb-3">
                            <span class="text-gray-500">الحالة:</span>
                            <span class="px-2 py-1 rounded text-xs {{ $doctor->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-600' }}">
                                {{ $doctor->is_active ? 'نشط' : 'غير نشط' }}
                            </span>
                        </div>
                    </div>
                    
                    <div class="flex justify-start mt-6 space-x-4">
                        <a href="{{ route('doctors.edit', $doctor) }}" class="px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600">تعديل</a>
                        <form method="POST" action="{{ route('doctors.destroy', $doctor) }}" style="display: inline;" onsubmit="return confirm('هل أنت متأكد من حذف هذا الطبيب؟')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">حذف</button>
                        </form>
                        <a href="{{ route('doctors.index') }}" class="px-4 py-2 border rounded-lg text-gray-700 hover:bg-gray-50">رجوع</a>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow">
                <div class="p-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-6">المرضى المسجلين</h2>
                    
                    @if($doctor->patients->count() > 0)
                    <div class="space-y-3">
                        @foreach($doctor->patients as $patient)
                        <div class="border rounded-lg p-4">
                            <div class="flex justify-between items-center">
                                <div>
                                    <p class="font-bold">{{ $patient->user->name }}</p>
                                    <p class="text-sm text-gray-500">{{ $patient->phone }}</p>
                                </div>
                                <a href="{{ route('patients.show', $patient) }}" class="text-blue-600 hover:text-blue-800">عرض</a>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <p class="text-gray-500 text-center py-8">لا يوجد مرضى مسجلين</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
