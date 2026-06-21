@extends('layouts.main')

@section('title', 'إنشاء حساب جديد - المركز الطبي')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/common.css') }}">
    <style>
        .register-wrapper {
            max-width: 672px;
            margin: 3rem auto;
            padding: 2rem;
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        .register-wrapper h2 {
            text-align: center;
            margin-bottom: 1.5rem;
            color: #1f2937;
        }
        .form-group { margin-bottom: 1rem; }
        .form-group label { display: block; margin-bottom: 0.25rem; font-weight: 600; color: #374151; }
        .form-group input, .form-group select {
            width: 100%;
            padding: 10px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-family: 'Cairo', sans-serif;
            box-sizing: border-box;
        }
        .form-group input:focus, .form-group select:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59,130,246,0.2);
        }
        .hidden { display: none; }
        .flex-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-top: 1.5rem;
        }
    </style>
@endsection

@section('content')
    <div class="register-wrapper">
        <h2>إنشاء حساب جديد</h2>

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="form-group">
                <label for="role">نوع الحساب</label>
                <select id="role" name="role" onchange="toggleRoleFields()">
                    <option value="patient" {{ request('role') == 'patient' ? 'selected' : '' }}>مريض</option>
                    <option value="doctor" {{ request('role') == 'doctor' ? 'selected' : '' }}>طبيب</option>
                </select>
            </div>

            <div id="doctorFields" class="hidden">
                <div class="form-group">
                    <label for="specialty">التخصص</label>
                    <select id="specialty" name="specialty">
                        <option value="">-- اختر التخصص --</option>
                        <optgroup label="طب الأطفال">
                            <option value="طب الأطفال">طب الأطفال</option>
                        </optgroup>
                        <optgroup label="الحمل والولادة">
                            <option value="نساء وتوليد">نساء وتوليد</option>
                        </optgroup>
                        <optgroup label="الأمراض المزمنة">
                            <option value="القلب والشرايين">القلب والشرايين</option>
                            <option value="السكري والغدد">السكري والغدد</option>
                            <option value="أمراض الدم">أمراض الدم</option>
                        </optgroup>
                        <optgroup label="الباطنة">
                            <option value="الباطنة">الباطنة</option>
                            <option value="الكبد الوبائي">الكبد الوبائي (الباطنة)</option>
                        </optgroup>
                        <optgroup label="طب عام">
                            <option value="طب عام">طب عام</option>
                        </optgroup>
                    </select>
                </div>
                <div class="form-group">
                    <label for="license_number">رقم الترخيص</label>
                    <input type="text" name="license_number" id="license_number" placeholder="مثال: LIC-25-00001" value="{{ old('license_number') }}">
                    <p style="font-size:0.8rem;color:#6b7280;margin-top:0.25rem;">يجب أن يبدأ بـ LIC- يتبعه سنة ثم رقم تسلسلي</p>
                </div>
            </div>

            <div id="patientFields">
                @if(request('type'))
                    <input type="hidden" name="patient_type" value="{{ request('type') == 'child' ? 'child' : (request('type') == 'pregnant' ? 'pregnant' : 'chronic_disease') }}">
                @endif
                <div class="form-group" {{ request('type') ? 'style=display:none;' : '' }}>
                    <label for="patient_type">فئة المريض</label>
                    <select id="patient_type" name="patient_type" onchange="toggleChronicDisease()">
                        <option value="child" {{ request('type') == 'child' ? 'selected' : '' }}>طفل (طب الأطفال)</option>
                        <option value="pregnant" {{ request('type') == 'pregnant' ? 'selected' : '' }}>حامل (نساء وتوليد)</option>
                        <option value="chronic_disease" {{ request('type') == 'chronic' ? 'selected' : '' }}>مريض مزمن</option>
                        <option value="other">أخرى</option>
                    </select>
                </div>

                <div id="chronicDiseaseField" class="form-group hidden">
                    <label for="chronic_disease_type">نوع المرض المزمن</label>
                    <select id="chronic_disease_type" name="chronic_disease_type">
                        <option value="">-- اختر --</option>
                        <option value="القلب والشرايين">القلب والشرايين</option>
                        <option value="أمراض الكلى">أمراض الكلى</option>
                        <option value="السكري والغدد">السكري والغدد</option>
                        <option value="أمراض الدم">أمراض الدم</option>
                        <optgroup label="الباطنة">
                            <option value="الباطنة">الباطنة</option>
                            <option value="الكبد الوبائي">الكبد الوبائي (الباطنة)</option>
                        </optgroup>
                    </select>
                </div>

                <div class="form-group">
                    <label for="phone">رقم الهاتف</label>
                    <input type="tel" name="phone" id="phone" placeholder="0599999999" value="{{ old('phone') }}">
                </div>

                <div class="form-group">
                    <label for="date_of_birth">تاريخ الميلاد</label>
                    <input type="date" name="date_of_birth" id="date_of_birth" onchange="calculateAge(this.value)" value="{{ old('date_of_birth') }}">
                    <p id="ageDisplay" style="font-size: 0.875rem; color: #6b7280; margin-top: 0.25rem;"></p>
                    <input type="hidden" name="age" id="ageInput">
                </div>

                <div class="form-group">
                    <label for="gender">الجنس</label>
                    <select id="gender" name="gender">
                        <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>ذكر</option>
                        <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>أنثى</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label for="name">الاسم الكامل</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" required>
            </div>

            <div class="form-group">
                <label for="email">البريد الإلكتروني</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}" required>
            </div>

            <div class="form-group">
                <label for="password">كلمة المرور</label>
                <input type="password" name="password" id="password" required autocomplete="new-password">
            </div>

            <div class="form-group">
                <label for="password_confirmation">تأكيد كلمة المرور</label>
                <input type="password" name="password_confirmation" id="password_confirmation" required autocomplete="new-password">
            </div>

            @if ($errors->any())
                <div style="margin-bottom: 1rem; padding: 10px; background: #fee2e2; border-radius: 8px;">
                    @foreach ($errors->all() as $error)
                        <p style="color: #dc2626; font-size: 0.9rem;">{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <div class="flex-row">
                <a href="{{ route('login') }}" style="color: #6b7280; font-size: 0.9rem; text-decoration: underline;">لديك حساب بالفعل؟</a>
                <button type="submit" style="background: linear-gradient(135deg, #3b82f6, #1d4ed8); color: white; padding: 10px 30px; border: none; border-radius: 8px; font-weight: 600; cursor: pointer;">إنشاء حساب</button>
            </div>
        </form>
    </div>

    <script>
        function toggleRoleFields() {
            const role = document.getElementById('role').value;
            document.getElementById('patientFields').style.display = role === 'patient' ? 'block' : 'none';
            document.getElementById('doctorFields').style.display = role === 'doctor' ? 'block' : 'none';
        }
        function toggleChronicDisease() {
            const patientType = document.getElementById('patient_type').value;
            document.getElementById('chronicDiseaseField').style.display = patientType === 'chronic_disease' ? 'block' : 'none';
        }
        function calculateAge(birthDate) {
            if (!birthDate) return;
            const today = new Date();
            const birth = new Date(birthDate);
            let age = today.getFullYear() - birth.getFullYear();
            const monthDiff = today.getMonth() - birth.getMonth();
            if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birth.getDate())) {
                age--;
            }
            document.getElementById('ageDisplay').textContent = 'العمر: ' + age + ' سنة';
            document.getElementById('ageInput').value = age;
        }
        document.addEventListener('DOMContentLoaded', function() {
            toggleRoleFields();
        });
    </script>
@endsection