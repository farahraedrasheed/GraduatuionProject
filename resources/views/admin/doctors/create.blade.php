@extends('layouts.app')

@section('title', 'إضافة طبيب جديد - المركز الطبي')

@section('styles')
    @vite('resources/css/app.css')
@endsection

@section('content')
    <div class="max-w-3xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-lg shadow">
            <div class="p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-6">إضافة طبيب جديد</h2>

                <form method="POST" action="{{ route('doctors.store') }}">
                    @csrf

                    <div class="grid grid-cols-1 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">الاسم</label>
                            <input type="text" name="name" required class="w-full border rounded-lg p-2">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">البريد الإلكتروني</label>
                            <input type="email" name="email" required class="w-full border rounded-lg p-2">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">كلمة المرور</label>
                            <input type="password" name="password" required class="w-full border rounded-lg p-2">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">التخصص</label>
                            <select name="specialty" required class="w-full border rounded-lg p-2">
                                <option value="">اختر التخصص</option>
                                <option value="طب عام">طب عام</option>
                                <option value="أخصائي قلب">أخصائي قلب</option>
                                <option value="باطنة">باطنة</option>
                                <option value="أطفال">أطفال</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">رقم الترخيص</label>
                            <input type="text" name="license_number" required class="w-full border rounded-lg p-2">
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