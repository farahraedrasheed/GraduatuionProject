@extends('layouts.app')

@section('title', 'إضافة مريض جديد - المركز الطبي')

@section('content')
<div style="max-width: 700px; margin: 0 auto;">
    <div style="background: #fff; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); padding: 2rem;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
            <h2 style="font-size: 1.25rem; font-weight: 700; color: #1f2937; margin: 0;">إضافة مريض جديد</h2>
            <a href="{{ route('patients.index') }}" style="color: #6b7280; font-size: 0.9rem; text-decoration: none;">← العودة للقائمة</a>
        </div>

        @if ($errors->any())
        <div style="background: #fee2e2; border: 1px solid #fca5a5; border-radius: 0.5rem; padding: 1rem; margin-bottom: 1rem;">
            @foreach ($errors->all() as $error)
                <p style="color: #dc2626; font-size: 0.875rem; margin: 0.25rem 0;">{{ $error }}</p>
            @endforeach
        </div>
        @endif

        <form method="POST" action="{{ route('patients.store') }}">
            @csrf

            <h3 style="font-weight: 700; color: #374151; margin-bottom: 1rem; border-bottom: 1px solid #e5e7eb; padding-bottom: 0.5rem;">بيانات الحساب</h3>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                <div>
                    <label style="display: block; font-size: 0.875rem; font-weight: 600; color: #374151; margin-bottom: 0.25rem;">الاسم الكامل *</label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                           style="width: 100%; padding: 0.5rem 0.75rem; border: 1px solid #d1d5db; border-radius: 0.5rem; font-family: inherit; box-sizing: border-box;">
                </div>
                <div>
                    <label style="display: block; font-size: 0.875rem; font-weight: 600; color: #374151; margin-bottom: 0.25rem;">البريد الإلكتروني *</label>
                    <input type="email" name="email" value="{{ old('email') }}" required
                           style="width: 100%; padding: 0.5rem 0.75rem; border: 1px solid #d1d5db; border-radius: 0.5rem; font-family: inherit; box-sizing: border-box;">
                </div>
            </div>

            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; font-size: 0.875rem; font-weight: 600; color: #374151; margin-bottom: 0.25rem;">كلمة المرور *</label>
                <input type="password" name="password" required
                       style="width: 100%; padding: 0.5rem 0.75rem; border: 1px solid #d1d5db; border-radius: 0.5rem; font-family: inherit; box-sizing: border-box;">
            </div>

            <h3 style="font-weight: 700; color: #374151; margin-bottom: 1rem; border-bottom: 1px solid #e5e7eb; padding-bottom: 0.5rem;">البيانات الطبية</h3>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                <div>
                    <label style="display: block; font-size: 0.875rem; font-weight: 600; color: #374151; margin-bottom: 0.25rem;">رقم الهاتف *</label>
                    <input type="tel" name="phone" value="{{ old('phone') }}" required
                           style="width: 100%; padding: 0.5rem 0.75rem; border: 1px solid #d1d5db; border-radius: 0.5rem; font-family: inherit; box-sizing: border-box;">
                </div>
                <div>
                    <label style="display: block; font-size: 0.875rem; font-weight: 600; color: #374151; margin-bottom: 0.25rem;">تاريخ الميلاد *</label>
                    <input type="date" name="date_of_birth" value="{{ old('date_of_birth') }}" required
                           style="width: 100%; padding: 0.5rem 0.75rem; border: 1px solid #d1d5db; border-radius: 0.5rem; font-family: inherit; box-sizing: border-box;">
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                <div>
                    <label style="display: block; font-size: 0.875rem; font-weight: 600; color: #374151; margin-bottom: 0.25rem;">الجنس *</label>
                    <select name="gender" required style="width: 100%; padding: 0.5rem 0.75rem; border: 1px solid #d1d5db; border-radius: 0.5rem; font-family: inherit; box-sizing: border-box;">
                        <option value="male"   {{ old('gender') == 'male'   ? 'selected' : '' }}>ذكر</option>
                        <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>أنثى</option>
                    </select>
                </div>
                <div>
                    <label style="display: block; font-size: 0.875rem; font-weight: 600; color: #374151; margin-bottom: 0.25rem;">فصيلة الدم</label>
                    <select name="blood_type" style="width: 100%; padding: 0.5rem 0.75rem; border: 1px solid #d1d5db; border-radius: 0.5rem; font-family: inherit; box-sizing: border-box;">
                        <option value="">-- غير محددة --</option>
                        @foreach(['A+','A-','B+','B-','AB+','AB-','O+','O-'] as $bt)
                            <option value="{{ $bt }}" {{ old('blood_type') == $bt ? 'selected' : '' }}>{{ $bt }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div style="margin-bottom: 1rem;">
                <label style="display: block; font-size: 0.875rem; font-weight: 600; color: #374151; margin-bottom: 0.25rem;">فئة المريض *</label>
                <select name="patient_type" id="patientType" onchange="toggleChronic()" required
                        style="width: 100%; padding: 0.5rem 0.75rem; border: 1px solid #d1d5db; border-radius: 0.5rem; font-family: inherit; box-sizing: border-box;">
                    <option value="child"          {{ old('patient_type') == 'child'          ? 'selected' : '' }}>طفل (طب الأطفال)</option>
                    <option value="pregnant"       {{ old('patient_type') == 'pregnant'       ? 'selected' : '' }}>حامل (نساء وتوليد)</option>
                    <option value="chronic_disease"{{ old('patient_type') == 'chronic_disease'? 'selected' : '' }}>مريض مزمن</option>
                    <option value="other"          {{ old('patient_type') == 'other'          ? 'selected' : '' }}>أخرى</option>
                </select>
            </div>

            <div id="chronicFields" style="display: none; margin-bottom: 1rem;">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div>
                        <label style="display: block; font-size: 0.875rem; font-weight: 600; color: #374151; margin-bottom: 0.25rem;">المرض المزمن الأول</label>
                        <select name="chronic_disease_type" style="width: 100%; padding: 0.5rem 0.75rem; border: 1px solid #d1d5db; border-radius: 0.5rem; font-family: inherit; box-sizing: border-box;">
                            <option value="">-- اختر --</option>
                            <option value="القلب والشرايين">القلب والشرايين</option>
                            <option value="أمراض الكلى">أمراض الكلى</option>
                            <option value="السكري والغدد">السكري والغدد</option>
                            <option value="أمراض الدم">أمراض الدم</option>
                            <option value="الباطنة">الباطنة</option>
                            <option value="الكبد الوبائي">الكبد الوبائي</option>
                        </select>
                    </div>
                    <div>
                        <label style="display: block; font-size: 0.875rem; font-weight: 600; color: #374151; margin-bottom: 0.25rem;">المرض المزمن الثاني</label>
                        <select name="chronic_disease_type2" style="width: 100%; padding: 0.5rem 0.75rem; border: 1px solid #d1d5db; border-radius: 0.5rem; font-family: inherit; box-sizing: border-box;">
                            <option value="">-- اختر --</option>
                            <option value="القلب والشرايين">القلب والشرايين</option>
                            <option value="أمراض الكلى">أمراض الكلى</option>
                            <option value="السكري والغدد">السكري والغدد</option>
                            <option value="أمراض الدم">أمراض الدم</option>
                            <option value="الباطنة">الباطنة</option>
                            <option value="الكبد الوبائي">الكبد الوبائي</option>
                        </select>
                    </div>
                </div>
            </div>

            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; font-size: 0.875rem; font-weight: 600; color: #374151; margin-bottom: 0.25rem;">العنوان</label>
                <input type="text" name="address" value="{{ old('address') }}"
                       style="width: 100%; padding: 0.5rem 0.75rem; border: 1px solid #d1d5db; border-radius: 0.5rem; font-family: inherit; box-sizing: border-box;">
            </div>

            <div style="display: flex; gap: 1rem; justify-content: flex-end;">
                <a href="{{ route('patients.index') }}" class="btn btn-ghost" style="text-decoration: none;">إلغاء</a>
                <button type="submit" class="btn btn-primary">إضافة المريض</button>
            </div>
        </form>
    </div>
</div>

<script>
    function toggleChronic() {
        const type = document.getElementById('patientType').value;
        document.getElementById('chronicFields').style.display = type === 'chronic_disease' ? 'block' : 'none';
    }
    document.addEventListener('DOMContentLoaded', toggleChronic);
</script>
@endsection
