@extends('layouts.app')

@section('title', 'مرضاي - لوحة تحكم الطبيب')

@section('content')
    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-bold text-gray-800">قائمة مرضاي</h2>
            <a href="{{ route('doctor.dashboard') }}" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600" style="text-decoration: none;">
                ← العودة
            </a>
        </div>

        <!-- Search Form -->
        <div class="bg-white rounded-lg shadow p-4 mb-6">
            <form method="GET" class="flex gap-4 items-end">
                <div class="flex-1">
                    <label class="block text-sm font-medium text-gray-700 mb-1">البحث</label>
                    <input type="text" name="search" value="{{ request('search') }}" 
                           placeholder="البحث باسم المريض أو رقم الهاتف..." 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">
                    بحث
                </button>
                @if(request('search'))
                <a href="{{ route('doctor.patients') }}" class="bg-gray-500 text-white px-6 py-2 rounded-lg hover:bg-gray-600" style="text-decoration: none;">
                    إلغاء
                </a>
                @endif
            </form>
        </div>

        @if($patients->isEmpty())
        <div class="bg-white rounded-lg shadow p-8 text-center text-gray-500">
            لا يوجد لديك مرضى بعد
        </div>
        @else
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <table class="w-full">
                <thead class="bg-gray-50 border-b">
                    <tr>
                        <th class="py-3 px-4 text-right">اسم المريض</th>
                        <th class="py-3 px-4 text-right">العمر</th>
                        <th class="py-3 px-4 text-right">الجنس</th>
                        <th class="py-3 px-4 text-right">رقم الهاتف</th>
                        <th class="py-3 px-4 text-right">الفئة / الأمراض</th>
                        <th class="py-3 px-4 text-right">إجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($patients as $patient)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="py-3 px-4">{{ $patient->user->name }}</td>
                        <td class="py-3 px-4">{{ $patient->age ?? '-' }}</td>
                        <td class="py-3 px-4">{{ $patient->gender == 'male' ? 'ذكر' : 'أنثى' }}</td>
                        <td class="py-3 px-4">{{ $patient->phone ?? '-' }}</td>
                        <td class="py-3 px-4">
                            @if($patient->patient_type == 'child')
                                طفل
                            @elseif($patient->patient_type == 'pregnant')
                                حامل
                            @elseif($patient->patient_type == 'chronic_disease')
                                @if($patient->chronic_disease_type)
                                    {{ $patient->chronic_disease_type }}
                                @endif
                                @if($patient->chronic_disease_type2)
                                    @if($patient->chronic_disease_type) + @endif
                                    {{ $patient->chronic_disease_type2 }}
                                @endif
                            @elseif($patient->patient_type == 'other')
                                أخرى
                            @endif
                        </td>
                        <td class="py-3 px-4">
                            <a href="{{ route('doctor.patients.show', $patient->id) }}" class="text-blue-600 hover:text-blue-800 text-sm" style="text-decoration: none;">
                                عرض السجل
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>
@endsection
