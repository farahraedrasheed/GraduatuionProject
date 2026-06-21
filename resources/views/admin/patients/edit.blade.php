@extends('layouts.app')

@section('title', 'تعديل بيانات المريض - المركز الطبي')

@section('styles')
    @vite('resources/css/app.css')
    <style>
        body { font-family: 'Cairo', sans-serif; }
    </style>
@endsection

@section('content')
    <div class="max-w-3xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-lg shadow">
            <div class="p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-6">تعديل بيانات المريض</h2>
                
                <form method="POST" action="{{ route('patients.update', $patient) }}">
                    @csrf
                    @method('PATCH')
                    
                    <div class="grid grid-cols-1 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">الاسم</label>
                            <input type="text" name="name" value="{{ $patient->user->name }}" required class="w-full border rounded-lg p-3">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">البريد الإلكتروني</label>
                            <input type="email" name="email" value="{{ $patient->user->email }}" required class="w-full border rounded-lg p-3">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">رقم الهاتف</label>
                            <input type="text" name="phone" value="{{ $patient->phone }}" required class="w-full border rounded-lg p-3">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">تاريخ الميلاد</label>
                            <input type="date" name="date_of_birth" value="{{ $patient->date_of_birth ? $patient->date_of_birth->format('Y-m-d') : '' }}" required class="w-full border rounded-lg p-3">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">الجنس</label>
                            <select name="gender" required class="w-full border rounded-lg p-3">
                                <option value="male" {{ $patient->gender == 'male' ? 'selected' : '' }}>ذكر</option>
                                <option value="female" {{ $patient->gender == 'female' ? 'selected' : '' }}>أنثى</option>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">فصيلة الدم</label>
                            <select name="blood_type" class="w-full border rounded-lg p-3">
                                <option value="">غير محدد</option>
                                <option value="A+" {{ $patient->blood_type == 'A+' ? 'selected' : '' }}>A+</option>
                                <option value="A-" {{ $patient->blood_type == 'A-' ? 'selected' : '' }}>A-</option>
                                <option value="B+" {{ $patient->blood_type == 'B+' ? 'selected' : '' }}>B+</option>
                                <option value="B-" {{ $patient->blood_type == 'B-' ? 'selected' : '' }}>B-</option>
                                <option value="O+" {{ $patient->blood_type == 'O+' ? 'selected' : '' }}>O+</option>
                                <option value="O-" {{ $patient->blood_type == 'O-' ? 'selected' : '' }}>O-</option>
                                <option value="AB+" {{ $patient->blood_type == 'AB+' ? 'selected' : '' }}>AB+</option>
                                <option value="AB-" {{ $patient->blood_type == 'AB-' ? 'selected' : '' }}>AB-</option>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">العنوان</label>
                            <input type="text" name="address" value="{{ $patient->address }}" class="w-full border rounded-lg p-3">
                        </div>
                        
                        <div>
                            <label class="flex items-center">
                                <input type="checkbox" name="is_active" value="1" {{ $patient->is_active ? 'checked' : '' }} class="ml-2">
                                <span class="text-sm font-medium text-gray-700">نشط</span>
                            </label>
                        </div>
                    </div>
                    
                    <div class="flex justify-end mt-6 space-x-4">
                        <a href="{{ route('patients.index') }}" class="px-4 py-2 border rounded-lg text-gray-700 hover:bg-gray-50">إلغاء</a>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">حفظ</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
