<!-- 
ملاحظة مهمة: ملفات frontend/ هي واجهات HTML التي برمجناها واستخدمناها في Laravel 
عبر ملفات Blade في resources/views/. مش ملفين منفصلين — الفرونت هو التصميم 
والـ Blade هو التطبيق داخل Laravel. أي تعديل بالفرونت ينعكس على الباك.
-->

# خريطة المشروع الكاملة - المركز الطبي (Clinic)

---

## 📁 هيكل المشروع

```
clinic-app/
├── frontend/                    # واجهات الفرونت إند (HTML/CIS) — تُستخدم مباشرة في Laravel
├── resources/views/             # ملفات Laravel Blade (الباك إند)
├── routes/
│   ├── web.php                  # الراوتات الرئيسية
│   ├── auth.php                 # روتات المصادقة
│   └── console.php              # أوامر Artisan المجدولة
├── app/Http/Controllers/        # التحكم (Controllers)
│   ├── AdminDashboardController.php
│   ├── AppointmentController.php
│   ├── DoctorController.php
│   ├── DoctorDashboardController.php
│   ├── DoctorScheduleController.php
│   ├── MedicalRecordController.php
│   ├── MedicalReportController.php
│   ├── NotificationController.php
│   ├── PatientController.php
│   ├── PatientDashboardController.php
│   ├── PatientRecordController.php
│   ├── ProfileController.php
│   ├── ReportController.php
│   ├── SettingsController.php
│   └── Auth/ (8 files)
└── ...
```

---

## 🎯 الجزء الأول: الفرونت إند (frontend/)

### 📂 frontend/ — الصفحات العامة (Auth & Public)

| الملف | الصفحة | الوصف |
|-------|--------|-------|
| `index.html` | الصفحة الرئيسية | لاندينج الصفحة الرئيسية مع هيدر عام (layouts.main) — أزرار دخول/حساب، بدون جرس إشعارات |
| `about.html` | من نحن | صفحة تعريفية بالمركز الطبي — نفس هيكل الصفحة الرئيسية |
| `login.html` | تسجيل الدخول | فورم دخول (بريد إلكتروني + كلمة سر) — standalone بدون layout |
| `register.html` | إنشاء حساب | فورم تسجيل (اسم، بريد، كلمة سر، تأكيد كلمة سر، نوع المستخدم، أمراض مزمنة، حساب العمر) — standalone |
| `forgot-password.html` | نسيت كلمة المرور | فورم إرسال رابط إعادة تعيين كلمة السر | 
| `reset-password.html` | إعادة تعيين كلمة المرور | فورم تغيير كلمة السر بعد الضغط على الرابط |
| `verify-email.html` | تأكيد البريد | رسالة تأكيد البريد الإلكتروني بعد التسجيل |
| `confirm-password.html` | تأكيد كلمة المرور | إعادة تأكيد كلمة السر قبل الدخول لمنطقة حساسة |
| `profile.html` | الملف الشخصي | **للأدمن فقط** — تعديل المعلومات الشخصية + تغيير كلمة السر (بدون أقسام إضافية، بدون JS) |

### 📂 frontend/admin/ — لوحة تحكم المسؤول (11 صفحة)

| الملف | الصفحة | الوصف |
|-------|--------|-------|
| `dashboard.html` | لوحة التحكم | إحصائيات (مخطط دائري Chart.js + أرقام سريعة)، روابط سريعة، جدول آخر المواعيد |
| `appointments.html` | إدارة المواعيد | جدول بجميع المواعيد مع فلترة حسب الحالة، أزرار تعديل/حذف |
| `doctors.html` | إدارة الأطباء | جدول بالأطباء + زر إضافة طبيب |
| `doctors-create.html` | إضافة طبيب | فورم (الاسم، البريد، التخصص، الرقم، كلمة السر، التوكين) |
| `doctors-edit.html` | تعديل طبيب | فورم تعديل بيانات الطبيب |
| `doctors-show.html` | تفاصيل الطبيب | عرض بيانات الطبيب + قائمة مرضاه |
| `patients.html` | إدارة المرضى | جدول بالمرضى + زر إضافة مريض |
| `patients-create.html` | إضافة مريض | فورم (الاسم، البريد، فئة المريض، أمراض مزمنة، هاتف، تاريخ ميلاد، الجنس، فصيلة الدم، العنوان، كلمة السر) |
| `patients-edit.html` | تعديل مريض | فورم تعديل بيانات المريض |
| `patients-show.html` | تفاصيل مريض | عرض بيانات المريض + سجل الزيارات + أمراضه المزمنة |
| `notifications.html` | الإشعارات | قائمة الإشعارات مع علامات قراءة/غير مقروء |

### 📂 frontend/doctor/ — لوحة تحكم الطبيب (9 صفحات)

| الملف | الصفحة | الوصف |
|-------|--------|-------|
| `dashboard.html` | لوحة التحكم | إحصائيات الطبيب (مخطط دائري Chart.js + أرقام) |
| `appointments.html` | إدارة المواعيد | جدول مواعيد الطبيب مع فلترة (كل المواعيد، معلق، مؤكد، ملغي، مكتمل) + أزرار تأكيد/إلغاء/إكمال |
| `appointments-create.html` | حجز موعد | فورم حجز موعد جديد (اختيار مريض، التاريخ، الملاحظات) |
| `patients.html` | قائمة مرضاي | جدول بمرضى الطبيب |
| `patient-show.html` | سجل المريض | بيانات المريض + سجل الزيارات + السجلات الطبية |
| `medical-record-create.html` | إضافة سجل طبي | فورم (التشخيص، الوصفة، الملاحظات) — موعد محدد |
| `medical-record-add.html` | إضافة سجل طبي | نفس الفورم السابق — نسخة مكررة تقريباً (تحتوي على "حفظ السجل الطبي") |
| `profile.html` | الملف الشخصي | معلومات الطبيب (تخصص، نبذة (تظهر فقط لو التخصص ≠ طب عام)، رقم الترخيص) + تغيير كلمة السر |
| `schedule.html` | جدول المواعيد | جدول أسبوعي/شهري للمواعيد (نصي ثابت بدون مكتبة) |

### 📂 frontend/patient/ — لوحة تحكم المريض (5 صفحات)

| الملف | الصفحة | الوصف |
|-------|--------|-------|
| `dashboard.html` | لوحة التحكم | إحصائيات المريض + روابط سريعة (مواعيدي، السجلات الطبية، التقارير، ملفي) |
| `appointments.html` | مواعيدي | جدول بمواعيد المريض مع أزرار قبول/رفض للمواعيد المعلقة + إلغاء |
| `medical-records.html` | السجلات الطبية | شريط معلومات (العمر، الجنس، الهاتف، فصيلة الدم) — شريط رمادي فاتح — قائمة السجلات مع التشخيص والوصفة والملاحظات |
| `medical-reports.html` | التقارير الطبية | رفع تقرير جديد (ملف + وصف) + قائمة التقارير المرفوعة |
| `profile.html` | الملف الشخصي | معلومات المريض (فئة المريض، أمراض مزمنة، هاتف، تاريخ ميلاد، جنس، فصيلة دم، عنوان) + تغيير كلمة السر |

### 📂 frontend/css/ — ملفات الستايل

| الملف | الوصف |
|-------|--------|
| `style.css` | ستايل واحد مشترك لكل الصفحات (16KB) — كلاسات الهيدر، الناڤ، الأزرار، الفوتر، الـ dashboard، الجداول |

### 📂 frontend/Photo/ — الصور

| الملف | الوصف |
|-------|--------|
| `logo.jpeg` | شعار المركز الطبي |
| `خلفية.jpeg` | صورة خلفية للصفحة الرئيسية |

---

## 🧠 الجزء الثاني: الباك إند (resources/views/)

### 📂 resources/views/ — الصفحات العامة

| الملف | الصفحة | الوصف |
|-------|--------|-------|
| `welcome.blade.php` | الصفحة الرئيسية | @extends('layouts.main') — لاندينج |
| `about.blade.php` | من نحن | @extends('layouts.main') |
### 📂 resources/views/layouts/ — الـ Layouts

| الملف | الوصف |
|-------|--------|
| `app.blade.php` | **@layouts.app** — للمستخدمين المسجلين: ناڤ ديناميكي حسب الدور (admin/doctor/patient), جرس إشعارات (Alpine.js), زر تسجيل خروج |
| `main.blade.php` | **@layouts.main** — للصفحات العامة: ناڤ بسيط (الرئيسية / من نحن) + أزرار دخول/حساب، بدون جرس إشعارات |

### 📂 resources/views/admin/ — لوحة تحكم المسؤول

| الملف | الصفحة | الوصف |
|-------|--------|-------|
| `dashboard.blade.php` | لوحة التحكم | @extends('layouts.app') — إحصائيات, مخطط دائري, روابط سريعة, جدول آخر المواعيد |
| `appointments/index.blade.php` | المواعيد | @extends('layouts.app') — جدول المواعيد مع فلترة (حالة, تاريخ) |
| `doctors/index.blade.php` | الأطباء | @extends('layouts.app') — جدول الأطباء |
| `doctors/create.blade.php` | إضافة طبيب | @extends('layouts.app') — فورم |
| `doctors/edit.blade.php` | تعديل طبيب | @extends('layouts.app') — فورم |
| `doctors/show.blade.php` | تفاصيل طبيب | @extends('layouts.app') — عرض + قائمة مرضاه |
| `patients/index.blade.php` | المرضى | @extends('layouts.app') — جدول المرضى |
| `patients/create.blade.php` | إضافة مريض | @extends('layouts.app') — فورم (مع حقل patient_type, chronic fields toggle مع Alpine.js) |
| `patients/edit.blade.php` | تعديل مريض | @extends('layouts.app') — فورم |
| `patients/show.blade.php` | تفاصيل مريض | @extends('layouts.app') — عرض + سجل زيارات + أمراض مزمنة |

### 📂 resources/views/doctor/ — لوحة تحكم الطبيب

| الملف | الصفحة | الوصف |
|-------|--------|-------|
| `dashboard.blade.php` | لوحة التحكم | @extends('layouts.app') — إحصائيات + مخطط |
| `appointments/index.blade.php` | المواعيد | @extends('layouts.app') — جدول مواعيد الطبيب مع أزرار إجراءات |
| `appointments/create.blade.php` | حجز موعد | @extends('layouts.app') — فورم حجز موعد |
| `patients/index.blade.php` | مرضاي | @extends('layouts.app') — جدول مرضاي |
| `patients/show.blade.php` | سجل المريض | @extends('layouts.app') — بيانات + سجل زيارات + سجلات طبية |
| `medical-records/create.blade.php` | إضافة سجل طبي | @extends('layouts.app') — فورم لموعد محدد |
| `medical-records/create-for-patient.blade.php` | إضافة سجل لمريض | @extends('layouts.app') — فورم لمريض محدد |
| `schedule/index.blade.php` | جدول المواعيد | @extends('layouts.app') — جدول زمني |

### 📂 resources/views/patient/ — لوحة تحكم المريض

| الملف | الصفحة | الوصف |
|-------|--------|-------|
| `dashboard.blade.php` | لوحة التحكم | @extends('layouts.app') — إحصائيات + روابط سريعة |
| `appointments/index.blade.php` | مواعيدي | @extends('layouts.app') — جدول المواعيد مع قبول/رفض/إلغاء |
| `medical-records/index.blade.php` | السجلات الطبية | @extends('layouts.app') — شريط معلومات + حالة صحية + سجل الزيارات |
| `medical-records/print.blade.php` | طباعة السجلات | @extends('layouts.app') — صفحة طباعة (PDF) |
| `medical-reports/reports.blade.php` | التقارير الطبية | @extends('layouts.app') — رفع تقرير + قائمة التقارير |

### 📂 resources/views/notifications/ — الإشعارات

| الملف | الصفحة | الوصف |
|-------|--------|-------|
| `index.blade.php` | الإشعارات | @extends('layouts.app') — قائمة الإشعارات مع علامات قراءة/غير مقروء |

### 📂 resources/views/profile/ — الملف الشخصي

| الملف | الصفحة | الوصف |
|-------|--------|-------|
| `edit.blade.php` | تعديل الملف | @extends('layouts.app') — اسم + بريد + (للمريض: معلومات المريض) / (للدكتور: معلومات الطبيب) + تغيير كلمة السر |
| `partials/update-profile-information-form.blade.php` | | جزء تعديل المعلومات الشخصية |
| `partials/update-password-form.blade.php` | | جزء تغيير كلمة السر |
| `partials/delete-user-form.blade.php` | | جزء حذف الحساب |

### 📂 resources/views/auth/ — المصادقة

| الملف | الصفحة | الوصف |
|-------|--------|-------|
| `login.blade.php` | تسجيل الدخول | @extends('layouts.guest') |
| `register.blade.php` | إنشاء حساب | @extends('layouts.guest') — مع حقول patient_type + chronic + age calculation |
| `forgot-password.blade.php` | نسيت كلمة المرور | @extends('layouts.guest') |
| `reset-password.blade.php` | إعادة تعيين | @extends('layouts.guest') |
| `verify-email.blade.php` | تأكيد البريد | @extends('layouts.guest') |
| `confirm-password.blade.php` | تأكيد كلمة السر | @extends('layouts.guest') |

### 📂 resources/views/components/ — مكونات مشتركة

| الملف | الوصف |
|-------|--------|
| `application-logo.blade.php` | شعار التطبيق (SVG) |
| `input-label.blade.php` | label للفورم |
| `text-input.blade.php` | input نصي |
| `input-error.blade.php` | رسالة خطأ للفورم |
| `primary-button.blade.php` | زر أساسي |
| `secondary-button.blade.php` | زر ثانوي |
| `danger-button.blade.php` | زر خطر |
| `modal.blade.php` | نافذة منبثقة (مودال) |

---

## 🔄 مقارنة: الفرونت إند مقابل الباك إند

### ✅ مطابق تام (Frontend == Backend)
- **Auth** (login, register, forgot-password, reset-password, verify-email, confirm-password)
- **Admin** (dashboard, appointments, doctors CRUD, patients CRUD, notifications)
- **Doctor** (dashboard, appointments, appointments-create, patients, patient-show, medical-record-create, medical-record-add, profile, schedule, **calendar**) ✅
- **Patient** (dashboard, appointments, medical-records, medical-reports, profile, **print-medical-records**) ✅
- Profile (Admin, Doctor, Patient — كل واحد بملف منفصل) ✅
- About, Index ✅

### ✅ تم إنشاء الملفات المفقودة
| ملف الباك | ملف الفرونت المقابل | الحالة |
|-----------|---------------------|--------|
| `patient/medical-records/print.blade.php` | `patient/print-medical-records.html` | ✅ تم الإنشاء |

### 🔧 اختلافات أسلوبية بين الفرونت والباك
- الفرونت يستخدم **inline styles** بشكل أساسي، الباك يستخدم **Tailwind CSS** كلاسات
- الفرونت CSS في ملف `style.css` منفصل، الباك يستخدم Tailwind utility classes
- الفرونت ما فيه Alpine.js للـ toggle (مثل chronic fields) لأنه static
- الفرونت ما فيه `@auth` / `@guest` directives — كل دور له صفحته المستقلة

---

## 🐛 المشاكل التي واجهتنا

### 1. ✅ تحميل/طباعة PDF (patient/medical-records print)
- **المشكلة:** في الباك إند صفحة `patient/medical-records/print.blade.php` مع route للطباعة، لكن الفرونت إند ما عنده ملف مقابل
- **الحل:** تم إنشاء `frontend/patient/print-medical-records.html` بمحتوى نموذجي (sample data) مع زر طباعة يستخدم `window.print()`
- **الملاحظة:** الصفحة تستخدم بيانات ثابتة (static HTML) — النسخة الحقيقية بتجيب البيانات من Laravel

### 2. ~~تقويم الدكتور (doctor/calendar)~~ — تم الحذف بناءً على طلب المستخدم
- تم حذف `frontend/doctor/calendar.html` و `resources/views/doctor/calendar/index.blade.php`

### 3. ❌ Tailwind v4 مع Syntax v3
- **المشكلة:** ملف `resources/css/app.css` يستخدم syntax قديم (`@tailwind base; @tailwind components; @tailwind utilities;`) لكن المشروع عليه Tailwind v4
- **النتيجة:** `npm run build` يفشل بـ error: `Cannot apply unknown utility class`
- **الحل:** تحديث `app.css` لاستخدام `@import "tailwindcss"` (syntax v4)

### 4. ❌ Build Scripts مقفولة
- **المشكلة:** PowerShell Execution Policy بتمنع تشغيل npm scripts في Windows
- **الحل:** تشغيل `powershell -ExecutionPolicy Bypass` أو استخدام CMD

### 5. ⚠️ duplicate: medical-record-create.html و medical-record-add.html
- **الوصف:** ملفين شبه متطابقين في `frontend/doctor/`
- `medical-record-create.html` ← مرتبط بموعد محدد (زر رجوع لـ appointments)
- `medical-record-add.html` ← مرتبط بمريض محدد (زر رجوع لـ patient-show)
- في الباك إند: `create.blade.php` للموعد، `create-for-patient.blade.php` للمريض
- **متطابقين مع الباك ✅**

### 6. ⚠️ صفحة notifications موجودة للأدمن فقط
- الفرونت عنده `admin/notifications.html` فقط، لكن الباك إند notification page واحدة لجميع الأدوار
- روابط الإشعارات في صفحات الدكتور والمريض بتودي على `../admin/notifications.html`
- **حل مؤقت:** مقبول لأنها صفحة واحدة للجميع في الباك إند

### 7. ⚠️ حساب العمر (Register page)
- الفرونت عنده حقل تاريخ ميلاد ويحسب العمر تلقائياً باستخدام JavaScript
- الباك إند يعتمد على Laravel لحساب العمر
- تمت إضافة دالة حساب العمر في الفرونت ✅

### 8. ⚠️ Bio مخفية للتخصص "طب عام"
- الباك إند: `@if($profile->specialty != 'طب عام')` يخفي حقل النبذة
- الفرونت (صفحة الدكتور بروفايل): يضيف دالة `toggleBio()` تخفي/تظهر النبذة حسب التخصص ✅

---

## 📋 الملفات المفقودة التي تحتاج إنشاء

| الملف المطلوب | الأولوية | السبب |
|---------------|----------|-------|
| `frontend/patient/print-medical-records.html` | 🟢 منجزة ✅ | تم إنشاؤها — بيانات نموذجية للعرض والطباعة |

---

## 💡 تحسينات مقترحة

### 1. 🔴 إصلاح Build Tailwind
- تحديث `resources/css/app.css` من `@tailwind base` syntax القديم إلى `@import "tailwindcss"` (Tailwind v4)
- هذا يحل مشكلة البيلد ويخلي كلاسات Tailwind الجديدة (مثل `from-gray-400`) تشتغل

### 2. 🔴 إنشاء صفحة طباعة PDF
- إنشاء صفحة `patient/print-medical-records.html` تعرض نموذج سجل طبي قابل للطباعة
- أو استخدام مكتبة **jsPDF** + **html2canvas** لتصدير السجلات لـ PDF من المتصفح

### 3. 🟡 إنشاء صفحة تقويم الدكتور
- استخدام **FullCalendar** من CDN (`https://cdn.jsdelivr.net/npm/fullcalendar`)
- إضافة أحداث وهمية للعرض (static demo)

### 4. 🟡 توحيد مسارات CSS
- توحيد مصدر CSS: إما كل الصفحات تستخدم inline styles (زي الوضع الحالي) أو كلها تستخدم كلاسات من `style.css`
- المختلط (بعض الصفحات inline، بعضها كلاسات) بيصعب الصيانة

### 5. 🟡 إضافة صفحة 404
- إنشاء صفحة خطأ 404 (مفقودة) مخصصة

### 6. 🟢 Responsive Design
- تأكد من أن كل الصفحات متجاوبة مع الجوال (بعض الصفحات ممكن تحتاج تحسين)
- الباك إند يستخدم كلاسات Tailwind responsive (مثل `md:grid-cols-4`) — الفرونت إند يحتاج محاكاة يدوية

### 7. 🟢 تحسين الأداء
- ضغط الصور (logo.jpeg حجمه 32KB — ممكن تصغيره)
- إضافة lazy loading للصور
- إضافة cache headers

### 8. 🟢 إضافة Animations
- إضافة انتقالات بسيطة (hover effects, transitions) باستخدام CSS
- تحسين تجربة المستخدم في الأزرار والروابط

### 9. 🟢 إضافة Validator للفورم
- الفرونت حالياً ما عنده validation على الحقول (required, pattern, إلخ)
- الباك إند عنده validation على مستوى Laravel + error messages
- إضافة validation على الفرونت باستخدام HTML5 attributes أو JavaScript

### 10. 🟢 إضافة SweetAlert2
- بديل جيد لـ confirm() في عمليات الحذف
- يعطي مظهر أفضل من confirm العادي

---

## 🗺️ خريطة الروابط (Routing Map)

### الصفحات العامة
```
/                   → index.html          → welcome.blade.php
/about              → about.html          → about.blade.php
```

### Auth (المصادقة)
```
/login              → login.html          → auth/login.blade.php
/register           → register.html       → auth/register.blade.php
/forgot-password    → forgot-password.html → auth/forgot-password.blade.php
/reset-password     → reset-password.html → auth/reset-password.blade.php
/verify-email       → verify-email.html   → auth/verify-email.blade.php
/confirm-password   → confirm-password.html → auth/confirm-password.blade.php
```

### Admin (لوحة التحكم)
```
/admin/dashboard    → admin/dashboard.html         → admin/dashboard.blade.php
/admin/appointments → admin/appointments.html       → admin/appointments/index.blade.php
/admin/doctors      → admin/doctors.html            → admin/doctors/index.blade.php
/admin/doctors/create → admin/doctors-create.html   → admin/doctors/create.blade.php
/admin/doctors/{id} → admin/doctors-show.html       → admin/doctors/show.blade.php
/admin/doctors/{id}/edit → admin/doctors-edit.html  → admin/doctors/edit.blade.php
/admin/patients     → admin/patients.html           → admin/patients/index.blade.php
/admin/patients/create → admin/patients-create.html → admin/patients/create.blade.php
/admin/patients/{id} → admin/patients-show.html     → admin/patients/show.blade.php
/admin/patients/{id}/edit → admin/patients-edit.html → admin/patients/edit.blade.php
/notifications      → admin/notifications.html      → notifications/index.blade.php
```

### Doctor (لوحة تحكم الطبيب)
```
/doctor/dashboard   → doctor/dashboard.html         → doctor/dashboard.blade.php
/doctor/appointments → doctor/appointments.html      → doctor/appointments/index.blade.php
/doctor/appointments/create → doctor/appointments-create.html → doctor/appointments/create.blade.php
/doctor/patients    → doctor/patients.html           → doctor/patients/index.blade.php
/doctor/patients/{id} → doctor/patient-show.html     → doctor/patients/show.blade.php
/doctor/appointments/{id}/record → doctor/medical-record-create.html → doctor/medical-records/create.blade.php
/doctor/patients/{id}/history → doctor/medical-record-add.html → doctor/medical-records/create-for-patient.blade.php
/doctor/schedule    → doctor/schedule.html           → doctor/schedule/index.blade.php
/doctor/profile     → doctor/profile.html            → profile/edit.blade.php
```

### Patient (لوحة تحكم المريض)
```
/patient/dashboard   → patient/dashboard.html        → patient/dashboard.blade.php
/patient/appointments → patient/appointments.html     → patient/appointments/index.blade.php
/patient/medical-records → patient/medical-records.html → patient/medical-records/index.blade.php
/patient/medical-records/print → patient/print-medical-records.html ✅ → patient/medical-records/print.blade.php
/patient/reports     → patient/medical-reports.html   → patient/medical-reports/reports.blade.php
/patient/profile     → patient/profile.html           → profile/edit.blade.php
```

### Profile (الملف الشخصي)
```
/profile            → profile.html (admin)           → profile/edit.blade.php
                    → doctor/profile.html (doctor)
                    → patient/profile.html (patient)
```

---

## 🧩 إحصائيات المشروع

| الفئة | الفرونت إند | الباك إند |
|-------|-------------|-----------|
| عدد ملفات HTML/Blade | 35 | 47 |
| عدد ملفات CSS | 1 (style.css) | 0 (Tailwind) |
| عدد الصور | 2 | 0 |
| عدد Layouts | 0 (مضمن في HTML) | 2 (app, main) |
| عدد Controllers | — | 24 |
| عدد Routes (web) | — | 102 lines |
| عدد Routes (auth) | — | 59 lines |

---

## 📝 ملاحظات إضافية

- **التعامل مع الأدوار:** الفرونت يعتمد على ملفات منفصلة لكل دور (admin/, doctor/, patient/) بينما الباك يستخدم @auth و role checks داخل نفس الملف مع layouts مختلفة
- **الـ Layouts:** الفرونت يكرر الهيدر والفوتر في كل صفحة (بدون @extends)، الباك يستخدم `@extends('layouts.app')` أو `@extends('layouts.main')`
- **Alpine.js:** الباك يستخدم Alpine.js بكثافة (toggle القوائم، إشعارات، chronic disease fields)، الفرونت يستخدم Alpine.js بس لجرس الإشعارات
- **Chart.js:** كل من الفرونت والباك يستخدم Chart.js للمخططات الدائرية في لوحة التحكم
- **الاتجاه:** المشروع كامل RTL (من اليمين لليسار) بالعربية

---

> **تم إعداد هذه الخريطة بتاريخ:** 2026-06-11  
> **الهدف:** توثيق كامل للمشروع وتحديد الفجوات بين الفرونت إند والباك إند لتسهيل التطوير المستقبلي

---

## ⚙️ الدوال في مشروعنا

### 🟢 دوال JavaScript (الفرونت إند)

#### `toggleNav()`
**الموجودة في:** كل ملفات HTML (34 ملف)
**الوصف:** تفتح وتقفل القائمة الجانبية (hamburger menu) في الموبايل. تضيف/تزيل كلاس `open` من `#navMenu` وكلاس `active` من `.nav-overlay`.

#### `confirmAppointment(id)`
**الموجودة في:** `doctor/appointments.html`
**الوصف:** تظهر نافذة تأكيد الموعد. تحدد `action` الفورم عشان يرسل للـ route `/doctor/appointments/{id}/confirm`.

#### `closeModal()`
**الموجودة في:** `doctor/appointments.html`
**الوصف:** تقفل نافذة تأكيد الموعد.

#### `editAppointment(id)`
**الموجودة في:** `doctor/appointments.html`
**الوصف:** تظهر نافذة تعديل الموعد وتحدد `action` الفورم.

#### `closeEditModal()`
**الموجودة في:** `doctor/appointments.html`
**الوصف:** تقفل نافذة تعديل الموعد.

#### `completeAppointment(id)`
**الموجودة في:** `doctor/appointments.html`
**الوصف:** تخلي الدكتور يكمل الموعد. تعرض confirm() وإذا تأكدت تظهر alert.

#### `toggleBio()`
**الموجودة في:** `doctor/profile.html`
**الوصف:** تخفي أو تظهر حقل "نبذة عن الطبيب" حسب التخصص المختار. لو التخصص "طب عام" تخفيه، ولو أي تخصص ثاني تظهره.

#### `toggleRoleFields()`
**الموجودة في:** `register.html`
**الوصف:** تخفي أو تظهر حقول التسجيل حسب نوع المستخدم (مريض أو دكتور).

#### `toggleChronicDisease()`
**الموجودة في:** `register.html`
**الوصف:** تخفي أو تظهر حقل الأمراض المزمنة حسب اختيار "فئة المريض".

#### `calculateAge(birthDate)`
**الموجودة في:** `register.html`
**الوصف:** تحسب العمر من تاريخ الميلاد المدخل وتعرضه تلقائياً.

#### `toggleChronic()`
**الموجودة في:** `admin/patients-create.html`
**الوصف:** تخفي أو تظهر حقول الأمراض المزمنة حسب فئة المريض في صفحة إضافة مريض من الأدمن.

#### Chart.js (دوال مجهولة)
**الموجودة في:** `admin/dashboard.html`
- **المخطط الشريطي:** يعرض عدد المواعيد الشهرية (January–December)
- **المخطط الدائري:** يعرض توزيع فئات المرضى (أطفال، حوامل، أمراض مزمنة، أخرى)

#### Filter (مستمع حدث input)
**الموجودة في:** `doctor/appointments-create.html`
**الوصف:** فلترة حية لقائمة المرضى أثناء الكتابة في حقل البحث.

---

### 🔵 دوال PHP (الباك إند — Controllers)

#### AdminDashboardController
| الدالة | الوظيفة |
|--------|---------|
| `index()` | تجلب إحصائيات الأدمن (عدد الأطباء، المرضى، المواعيد بحالاتها)، المخططات، جدول المواعيد، آخر الأطباء |

#### AppointmentController
| الدالة | الوظيفة |
|--------|---------|
| `index(Request)` | تجيب كل المواعيد للأدمن (15 لكل صفحة) |
| `doctorIndex(Request)` | تجيب مواعيد الدكتور المسجل (مع فلترة وبحث) |
| `doctorCreate(Request)` | تظهر فورم حجز موعد (مع قائمة المرضى السابقين) |
| `doctorStore(Request)` | تحفظ موعد جديد من الدكتور وترسل إشعارات |
| `patientAppointments(Request)` | تجيب مواعيد المريض المسجل |
| `acceptAppointment(Appointment)` | المريض يقبل الموعد المعلق |
| `declineAppointment(Appointment)` | المريض يرفض الموعد المعلق |
| `cancelConfirmed(Appointment)` | المريض يلغي موعد مؤكد |
| `create(Request)` | تظهر فورم حجز موعد للمريض |
| `store(Request)` | تحفظ موعد من المريض |
| `confirm(Request, Appointment)` | الدكتور يؤكد الموعد (يتحقق من التعارضات) |
| `editDate(Request, Appointment)` | الدكتور يعدل تاريخ الموعد |
| `complete(Request, Appointment)` | الدكتور يكمل الموعد |
| `cancel(Request, Appointment)` | الدكتور يلغي الموعد |
| `addNotes(Request, Appointment)` | الدكتور يضيف ملاحظات على الموعد |
| `updateStatus(Request, Appointment)` | تحديث حالة الموعد مع إشعار |

#### DoctorController
| الدالة | الوظيفة |
|--------|---------|
| `index()` | تجيب كل الأطباء (مقسم صفحات) |
| `create()` | تظهر فورم إضافة طبيب |
| `store(Request)` | تنشئ طبيب + يوزر وترسل إشعار |
| `show(Doctor)` | تعرض تفاصيل طبيب مع مرضاه |
| `edit(Doctor)` | تظهر فورم تعديل طبيب |
| `update(Request, Doctor)` | تحديث بيانات الطبيب |
| `destroy(Doctor)` | حذف طبيب + اليوزر تاعه |

#### DoctorDashboardController
| الدالة | الوظيفة |
|--------|---------|
| `index()` | تجيب إحصائيات الدكتور (مواعيد اليوم، المعلقة، المؤكدة، المكتملة) |
| `calendar()` | تعرض التقويم (FullCalendar) مع المواعيد ملونة حسب الحالة |

#### DoctorScheduleController
| الدالة | الوظيفة |
|--------|---------|
| `index()` | تجيب جدول الدكتور الأسبوعي |
| `store(Request)` | تنشئ جدول دوام جديد |
| `update(Request, DoctorSchedule)` | تعدل الجدول |
| `destroy(DoctorSchedule)` | تحذف الجدول |
| `getAvailableDoctors(Request)` | (AJAX) تجيب الأطباء النشطين في يوم معين |

#### MedicalRecordController
| الدالة | الوظيفة |
|--------|---------|
| `create(Appointment)` | تظهر فورم إضافة سجل طبي لموعد |
| `store(Request, Appointment)` | تحفظ السجل الطبي وتكمل الموعد |
| `show(MedicalRecord)` | تعرض سجل طبي واحد |
| `patientHistory(patientId)` | تجيب كل سجلات مريض للدكتور |
| `createForPatient(Patient)` | فورم إضافة سجل لمريض بدون موعد |
| `storeForPatient(Request, Patient)` | تحفظ سجل لمريض بدون موعد |
| `patientIndex()` | تجيب سجلات المريض المسجل |
| `printView()` | صفحة طباعة السجلات الطبية (PDF) |

#### MedicalReportController
| الدالة | الوظيفة |
|--------|---------|
| `patientIndex()` | تجيب تقارير المريض المسجل |
| `upload(Request)` | ترفع ملف تقرير (PDF, JPG, PNG, DOC — حد 10MB) |
| `download(MedicalReport)` | المريض يحمل تقريره |
| `doctorDownload(MedicalReport)` | الدكتور يحمل تقرير مريضه |
| `destroy(MedicalReport)` | المريض يحذف تقريره |

#### NotificationController
| الدالة | الوظيفة |
|--------|---------|
| `index()` | تجيب إشعارات المستخدم (20 لكل صفحة) |
| `markAsRead(id)` | تعلم إشعار كمقروء |
| `markAllAsRead()` | تعلم كل الإشعارات كمقروءة |

#### PatientController
| الدالة | الوظيفة |
|--------|---------|
| `index(Request)` | تجيب المرضى (مع بحث وفلترة جنس) |
| `create()` | فورم إضافة مريض |
| `store(Request)` | تنشئ مريض + يوزر بكل الحقول الطبية |
| `show(Patient)` | تعرض مريض مع مواعيده وأطبائه |
| `edit(Patient)` | فورم تعديل مريض |
| `update(Request, Patient)` | تحديث بيانات المريض |
| `destroy(Patient)` | حذف مريض + اليوزر |

#### PatientDashboardController
| الدالة | الوظيفة |
|--------|---------|
| `index()` | تجيب إحصائيات المريض (مواعيد معلقة/مؤكدة/مكتملة) + آخر المواعيد |

#### PatientRecordController
| الدالة | الوظيفة |
|--------|---------|
| `show(Patient)` | تعرض سجل مريض للدكتور (مواعيد، آخر موعد، تقارير) |
| `index(Request)` | تجيب مرضى الدكتور (مع بحث) |

#### ProfileController
| الدالة | الوظيفة |
|--------|---------|
| `edit(Request)` | تظهر فورم تعديل الملف الشخصي (بيانات مريض أو دكتور حسب الـ role) |
| `update(Request)` | تحديث الاسم والبريد والبروفايل حسب الدور |
| `destroy(Request)` | حذف الحساب (بعد تأكيد كلمة السر) |
| `updatePassword(Request)` | تغيير كلمة السر |

#### ReportController
| الدالة | الوظيفة |
|--------|---------|
| `appointments(Request)` | تقارير المواعيد (فلترة حسب الحالة، الدكتور، التاريخ) |
| `patients(Request)` | تقارير المرضى (فلترة حسب الجنس وفصيلة الدم) |
| `doctors(Request)` | تقارير الأطباء (فلترة حسب التخصص والحالة) |

#### SettingsController
| الدالة | الوظيفة |
|--------|---------|
| `index()` | صفحة إعدادات الأدمن |
| `update(Request)` | تحديث إعدادات العيادة (الاسم، الهاتف، مدة الموعد، ساعات الإلغاء) — يكتب في `.env` |

#### دوال Auth (المصادقة)
| الـ Controller | الدوال | الوظيفة |
|---------------|--------|---------|
| `AuthenticatedSessionController` | `create()`, `store()`, `destroy()` | تسجيل الدخول والخروج |
| `ConfirmablePasswordController` | `show()`, `store()` | تأكيد كلمة السر |
| `EmailVerificationNotificationController` | `store()` | إعادة إرسال رابط التوثيق |
| `EmailVerificationPromptController` | `__invoke()` | عرض صفحة توثيق البريد أو تحويل للـ dashboard |
| `NewPasswordController` | `create()`, `store()` | إعادة تعيين كلمة السر (باستخدام token) |
| `PasswordController` | `update()` | تحديث كلمة السر للمستخدم المسجل |
| `PasswordResetLinkController` | `create()`, `store()` | إرسال رابط إعادة تعيين كلمة السر |
| `RegisteredUserController` | `create()`, `store()` | تسجيل حساب جديد (مريض أو دكتور) مع إرسال إشعار |
| `VerifyEmailController` | `__invoke()` | توثيق البريد الإلكتروني عبر الرابط الموقع |

---

### 🧠 ملخص عدد الدوال

| النوع | العدد |
|-------|-------|
| دوال JavaScript (مسماة) | **12** دالة |
| دوال JavaScript (مجهولة - event listeners) | **3** |
| دوال JavaScript (Chart.js) | **2** |
| دوال PHP (public methods) | **75+** دالة |
| **المجموع الكلي** | **~92+** دالة
