@extends('layouts.app')

@section('title', 'إضافة سجل طبي - المركز الطبي')

@section('content')
    <div style="max-width: 1200px; margin: 0 auto;">
        <div style="background: #fff; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); padding: 1.5rem;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
                <h2 style="font-size: 1.25rem; font-weight: 700; color: #1f2937;">
                    إضافة سجل طبي للمريض: {{ $patient->user->name }}
                </h2>
                <a href="{{ route('doctor.patients.show', $patient) }}" style="color: #6b7280; text-decoration: none;">← العودة</a>
            </div>

            <form method="POST" action="{{ route('medical-records.store-for-patient', $patient) }}">
                @csrf

                <div style="display: flex; flex-direction: column; gap: 1rem;">
                    <div>
                        <label style="display: block; font-size: 0.9rem; font-weight: 600; color: #374151; margin-bottom: 0.25rem;">التشخيص</label>
                        <textarea name="diagnosis" rows="3" required style="width: 100%; border: 1px solid #d1d5db; border-radius: 0.5rem; padding: 0.75rem;">{{ old('diagnosis') }}</textarea>
                    </div>

                    <div>
                        <label style="display: block; font-size: 0.9rem; font-weight: 600; color: #374151; margin-bottom: 0.25rem;">الوصفة الطبية</label>
                        <textarea name="prescription" rows="3" style="width: 100%; border: 1px solid #d1d5db; border-radius: 0.5rem; padding: 0.75rem;">{{ old('prescription') }}</textarea>
                    </div>

                    <div>
                        <label style="display: block; font-size: 0.9rem; font-weight: 600; color: #374151; margin-bottom: 0.25rem;">الملاحظات</label>
                        <textarea name="notes" rows="3" style="width: 100%; border: 1px solid #d1d5db; border-radius: 0.5rem; padding: 0.75rem;">{{ old('notes') }}</textarea>
                    </div>
                </div>

                <div style="display: flex; justify-content: flex-end; margin-top: 1.5rem; gap: 1rem;">
                    <a href="{{ route('doctor.patients.show', $patient) }}" class="btn btn-ghost">إلغاء</a>
                    <button type="submit" class="btn btn-primary">حفظ السجل الطبي</button>
                </div>
            </form>
        </div>
    </div>
@endsection
