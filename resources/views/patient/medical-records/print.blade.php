<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="UTF-8">
    <title>السجلات الطبية - {{ auth()->user()->name }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;700&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Cairo', sans-serif; color: #1f2937; background: white; padding: 2rem; }
        .header { text-align: center; border-bottom: 2px solid #3b82f6; padding-bottom: 1rem; margin-bottom: 1.5rem; }
        .header h1 { font-size: 1.5rem; color: #3b82f6; }
        .header p { font-size: 0.9rem; color: #6b7280; margin-top: 0.25rem; }
        .patient-info { background: #f0f9ff; border: 1px solid #bae6fd; border-radius: 8px; padding: 1rem; margin-bottom: 1.5rem; display: grid; grid-template-columns: 1fr 1fr 1fr 1fr; gap: 0.5rem; font-size: 0.875rem; }
        .patient-info span { color: #0369a1; font-weight: 700; }
        .record { border: 1px solid #e5e7eb; border-radius: 8px; padding: 1rem; margin-bottom: 1rem; page-break-inside: avoid; }
        .record-header { display: flex; justify-content: space-between; margin-bottom: 0.75rem; }
        .record-doctor { font-weight: 700; font-size: 1rem; }
        .record-specialty { color: #6b7280; font-size: 0.85rem; }
        .record-date { color: #9ca3af; font-size: 0.85rem; }
        .section { padding: 0.75rem; border-radius: 6px; margin-bottom: 0.5rem; }
        .section-diagnosis { background: #fefce8; border: 1px solid #fde68a; }
        .section-prescription { background: #eff6ff; border: 1px solid #bfdbfe; }
        .section-notes { background: #f9fafb; border: 1px solid #e5e7eb; }
        .section-label { font-weight: 700; font-size: 0.85rem; margin-bottom: 0.25rem; }
        .section-diagnosis .section-label { color: #92400e; }
        .section-prescription .section-label { color: #1e40af; }
        .section-notes .section-label { color: #374151; }
        .empty { text-align: center; padding: 3rem; color: #9ca3af; }
        .footer { margin-top: 2rem; border-top: 1px solid #e5e7eb; padding-top: 1rem; text-align: center; font-size: 0.75rem; color: #9ca3af; }
        .no-print { margin-bottom: 1.5rem; }
        @media print {
            .no-print { display: none !important; }
            body { padding: 1rem; }
        }
    </style>
</head>
<body>

<div class="no-print" style="display: flex; gap: 1rem; align-items: center;">
    <button onclick="window.print()"
            style="background:#3b82f6;color:white;border:none;border-radius:6px;padding:0.6rem 1.5rem;cursor:pointer;font-family:'Cairo',sans-serif;font-size:0.9rem;font-weight:700;">
        🖨️ طباعة / حفظ PDF
    </button>
    <a href="{{ route('patient.medical-records') }}"
       style="color:#6b7280;text-decoration:none;font-size:0.9rem;">← العودة</a>
</div>

<div class="header">
    <h1>المركز الطبي - السجلات الطبية</h1>
    <p>{{ auth()->user()->name }} | تاريخ الطباعة: {{ now()->format('Y-m-d') }}</p>
</div>

<div class="patient-info">
    <div><span>العمر:</span> {{ $patient->age ?? '-' }}</div>
    <div><span>الجنس:</span> {{ $patient->gender == 'male' ? 'ذكر' : 'أنثى' }}</div>
    <div><span>الهاتف:</span> {{ $patient->phone ?? '-' }}</div>
    <div><span>فصيلة الدم:</span> {{ $patient->blood_type ?? 'غير محددة' }}</div>
</div>

@forelse($records as $record)
<div class="record">
    <div class="record-header">
        <div>
            <div class="record-doctor">د. {{ $record->doctor->user->name }}</div>
            <div class="record-specialty">{{ $record->doctor->specialty }}</div>
        </div>
        <div class="record-date">{{ $record->created_at->format('Y-m-d') }}</div>
    </div>

    <div class="section section-diagnosis">
        <div class="section-label">التشخيص</div>
        <div>{{ $record->diagnosis }}</div>
    </div>

    @if($record->prescription)
    <div class="section section-prescription">
        <div class="section-label">الوصفة الطبية</div>
        <div style="white-space: pre-line;">{{ $record->prescription }}</div>
    </div>
    @endif

    @if($record->notes)
    <div class="section section-notes">
        <div class="section-label">ملاحظات</div>
        <div>{{ $record->notes }}</div>
    </div>
    @endif
</div>
@empty
<div class="empty">لا توجد سجلات طبية</div>
@endforelse

<div class="footer">
    المركز الطبي | جميع الحقوق محفوظة © {{ now()->year }}
</div>
</body>
</html>
