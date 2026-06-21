@extends('layouts.app')

@section('title', 'مواعيدي - لوحة تحكم المريض')

@section('content')
    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-lg shadow">
            <div class="p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-6">مواعيدي</h2>

                @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-4">
                    {{ session('success') }}
                </div>
                @endif

                <table class="w-full">
                    <thead>
                        <tr class="text-right text-gray-500 border-b">
                            <th class="py-3 px-2">الطبيب</th>
                            <th class="py-3 px-2">نوع الزيارة</th>
                            <th class="py-3 px-2">الوصف</th>
                            <th class="py-3 px-2">اسم الدواء</th>
                            <th class="py-3 px-2">ملاحظة الطبيب</th>
                            <th class="py-3 px-2">التشخيص</th>
                            <th class="py-3 px-2">الوصفة</th>
                            <th class="py-3 px-2">تاريخ الطلب</th>
                            <th class="py-3 px-2">تاريخ التأكيد</th>
                            <th class="py-3 px-2">الحالة</th>
                            <th class="py-3 px-2">إجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($appointments as $appointment)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="py-3 px-2">د. {{ $appointment->doctor->user->name }}</td>
                            <td class="py-3 px-2 text-sm">{{ $appointment->disease_type ?? '-' }}</td>
                            <td class="py-3 px-2 text-sm">{{ Str::limit($appointment->patient_description, 30) ?? '-' }}</td>
                            <td class="py-3 px-2 text-sm">{{ $appointment->medicine_name ?? '-' }}</td>
                            <td class="py-3 px-2 text-sm">
                                @if($appointment->medicalRecord)
                                    {{ Str::limit($appointment->medicalRecord->notes, 30) }}
                                @else
                                    {{ Str::limit($appointment->doctor_notes, 30) ?? '-' }}
                                @endif
                            </td>
                            <td class="py-3 px-2 text-sm">
                                @if($appointment->medicalRecord)
                                    {{ Str::limit($appointment->medicalRecord->diagnosis, 30) }}
                                @else
                                    -
                                @endif
                            </td>
                            <td class="py-3 px-2 text-sm">
                                @if($appointment->medicalRecord)
                                    {{ Str::limit($appointment->medicalRecord->prescription, 30) }}
                                @else
                                    -
                                @endif
                            </td>
                            <td class="py-3 px-2 text-sm">
                                {{ $appointment->requested_date ? $appointment->requested_date->format('Y-m-d') : '-' }}
                            </td>
                            <td class="py-3 px-2">
                                @if($appointment->appointment_date)
                                    {{ $appointment->appointment_date->format('Y-m-d H:i') }}
                                @else
                                    <span class="text-yellow-600">بانتظار التأكيد</span>
                                @endif
                            </td>
                            <td class="py-3 px-2">
                                <span class="px-2 py-1 rounded-full text-xs font-medium 
                                    @if($appointment->status == 'pending') bg-yellow-100 text-yellow-800
                                    @elseif($appointment->status == 'confirmed') bg-blue-100 text-blue-800
                                    @elseif($appointment->status == 'completed') bg-green-100 text-green-800
                                    @else bg-red-100 text-red-800 @endif">
                                    @if($appointment->status == 'pending') بانتظار الموافقة
                                    @elseif($appointment->status == 'confirmed') تم التأكيد
                                    @elseif($appointment->status == 'completed') مكتمل
                                    @else ملغي @endif
                                </span>
                            </td>
                            <td class="py-3 px-2">
                                @if($appointment->status == 'pending')
                                <div class="flex gap-2">
                                    <form method="POST" action="{{ route('patient.appointments.accept', $appointment) }}" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="bg-green-600 text-white px-3 py-1 rounded text-sm hover:bg-green-700">قبول</button>
                                    </form>
                                    <form method="POST" action="{{ route('patient.appointments.decline', $appointment) }}" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="bg-red-600 text-white px-3 py-1 rounded text-sm hover:bg-red-700">رفض</button>
                                    </form>
                                </div>
                                @elseif($appointment->status == 'confirmed')
                                    <form method="POST" action="{{ route('patient.appointments.cancel-confirmed', $appointment) }}" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="bg-red-600 text-white px-3 py-1 rounded text-sm hover:bg-red-700">إلغاء الموعد</button>
                                    </form>
                                @else
                                    <span class="text-gray-400 text-sm">لا توجد إجراءات</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                @if($appointments->isEmpty())
                <div class="text-center py-12 text-gray-500">
                    <p>لا توجد مواعيد</p>
                </div>
                @endif
            </div>
        </div>
    </div>
@endsection
