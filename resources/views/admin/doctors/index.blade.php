@extends('layouts.app')

@section('title', 'إدارة الأطباء - المركز الطبي')

@section('content')
    <div style="max-width: 1200px; margin: 0 auto;">
        <div style="background: #fff; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); padding: 1.5rem;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                <h2 style="font-size: 1.25rem; font-weight: 700; color: #1f2937; margin: 0;">
                    قائمة الأطباء
                    <span style="font-size: 0.875rem; font-weight: 400; color: #6b7280;">({{ $doctors->total() }})</span>
                </h2>
                <a href="{{ route('doctors.create') }}" class="btn btn-primary">+ إضافة طبيب جديد</a>
            </div>

            @if(session('success'))
            <div style="background-color: #d1fae5; border: 1px solid #6ee7b7; color: #065f46; padding: 0.75rem 1rem; border-radius: 0.25rem; margin-bottom: 1rem;">
                {{ session('success') }}
            </div>
            @endif

            <!-- Search & Filter -->
            <form method="GET" action="{{ route('doctors.index') }}" style="display: flex; gap: 0.75rem; margin-bottom: 1rem; flex-wrap: wrap;">
                <input type="text" name="search" placeholder="بحث بالاسم أو البريد..." value="{{ request('search') }}"
                       style="flex: 1; min-width: 200px; padding: 0.5rem 0.75rem; border: 1px solid #d1d5db; border-radius: 0.375rem; font-family: inherit; font-size: 0.875rem;">
                <input type="text" name="specialty" placeholder="بحث بالتخصص..." value="{{ request('specialty') }}"
                       style="flex: 1; min-width: 150px; padding: 0.5rem 0.75rem; border: 1px solid #d1d5db; border-radius: 0.375rem; font-family: inherit; font-size: 0.875rem;">
                <button type="submit" style="padding: 0.5rem 1rem; background: #3b82f6; color: white; border: none; border-radius: 0.375rem; cursor: pointer; font-family: inherit; font-size: 0.875rem;">
                    بحث
                </button>
            </form>

            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="text-align: right; color: #6b7280; border-bottom: 1px solid #e5e7eb;">
                        <th style="padding: 0.75rem 1rem;">#</th>
                        <th style="padding: 0.75rem 1rem;">الاسم</th>
                        <th style="padding: 0.75rem 1rem;">البريد الإلكتروني</th>
                        <th style="padding: 0.75rem 1rem;">التخصص</th>
                        <th style="padding: 0.75rem 1rem;">رقم الترخيص</th>
                        <th style="padding: 0.75rem 1rem;">الحالة</th>
                        <th style="padding: 0.75rem 1rem;">الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($doctors as $doctor)
                    <tr style="border-bottom: 1px solid #e5e7eb;">
                        <td style="padding: 0.75rem 1rem;">{{ $loop->iteration }}</td>
                        <td style="padding: 0.75rem 1rem; font-weight: 700;">{{ $doctor->user->name }}</td>
                        <td style="padding: 0.75rem 1rem;">{{ $doctor->user->email }}</td>
                        <td style="padding: 0.75rem 1rem;">{{ $doctor->specialty }}</td>
                        <td style="padding: 0.75rem 1rem;">{{ $doctor->license_number }}</td>
                        <td style="padding: 0.75rem 1rem;">
                            <span style="padding: 0.25rem 0.5rem; border-radius: 0.25rem; font-size: 0.875rem; {{ $doctor->is_active ? 'background-color: #d1fae5; color: #065f46;' : 'background-color: #fee2e2; color: #991b1b;' }}">
                                {{ $doctor->is_active ? 'نشط' : 'غير نشط' }}
                            </span>
                        </td>
                        <td style="padding: 0.75rem 1rem;">
                            <a href="{{ route('doctors.show', $doctor) }}" style="color: #2563eb; text-decoration: none; margin: 0 0.25rem;">عرض</a>
                            <a href="{{ route('doctors.edit', $doctor) }}" style="color: #d97706; text-decoration: none; margin: 0 0.25rem;">تعديل</a>
                            <form method="POST" action="{{ route('doctors.destroy', $doctor) }}" style="display: inline;" onsubmit="return confirm('هل أنت متأكد من حذف هذا الطبيب؟')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="color: #dc2626; background: none; border: none; cursor: pointer; margin: 0 0.25rem; font-family: inherit; font-size: inherit;">حذف</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" style="padding: 2rem 0; text-align: center; color: #6b7280;">لا يوجد أطباء مسجلين</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

            @if($doctors->hasPages())
            <div style="padding: 1rem; border-top: 1px solid #e5e7eb;">
                {{ $doctors->links() }}
            </div>
            @endif
        </div>
    </div>
@endsection
