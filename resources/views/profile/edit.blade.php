@extends('layouts.app')

@section('title', 'تعديل الملف الشخصي - المركز الطبي')

@section('styles')
    @vite('resources/css/app.css')
@endsection

@section('content')
    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        @if(session('status'))
        <div class="mb-4 font-medium text-sm text-green-600">
            {{ session('status') }}
        </div>
        @endif

        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-800 mb-6">تعديل المعلومات الشخصية</h2>

                <form method="POST" action="{{ route('profile.update') }}">
                    @csrf
                    @method('patch')

                    <div>
                        <x-input-label for="name" value="الاسم" />
                        <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" value="{{ old('name', $user->name) }}" required autofocus />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <div class="mt-4">
                        <x-input-label for="email" value="البريد الإلكتروني" />
                        <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" value="{{ old('email', $user->email) }}" required />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    @if($user->role === 'patient' && $profile)
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">معلومات المريض</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <x-input-label for="patient_type" value="فئة المريض" />
                                <select id="patient_type" name="patient_type" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                    <option value="child" {{ $profile->patient_type == 'child' ? 'selected' : '' }}>طفل (تطعيم)</option>
                                    <option value="pregnant" {{ $profile->patient_type == 'pregnant' ? 'selected' : '' }}>حامل</option>
                                    <option value="chronic_disease" {{ $profile->patient_type == 'chronic_disease' ? 'selected' : '' }}>مريض مزمن</option>
                                    <option value="other" {{ $profile->patient_type == 'other' ? 'selected' : '' }}>أخرى</option>
                                </select>
                            </div>
                            <div>
                                <x-input-label for="chronic_disease_type" value="نوع المرض المزمن" />
                                <select id="chronic_disease_type" name="chronic_disease_type" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                    <option value="">-- اختر --</option>
                                    <option value="القلب والشرايين" {{ $profile->chronic_disease_type == 'القلب والشرايين' ? 'selected' : '' }}>القلب والشرايين</option>
                                    <option value="أمراض الكلى" {{ $profile->chronic_disease_type == 'أمراض الكلى' ? 'selected' : '' }}>أمراض الكلى</option>
                                    <option value="السكري والغدد" {{ $profile->chronic_disease_type == 'السكري والغدد' ? 'selected' : '' }}>السكري والغدد</option>
                                    <option value="أمراض الدم" {{ $profile->chronic_disease_type == 'أمراض الدم' ? 'selected' : '' }}>أمراض الدم</option>
                                    <optgroup label="الباطنة">
                                        <option value="الكبد الوبائي" {{ $profile->chronic_disease_type == 'الكبد الوبائي' ? 'selected' : '' }}>الكبد الوبائي (الباطنة)</option>
                                    </optgroup>
                                </select>
                            </div>
                            <div>
                                <x-input-label for="chronic_disease_type2" value="فئة إضافية (اختياري)" />
                                <select id="chronic_disease_type2" name="chronic_disease_type2" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                    <option value="">-- اختر --</option>
                                    <option value="القلب والشرايين" {{ $profile->chronic_disease_type2 == 'القلب والشرايين' ? 'selected' : '' }}>القلب والشرايين</option>
                                    <option value="أمراض الكلى" {{ $profile->chronic_disease_type2 == 'أمراض الكلى' ? 'selected' : '' }}>أمراض الكلى</option>
                                    <option value="السكري والغدد" {{ $profile->chronic_disease_type2 == 'السكري والغدد' ? 'selected' : '' }}>السكري والغدد</option>
                                    <option value="أمراض الدم" {{ $profile->chronic_disease_type2 == 'أمراض الدم' ? 'selected' : '' }}>أمراض الدم</option>
                                    <optgroup label="الباطنة">
                                        <option value="الكبد الوبائي" {{ $profile->chronic_disease_type2 == 'الكبد الوبائي' ? 'selected' : '' }}>الكبد الوبائي (الباطنة)</option>
                                    </optgroup>
                                    <option value="حامل" {{ $profile->chronic_disease_type2 == 'حامل' ? 'selected' : '' }}>حامل</option>
                                    <option value="طفل" {{ $profile->chronic_disease_type2 == 'طفل' ? 'selected' : '' }}>طفل</option>
                                </select>
                            </div>
                            <div>
                                <x-input-label for="phone" value="رقم الهاتف" />
                                <x-text-input id="phone" name="phone" type="text" class="mt-1 block w-full" value="{{ old('phone', $profile->phone) }}" required />
                            </div>
                            <div>
                                <x-input-label for="date_of_birth" value="تاريخ الميلاد" />
                                <x-text-input id="date_of_birth" name="date_of_birth" type="date" class="mt-1 block w-full" value="{{ old('date_of_birth', $profile->date_of_birth ? $profile->date_of_birth->format('Y-m-d') : '') }}" required />
                            </div>
                            <div>
                                <x-input-label for="gender" value="الجنس" />
                                <select id="gender" name="gender" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                    <option value="male" {{ $profile->gender == 'male' ? 'selected' : '' }}>ذكر</option>
                                    <option value="female" {{ $profile->gender == 'female' ? 'selected' : '' }}>أنثى</option>
                                </select>
                            </div>
                            <div>
                                <x-input-label for="blood_type" value="فصيلة الدم" />
                                <select id="blood_type" name="blood_type" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                    <option value="">غير محدد</option>
                                    <option value="A+" {{ $profile->blood_type == 'A+' ? 'selected' : '' }}>A+</option>
                                    <option value="A-" {{ $profile->blood_type == 'A-' ? 'selected' : '' }}>A-</option>
                                    <option value="B+" {{ $profile->blood_type == 'B+' ? 'selected' : '' }}>B+</option>
                                    <option value="B-" {{ $profile->blood_type == 'B-' ? 'selected' : '' }}>B-</option>
                                    <option value="O+" {{ $profile->blood_type == 'O+' ? 'selected' : '' }}>O+</option>
                                    <option value="O-" {{ $profile->blood_type == 'O-' ? 'selected' : '' }}>O-</option>
                                    <option value="AB+" {{ $profile->blood_type == 'AB+' ? 'selected' : '' }}>AB+</option>
                                    <option value="AB-" {{ $profile->blood_type == 'AB-' ? 'selected' : '' }}>AB-</option>
                                </select>
                            </div>
                            <div class="md:col-span-2">
                                <x-input-label for="address" value="العنوان" />
                                <x-text-input id="address" name="address" type="text" class="mt-1 block w-full" value="{{ old('address', $profile->address) }}" />
                            </div>
                        </div>
                    </div>
                    @endif

                    @if($user->role === 'doctor' && $profile)
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">معلومات الطبيب</h3>
                        <div>
                            <x-input-label for="specialty" value="التخصص" />
                            <select id="specialty" name="specialty" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                <option value="">-- اختر التخصص --</option>
                                <optgroup label="طب الأطفال">
                                    <option value="طب الأطفال" {{ $profile->specialty == 'طب الأطفال' ? 'selected' : '' }}>طب الأطفال</option>
                                </optgroup>
                                <optgroup label="الحمل والولادة">
                                    <option value="نساء وتوليد" {{ $profile->specialty == 'نساء وتوليد' ? 'selected' : '' }}>نساء وتوليد</option>
                                </optgroup>
                                <optgroup label="الأمراض المزمنة">
                                    <option value="القلب والشرايين" {{ $profile->specialty == 'القلب والشرايين' ? 'selected' : '' }}>القلب والشرايين</option>
                                    <option value="السكري والغدد" {{ $profile->specialty == 'السكري والغدد' ? 'selected' : '' }}>السكري والغدد</option>
                                    <option value="أمراض الدم" {{ $profile->specialty == 'أمراض الدم' ? 'selected' : '' }}>أمراض الدم</option>
                                </optgroup>
                                <optgroup label="الباطنة">
                                    <option value="الباطنة" {{ $profile->specialty == 'الباطنة' ? 'selected' : '' }}>الباطنة</option>
                                    <option value="الكبد الوبائي" {{ $profile->specialty == 'الكبد الوبائي' ? 'selected' : '' }}>الكبد الوبائي (الباطنة)</option>
                                </optgroup>
                                <optgroup label="طب عام">
                                    <option value="طب عام" {{ $profile->specialty == 'طب عام' ? 'selected' : '' }}>طب عام</option>
                                </optgroup>
                            </select>
                        </div>
                        @if($profile->specialty != 'طب عام')
                        <div class="mt-4">
                            <x-input-label for="bio" value="نبذة عن الطبيب" />
                            <textarea id="bio" name="bio" rows="3" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('bio', $profile->bio) }}</textarea>
                        </div>
                        @endif
                        <div class="mt-4">
                            <x-input-label for="license_number" value="رقم الترخيص" />
                            <x-text-input id="license_number" type="text" class="mt-1 block w-full bg-gray-100" value="{{ $profile->license_number }}" disabled />
                        </div>
                    </div>
                    @endif

                    <div class="flex items-center justify-end mt-4">
                        <x-primary-button>
                            حفظ التغييرات
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>

        <div style="background: #fff; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); padding: 1.5rem; margin-top: 1.5rem;">
            <h3 style="font-size: 1.1rem; font-weight: 700; color: #1f2937; margin-bottom: 1rem;">تغيير كلمة السر</h3>

            @if(session('password_status'))
            <div style="background-color: #d1fae5; border: 1px solid #6ee7b7; color: #065f46; padding: 0.75rem 1rem; border-radius: 0.25rem; margin-bottom: 1rem;">
                {{ session('password_status') }}
            </div>
            @elseif(session('status'))
            <div style="background-color: #d1fae5; border: 1px solid #6ee7b7; color: #065f46; padding: 0.75rem 1rem; border-radius: 0.25rem; margin-bottom: 1rem;">
                {{ session('status') }}
            </div>
            @endif

            <form method="POST" action="{{ route('profile.password') }}">
                @csrf
                @method('patch')

                <div style="display: flex; flex-direction: column; gap: 1rem;">
                    <div>
                        <label style="display: block; font-size: 0.9rem; font-weight: 700; color: #374151; margin-bottom: 0.25rem;">كلمة السر الحالية</label>
                        <input type="password" name="current_password" required style="width: 100%; max-width: 400px; border: 1px solid #d1d5db; border-radius: 0.5rem; padding: 0.5rem;">
                        @error('current_password')
                        <p style="color: #ef4444; font-size: 0.8rem; margin-top: 0.25rem;">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label style="display: block; font-size: 0.9rem; font-weight: 700; color: #374151; margin-bottom: 0.25rem;">كلمة السر الجديدة</label>
                        <input type="password" name="password" required style="width: 100%; max-width: 400px; border: 1px solid #d1d5db; border-radius: 0.5rem; padding: 0.5rem;">
                        @error('password')
                        <p style="color: #ef4444; font-size: 0.8rem; margin-top: 0.25rem;">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label style="display: block; font-size: 0.9rem; font-weight: 700; color: #374151; margin-bottom: 0.25rem;">تأكيد كلمة السر الجديدة</label>
                        <input type="password" name="password_confirmation" required style="width: 100%; max-width: 400px; border: 1px solid #d1d5db; border-radius: 0.5rem; padding: 0.5rem;">
                    </div>
                </div>

                <div style="margin-top: 1rem;">
                    <button type="submit" class="btn btn-primary">تغيير كلمة السر</button>
                </div>
            </form>
        </div>
    </div>
@endsection