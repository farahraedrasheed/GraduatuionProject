@extends('layouts.app')

@section('title', 'التقارير الطبية - لوحة تحكم المريض')

@section('content')
    <div style="max-width: 1200px; margin: 0 auto;">
        <h2 style="font-size: 1.25rem; font-weight: 700; color: #1f2937; margin-bottom: 1.5rem;">التقارير الطبية</h2>

        @if(session('success'))
            <div style="padding: 1rem; background: #d1fae5; border-radius: 8px; margin-bottom: 1rem; color: #065f46;">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div style="padding: 1rem; background: #fee2e2; border-radius: 8px; margin-bottom: 1rem; color: #991b1b;">{{ session('error') }}</div>
        @endif

        <div style="background: #fff; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); padding: 1.5rem; margin-bottom: 1.5rem;">
            <h3 style="font-weight: 600; margin-bottom: 1rem;">رفع تقرير جديد</h3>
            <form method="POST" action="{{ route('patient.reports.upload') }}" enctype="multipart/form-data">
                @csrf
                <div style="display: flex; flex-direction: column; gap: 1rem;">
                    <div>
                        <label style="display: block; font-size: 0.9rem; font-weight: 600; color: #374151; margin-bottom: 0.25rem;">الملف (PDF, JPG, PNG, DOC - حد أقصى 10MB)</label>
                        <input type="file" name="file" required accept=".pdf,.jpg,.jpeg,.png,.doc,.docx"
                               style="width: 100%; padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 8px;">
                    </div>
                    <div>
                        <label style="display: block; font-size: 0.9rem; font-weight: 600; color: #374151; margin-bottom: 0.25rem;">وصف التقرير (اختياري)</label>
                        <textarea name="description" rows="2" maxlength="500" style="width: 100%; border: 1px solid #d1d5db; border-radius: 8px; padding: 0.75rem;">{{ old('description') }}</textarea>
                    </div>
                    <div style="display: flex; justify-content: flex-end;">
                        <button type="submit" class="btn btn-primary">رفع التقرير</button>
                    </div>
                </div>
            </form>
            @if($errors->any())
                <div style="margin-top: 1rem; padding: 0.75rem; background: #fee2e2; border-radius: 8px;">
                    @foreach($errors->all() as $error)
                        <p style="color: #dc2626; font-size: 0.85rem;">{{ $error }}</p>
                    @endforeach
                </div>
            @endif
        </div>

        <div style="background: #fff; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); padding: 1.5rem;">
            <h3 style="font-weight: 600; margin-bottom: 1rem;">التقارير المرفوعة</h3>
            @if($reports->isEmpty())
                <p style="color: #6b7280; text-align: center; padding: 2rem;">لا توجد تقارير مرفوعة بعد</p>
            @else
                <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                    @foreach($reports as $report)
                        <div style="display: flex; justify-content: space-between; align-items: center; padding: 1rem; border: 1px solid #e5e7eb; border-radius: 8px;">
                            <div style="flex: 1;">
                                <p style="font-weight: 600;">{{ $report->original_name }}</p>
                                @if($report->description)
                                    <p style="font-size: 0.85rem; color: #6b7280;">{{ $report->description }}</p>
                                @endif
                                <p style="font-size: 0.8rem; color: #9ca3af;">{{ $report->created_at->format('Y-m-d H:i') }}</p>
                            </div>
                            <div style="display: flex; gap: 0.5rem;">
                                <a href="{{ route('patient.reports.download', $report) }}" class="btn btn-ghost" style="font-size: 0.85rem; padding: 0.4rem 0.75rem;">تحميل</a>
                                <form method="POST" action="{{ route('patient.reports.destroy', $report) }}" onsubmit="return confirm('هل أنت متأكد من حذف هذا التقرير؟')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" style="font-size: 0.85rem; padding: 0.4rem 0.75rem;">حذف</button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
@endsection
