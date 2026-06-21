@extends('layouts.app')

@section('title', 'حجز موعد جديد - المركز الطبي')

@section('content')
    <div style="max-width: 1200px; margin: 0 auto;">
        <div style="background: #fff; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); padding: 1.5rem;">
            <h2 style="font-size: 1.25rem; font-weight: 700; color: #1f2937; margin-bottom: 1.5rem;">حجز موعد جديد لمريض</h2>

            @if(session('success'))
            <div style="background-color: #d1fae5; border: 1px solid #6ee7b7; color: #065f46; padding: 0.75rem 1rem; border-radius: 0.25rem; margin-bottom: 1rem;">
                {{ session('success') }}
            </div>
            @endif

            <form method="POST" action="{{ route('doctor.appointments.store') }}">
                @csrf

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                    <div style="margin-bottom: 1rem;">
                        <label style="display: block; font-size: 0.9rem; font-weight: 700; color: #374151; margin-bottom: 0.5rem;">اختر المريض</label>
                        <input type="text" id="patient_search" placeholder="ابحث باسم المريض..."
                            style="width: 100%; border: 1px solid #d1d5db; border-radius: 0.5rem; padding: 0.5rem; margin-bottom: 0.5rem;">
                        <select name="patient_id" id="patient_select" required style="width: 100%; border: 1px solid #d1d5db; border-radius: 0.5rem; padding: 0.5rem;">
                            <option value="">-- اختر المريض --</option>
                            @php
                                $previousPatients = $patients->where('is_previous', true);
                                $newPatients = $patients->where('is_previous', false);
                            @endphp
                            @if($previousPatients->isNotEmpty())
                            <optgroup label="مرضاي السابقين">
                                @foreach($previousPatients as $patient)
                                <option value="{{ $patient->id }}" data-name="{{ $patient->user->name }}">{{ $patient->user->name }} - {{ $patient->phone ?? '-' }}</option>
                                @endforeach
                            </optgroup>
                            @endif
                            @if($newPatients->isNotEmpty())
                            <optgroup label="مرضى جدد">
                                @foreach($newPatients as $patient)
                                <option value="{{ $patient->id }}" data-name="{{ $patient->user->name }}">{{ $patient->user->name }} - {{ $patient->phone ?? '-' }}</option>
                                @endforeach
                            </optgroup>
                            @endif
                        </select>
                        @error('patient_id')
                        <p style="color: #ef4444; font-size: 0.8rem; margin-top: 0.25rem;">{{ $message }}</p>
                        @enderror
                    </div>

                    <div style="margin-bottom: 1rem;">
                        <label style="display: block; font-size: 0.9rem; font-weight: 700; color: #374151; margin-bottom: 0.5rem;">التاريخ المطلوب من المريض</label>
                        <input type="date" name="requested_date" required style="width: 100%; border: 1px solid #d1d5db; border-radius: 0.5rem; padding: 0.5rem;">
                        @error('requested_date')
                        <p style="color: #ef4444; font-size: 0.8rem; margin-top: 0.25rem;">{{ $message }}</p>
                        @enderror
                    </div>

                    <div style="margin-bottom: 1rem;">
                        <label style="display: block; font-size: 0.9rem; font-weight: 700; color: #374151; margin-bottom: 0.5rem;">نوع الخدمة</label>
                        <select name="disease_type" style="width: 100%; border: 1px solid #d1d5db; border-radius: 0.5rem; padding: 0.5rem;">
                            <option value="">-- اختر نوع الخدمة --</option>
                            <optgroup label="تطعيمات الأطفال">
                                <option value="تطعيم حديثي الولادة">تطعيم حديثي الولادة</option>
                                <option value="تطعيم الشهر الثاني">تطعيم الشهر الثاني</option>
                                <option value="تطعيم الشهر الرابع">تطعيم الشهر الرابع</option>
                                <option value="تطعيم الشهر السادس">تطعيم الشهر السادس</option>
                                <option value="تطعيم الشهر التاسع">تطعيم الشهر التاسع</option>
                                <option value="تطعيم السنة الأولى">تطعيم السنة الأولى</option>
                                <option value="تطعيم الصف الأول">تطعيم الصف الأول</option>
                            </optgroup>
                            <optgroup label="الحمل والحوامل">
                                <option value="فحص حمل">فحص حمل</option>
                                <option value="فحص ما قبل الحمل">فحص ما قبل الحمل</option>
                                <option value="استشارة حمل">استشارة حمل</option>
                            </optgroup>
                            <optgroup label="الأمراض المزمنة">
                                <option value="أمراض القلب">أمراض القلب</option>
                                <option value="السكري والغدد">السكري والغدد</option>
                                <option value="أمراض الدم">أمراض الدم</option>
                                <option value="متابعة الأمراض المزمنة">متابعة الأمراض المزمنة</option>
                            </optgroup>
                            <optgroup label="الباطنة">
                                <option value="الكبد الوبائي">الكبد الوبائي</option>
                            </optgroup>
                            <optgroup label="أخرى">
                                <option value="كشف عام">كشف عام</option>
                                <option value="استشارة">استشارة</option>
                            </optgroup>
                        </select>
                        @error('disease_type')
                        <p style="color: #ef4444; font-size: 0.8rem; margin-top: 0.25rem;">{{ $message }}</p>
                        @enderror
                    </div>

                    <div style="margin-bottom: 1rem;">
                        <label style="display: block; font-size: 0.9rem; font-weight: 700; color: #374151; margin-bottom: 0.5rem;">وصف حالة المريض</label>
                        <input type="text" name="patient_description" style="width: 100%; border: 1px solid #d1d5db; border-radius: 0.5rem; padding: 0.5rem;" placeholder="أصف حالة المريض...">
                    </div>

                    <div style="margin-bottom: 1rem;">
                        <label style="display: block; font-size: 0.9rem; font-weight: 700; color: #374151; margin-bottom: 0.5rem;">اسم الدواء</label>
                        <input type="text" name="medicine_name" style="width: 100%; border: 1px solid #d1d5db; border-radius: 0.5rem; padding: 0.5rem;" placeholder="اسم الدواء...">
                    </div>

                    <div style="margin-bottom: 1rem;">
                        <label style="display: block; font-size: 0.9rem; font-weight: 700; color: #374151; margin-bottom: 0.5rem;">ملاحظة الطبيب</label>
                        <textarea name="doctor_notes" rows="3" style="width: 100%; border: 1px solid #d1d5db; border-radius: 0.5rem; padding: 0.5rem;" placeholder="ملاحظات حول العلاج..."></textarea>
                    </div>

                    <div style="grid-column: span 2; margin-bottom: 1rem;">
                        <label style="display: block; font-size: 0.9rem; font-weight: 700; color: #374151; margin-bottom: 0.5rem;">ملاحظات</label>
                        <textarea name="notes" rows="3" style="width: 100%; border: 1px solid #d1d5db; border-radius: 0.5rem; padding: 0.5rem;" placeholder="ملاحظات حول الموعد..."></textarea>
                    </div>
                </div>

                <div style="margin-top: 1.5rem; display: flex; gap: 1rem;">
                    <button type="submit" class="btn btn-primary">إرسال طلب الموافقة</button>
                    <a href="{{ route('doctor.appointments') }}" class="btn btn-ghost">إلغاء</a>
                </div>
            </form>
        </div>
    </div>

    <script>
        const searchInput = document.getElementById('patient_search');
        const patientSelect = document.getElementById('patient_select');

        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            for (let i = 0; i < patientSelect.options.length; i++) {
                const opt = patientSelect.options[i];
                if (opt.parentNode.tagName === 'OPTGROUP') {
                    opt.style.display = opt.text.toLowerCase().includes(searchTerm) ? '' : 'none';
                }
            }
        });
    </script>
@endsection