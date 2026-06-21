@extends('layouts.app')

@section('title', 'تعديل طبيب - المركز الطبي')

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
                <h2 class="text-xl font-bold text-gray-800 mb-6">تعديل بيانات الطبيب</h2>

                <form method="POST" action="{{ route('doctors.update', $doctor) }}">
                    @csrf
                    @method('PATCH')

                    <div class="grid grid-cols-1 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">الاسم</label>
                            <input type="text" name="name" value="{{ $doctor->user->name }}" required class="w-full border rounded-lg p-2">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">التخصص</label>
                            <select name="specialty" required class="w-full border rounded-lg p-2">
                                <option value="طب عام" {{ $doctor->specialty == 'طب عام' ? 'selected' : '' }}>طب عام</option>
                                <option value="أخصائي قلب" {{ $doctor->specialty == 'أخصائي قلب' ? 'selected' : '' }}>أخصائي قلب</option>
                                <option value="باطنة" {{ $doctor->specialty == 'باطنة' ? 'selected' : '' }}>باطنة</option>
                                <option value="أطفال" {{ $doctor->specialty == 'أطفال' ? 'selected' : '' }}>أطفال</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">رقم الترخيص</label>
                            <input type="text" name="license_number" value="{{ $doctor->license_number }}" required class="w-full border rounded-lg p-2">
                        </div>

                        <div>
                            <label class="flex items-center">
                                <input type="checkbox" name="is_active" value="1" {{ $doctor->is_active ? 'checked' : '' }} class="ml-2">
                                <span class="text-sm font-medium text-gray-700">نشط</span>
                            </label>
                        </div>
                    </div>

                    <div class="flex justify-end mt-6 space-x-4">
                        <a href="{{ route('doctors.index') }}" class="px-4 py-2 border rounded-lg text-gray-700 hover:bg-gray-50">إلغاء</a>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">حفظ</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection