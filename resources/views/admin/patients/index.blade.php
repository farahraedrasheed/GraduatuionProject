@extends('layouts.app')

@section('title', 'إدارة المرضى - المركز الطبي')

@section('content')
    <div style="max-width: 1200px; margin: 0 auto;">
        <div style="background: #fff; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); padding: 1.5rem;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; flex-wrap: wrap; gap: 0.75rem;">
                <h2 style="font-size: 1.25rem; font-weight: 700; color: #1f2937; margin: 0;">
                    قائمة المرضى
                    <span style="font-size: 0.875rem; font-weight: 400; color: #6b7280;">({{ $patients->total() }})</span>
                </h2>
                <a href="{{ route('patients.create') }}" class="btn btn-primary" style="text-decoration: none;">+ إضافة مريض جديد</a>

            </div>

            <!-- Search & Filter -->
            <form method="GET" action="{{ route('patients.index') }}" style="display: flex; gap: 0.75rem; margin-bottom: 1rem; flex-wrap: wrap;">
                <input type="text" name="search" placeholder="بحث بالاسم أو البريد..." value="{{ request('search') }}"
                       style="flex: 1; min-width: 200px; padding: 0.5rem 0.75rem; border: 1px solid #d1d5db; border-radius: 0.375rem; font-family: inherit; font-size: 0.875rem;">
                <select name="gender" style="padding: 0.5rem 0.75rem; border: 1px solid #d1d5db; border-radius: 0.375rem; font-family: inherit; font-size: 0.875rem;">
                    <option value="">كل الجنسين</option>
                    <option value="male" {{ request('gender') == 'male' ? 'selected' : '' }}>ذكر</option>
                    <option value="female" {{ request('gender') == 'female' ? 'selected' : '' }}>أنثى</option>
                </select>
                <button type="submit" style="padding: 0.5rem 1rem; background: #3b82f6; color: white; border: none; border-radius: 0.375rem; cursor: pointer; font-family: inherit; font-size: 0.875rem;">
                    بحث
                </button>
            </form>


            @if(session('success'))
            <div style="background-color: #d1fae5; border: 1px solid #6ee7b7; color: #065f46; padding: 0.75rem 1rem; border-radius: 0.25rem; margin-bottom: 1rem;">
                {{ session('success') }}
            </div>
            @endif

            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="text-align: right; color: #6b7280; border-bottom: 1px solid #e5e7eb;">
                        <th style="padding: 0.75rem 0.5rem;">#</th>
                        <th style="padding: 0.75rem 0.5rem;">الاسم</th>
                        <th style="padding: 0.75rem 0.5rem;">البريد الإلكتروني</th>
                        <th style="padding: 0.75rem 0.5rem;">الهاتف</th>
                        <th style="padding: 0.75rem 0.5rem;">الجنس</th>
                        <th style="padding: 0.75rem 0.5rem;">فصيلة الدم</th>
                        <th style="padding: 0.75rem 0.5rem;">الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($patients as $patient)
                    <tr style="border-bottom: 1px solid #e5e7eb;">
                        <td style="padding: 0.75rem 0.5rem;">{{ $loop->iteration }}</td>
                        <td style="padding: 0.75rem 0.5rem; font-weight: 700;">{{ $patient->user->name }}</td>
                        <td style="padding: 0.75rem 0.5rem;">{{ $patient->user->email }}</td>
                        <td style="padding: 0.75rem 0.5rem;">{{ $patient->phone }}</td>
                        <td style="padding: 0.75rem 0.5rem;">{{ $patient->gender == 'male' ? 'ذكر' : 'أنثى' }}</td>
                        <td style="padding: 0.75rem 0.5rem;">{{ $patient->blood_type ?? '-' }}</td>
                        <td style="padding: 0.75rem 0.5rem;">
                            <a href="{{ route('patients.show', $patient) }}" style="color: #2563eb; margin-left: 0.25rem; margin-right: 0.25rem;">عرض</a>
                            <a href="{{ route('patients.edit', $patient) }}" style="color: #d97706; margin-left: 0.25rem; margin-right: 0.25rem;">تعديل</a>
                            <form method="POST" action="{{ route('patients.destroy', $patient) }}" style="display: inline;" onsubmit="return confirm('هل أنت متأكد من حذف هذا المريض؟')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="color: #dc2626; background: none; border: none; cursor: pointer; margin-left: 0.25rem; margin-right: 0.25rem; font-family: inherit; font-size: inherit;">حذف</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" style="padding: 2rem 0; text-align: center; color: #6b7280;">لا يوجد مرضى مسجلين</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            @if($patients->hasPages())
            <div style="padding: 1rem; border-top: 1px solid #e5e7eb;">
                {{ $patients->links() }}
            </div>
            @endif
        </div>
    </div>
@endsection
