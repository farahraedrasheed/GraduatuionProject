@extends('layouts.app')

@section('title', 'مواعيدي - لوحة تحكم الطبيب')

@section('content')
    <div style="max-width: 1200px; margin: 0 auto;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
            <h2 style="font-size: 1.25rem; font-weight: 700; color: #1f2937;">إدارة المواعيد</h2>
            <a href="{{ route('doctor.appointments.create') }}" class="btn btn-primary" style="text-decoration: none;">
                + حجز موعد جديد
            </a>
        </div>

        <div style="background: #fff; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); padding: 1rem; margin-bottom: 1.5rem;">
            <form method="GET" style="display: flex; gap: 1rem; align-items: end;">
                <div style="flex: 1;">
                    <label style="display: block; font-size: 0.9rem; font-weight: 600; color: #374151; margin-bottom: 0.25rem;">البحث عن مريض</label>
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="اسم المريض..." style="width: 100%; border: 1px solid #d1d5db; border-radius: 0.5rem; padding: 0.5rem;">
                </div>
                <div>
                    <label style="display: block; font-size: 0.9rem; font-weight: 600; color: #374151; margin-bottom: 0.25rem;">الحالة</label>
                    <select name="status" style="border: 1px solid #d1d5db; border-radius: 0.5rem; padding: 0.5rem;">
                        <option value="">كل الحالات</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>معلق</option>
                        <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>مؤكد</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>مكتمل</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>ملغي</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">بحث</button>
                @if(request('search') || request('status'))
                <a href="{{ route('doctor.appointments') }}" class="btn btn-ghost" style="text-decoration: none;">
                    إلغاء
                </a>
                @endif
            </form>
        </div>

        @if(session('success'))
        <div style="background-color: #d1fae5; border: 1px solid #6ee7b7; color: #065f46; padding: 0.75rem 1rem; border-radius: 0.5rem; margin-bottom: 1rem;">
            {{ session('success') }}
        </div>
        @endif

        @if(session('error'))
        <div style="background-color: #fee2e2; border: 1px solid #fca5a5; color: #991b1b; padding: 0.75rem 1rem; border-radius: 0.5rem; margin-bottom: 1rem;">
            {{ session('error') }}
        </div>
        @endif

        <div style="background: #fff; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); overflow: hidden;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead style="background: #f9fafb; border-bottom: 1px solid #e5e7eb;">
                    <tr>
                        <th style="padding: 0.75rem 1rem; text-align: right;">المريض</th>
                        <th style="padding: 0.75rem 1rem; text-align: right;">نوع الزيارة</th>
                        <th style="padding: 0.75rem 1rem; text-align: right;">وصف الحالة</th>
                        <th style="padding: 0.75rem 1rem; text-align: right;">تاريخ الطلب</th>
                        <th style="padding: 0.75rem 1rem; text-align: right;">تاريخ الموعد</th>
                        <th style="padding: 0.75rem 1rem; text-align: right;">الحالة</th>
                        <th style="padding: 0.75rem 1rem; text-align: right;">ملاحظات</th>
                        <th style="padding: 0.75rem 1rem; text-align: right;">إجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($appointments as $appointment)
                    <tr style="border-bottom: 1px solid #e5e7eb;">
                        <td style="padding: 0.75rem 1rem;">{{ $appointment->patient->user->name }}</td>
                        <td style="padding: 0.75rem 1rem; font-size: 0.9rem;">{{ $appointment->disease_type ?? '-' }}</td>
                        <td style="padding: 0.75rem 1rem; font-size: 0.9rem;">{{ Str::limit($appointment->patient_description, 30) ?? '-' }}</td>
                        <td style="padding: 0.75rem 1rem; font-size: 0.9rem;">
                            {{ $appointment->requested_date ? $appointment->requested_date->format('Y-m-d') : '-' }}
                        </td>
                        <td style="padding: 0.75rem 1rem;">
                            @if($appointment->appointment_date)
                                {{ $appointment->appointment_date->format('Y-m-d H:i') }}
                            @else
                                <span style="color: #d97706;">بانتظار التأكيد</span>
                            @endif
                        </td>
                        <td style="padding: 0.75rem 1rem;">
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
                        </td>
                        <td style="padding: 0.75rem 1rem; font-size: 0.9rem; color: #6b7280;">{{ Str::limit($appointment->notes, 20) ?? '-' }}</td>
                        <td style="padding: 0.75rem 1rem;">
                            @if($appointment->status == 'pending')
                            <button onclick="confirmAppointment({{ $appointment->id }})" style="color: #2563eb; background: none; border: none; cursor: pointer; font-family: inherit; font-size: 0.9rem; margin-left: 0.5rem;">
                                تأكيد
                            </button>
                            @endif
                            @if($appointment->status == 'confirmed')
                            <a href="{{ route('doctor.appointments.edit', $appointment) }}" style="color: #f59e0b; margin-left: 0.5rem; font-size: 0.9rem; text-decoration: none;">
                                تعديل
                            </a>
                            <a href="{{ route('medical-records.create', $appointment) }}" style="color: #7c3aed; margin-left: 0.5rem; font-size: 0.9rem; text-decoration: none;">
                                سجل طبي
                            </a>
                            @endif
                            @if($appointment->status != 'completed' && $appointment->status != 'cancelled')
                            <form method="POST" action="{{ route('appointments.complete', $appointment) }}" style="display: inline; margin-left: 0.5rem;">
                                @csrf
                                @method('PATCH')
                                <button type="submit" style="color: #059669; background: none; border: none; cursor: pointer; font-family: inherit; font-size: 0.9rem;">
                                    إكمال
                                </button>
                            </form>
                            @endif
                            @if($appointment->status != 'cancelled')
                            <form method="POST" action="{{ route('doctor.appointments.destroy', $appointment) }}" style="display: inline; margin-left: 0.5rem;" onsubmit="return confirm('هل أنت متأكد من حذف هذا الموعد؟')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="color: #ef4444; background: none; border: none; cursor: pointer; font-family: inherit; font-size: 0.9rem;">
                                    حذف
                                </button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            @if($appointments->isEmpty())
            <div style="padding: 2rem 0; text-align: center; color: #6b7280;">
                لا توجد مواعيد
            </div>
            @endif

            @if($appointments->hasPages())
            <div style="padding: 1rem; border-top: 1px solid #e5e7eb;">
                {{ $appointments->links() }}
            </div>
            @endif
        </div>
    </div>

    <div id="confirmModal" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background-color: rgba(0,0,0,0.5); z-index: 50; align-items: center; justify-content: center;">
        <div style="background: #fff; border-radius: 8px; padding: 1.5rem; max-width: 28rem; margin: 0 auto;">
            <h3 style="font-size: 1.1rem; font-weight: 700; margin-bottom: 1rem;">تأكيد الموعد</h3>
            <form id="confirmForm" method="POST">
                @csrf
                @method('PATCH')
                <div style="margin-bottom: 1rem;">
                    <label style="display: block; font-size: 0.9rem; font-weight: 700; margin-bottom: 0.25rem;">تاريخ ووقت الموعد</label>
                    <input type="datetime-local" name="appointment_date" required style="width: 100%; border: 1px solid #d1d5db; border-radius: 0.5rem; padding: 0.5rem;">
                </div>
                <div style="display: flex; gap: 1rem;">
                    <button type="submit" class="btn btn-primary">تأكيد</button>
                    <button type="button" onclick="closeModal()" class="btn btn-ghost">إلغاء</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function confirmAppointment(id) {
            document.getElementById('confirmForm').action = '/doctor/appointments/' + id + '/confirm';
            document.getElementById('confirmModal').style.display = 'flex';
        }
        function closeModal() {
            document.getElementById('confirmModal').style.display = 'none';
        }
    </script>
@endsection
