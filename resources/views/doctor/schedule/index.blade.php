@extends('layouts.app')

@section('title', 'مواعيد العمل - المركز الطبي')

@section('styles')
    @vite('resources/css/app.css')
@endsection

@section('content')
    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="md:col-span-1">
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-lg font-bold text-gray-800 mb-4">إضافة موعد عمل</h2>
                    <form method="POST" action="{{ route('doctor.schedule.store') }}">
                        @csrf
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">اليوم</label>
                                <select name="day" required class="w-full border rounded-lg p-3">
                                    <option value="">اختر اليوم</option>
                                    <option value="saturday">السبت</option>
                                    <option value="sunday">الأحد</option>
                                    <option value="monday">الإثنين</option>
                                    <option value="tuesday">الثلاثاء</option>
                                    <option value="wednesday">الأربعاء</option>
                                    <option value="thursday">الخميس</option>
                                    <option value="friday">الجمعة</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">وقت البداية</label>
                                <input type="time" name="start_time" required class="w-full border rounded-lg p-3">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">وقت النهاية</label>
                                <input type="time" name="end_time" required class="w-full border rounded-lg p-3">
                            </div>
                            <button type="submit" class="w-full bg-blue-600 text-white py-3 rounded-lg hover:bg-blue-700">إضافة</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="md:col-span-2">
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-lg font-bold text-gray-800 mb-4">مواعيد عملي</h2>
                    @if($schedules->count() > 0)
                    <div class="space-y-3">
                        @foreach($schedules as $schedule)
                        <div class="flex items-center justify-between border rounded-lg p-4">
                            <div class="flex items-center space-x-4">
                                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                                    <span class="text-blue-600 font-bold">{{ substr($schedule->day, 0, 2) }}</span>
                                </div>
                                <div>
                                    <p class="font-bold">{{ \App\Models\DoctorSchedule::getDaysInArabic()[$schedule->day] }}</p>
                                    <p class="text-sm text-gray-500">{{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}</p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-2">
                                <form method="POST" action="{{ route('doctor.schedule.update', $schedule) }}" class="flex items-center">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="is_active" value="{{ $schedule->is_active ? '0' : '1' }}">
                                    <button type="submit" class="px-3 py-1 rounded text-sm {{ $schedule->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-600' }}">
                                        {{ $schedule->is_active ? 'نشط' : 'غير نشط' }}
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('doctor.schedule.destroy', $schedule) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800">حذف</button>
                                </form>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <p class="text-gray-500 text-center py-8">لم تقم بإضافة مواعيد عمل بعد</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection