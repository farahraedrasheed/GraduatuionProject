# 🏥 Clinic Management System — Backend Development Roadmap
## For Backend Developers (Laravel MVC)

---

## 📋 Executive Summary

**نظام إدارة المركز الطبي** هو تطبيق ويب متكامل مبني على **Laravel 13** يربط بين ثلاثة أدوار رئيسية:
- 🛡️ **أدمن** — إدارة شاملة للنظام
- 👨‍⚕️ **طبيب** — إدارة المواعيد والمرضى والسجلات الطبية
- 🧑‍🦽 **مريض** — متابعة المواعيد والسجلات الطبية

هذا الملف يوثّق:

1. ✅ **البنية الحالية للمشروع** — ما تم بناؤه
2. 📊 **نماذج البيانات (Models)** — تصميم قاعدة البيانات
3. 🔌 **المسارات (Routes)** — جميع نقاط النهاية
4. 🔐 **المصادقة والصلاحيات** — نظام الأدوار
5. 🚀 **مراحل التنفيذ** — ما تم وما هو مخطط

---

## Part 1️⃣: البنية الحالية للمشروع

### 1.1 هيكل المشروع

```
clinic-app/
├── app/
│   ├── Console/Commands/
│   │   └── SendAppointmentReminders.php    # Scheduler يومي للتذكيرات
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Auth/                       # Authentication controllers
│   │   │   ├── AdminDashboardController.php
│   │   │   ├── AppointmentController.php
│   │   │   ├── DoctorController.php
│   │   │   ├── DoctorDashboardController.php
│   │   │   ├── DoctorScheduleController.php
│   │   │   ├── MedicalRecordController.php
│   │   │   ├── MedicalReportController.php
│   │   │   ├── NotificationController.php
│   │   │   ├── PatientController.php
│   │   │   ├── PatientDashboardController.php
│   │   │   ├── PatientRecordController.php
│   │   │   ├── ProfileController.php
│   │   │   └── Controller.php              # Base controller with authUser()
│   │   └── Middleware/
│   │       └── RoleMiddleware.php          # Role-based access control
│   ├── Models/
│   │   ├── User.php
│   │   ├── Doctor.php
│   │   ├── Patient.php
│   │   ├── Appointment.php
│   │   ├── AppointmentReminder.php
│   │   ├── DoctorSchedule.php
│   │   ├── MedicalRecord.php
│   │   └── MedicalReport.php
│   └── Notifications/
│       ├── AppointmentStatusNotification.php
│       ├── AppointmentReminderNotification.php
│       └── NewDoctorRegisteredNotification.php
├── database/
│   ├── migrations/                         # 21 migration file
│   └── seeders/
│       └── UserSeeder.php                  # بيانات تجريبية
├── routes/
│   ├── web.php                             # المسارات الرئيسية
│   ├── auth.php                            # مسارات المصادقة
│   └── console.php                         # المهام المجدولة
└── resources/views/                        # Blade templates
    ├── admin/
    ├── doctor/
    ├── patient/
    ├── notifications/
    └── layouts/
```

### 1.2 Stack التقني المستخدم

| التقنية | الإصدار | الاستخدام |
|---|---|---|
| **Laravel** | 13.x | إطار العمل الخلفي |
| **PHP** | 8.2+ | لغة البرمجة |
| **MySQL** | 8.0+ | قاعدة البيانات |
| **Tailwind CSS** | 4.x | التصميم |
| **Alpine.js** | 3.x | التفاعلية |
| **Chart.js** | 4.x | الرسوم البيانية |
| **FullCalendar** | 6.x | تقويم المواعيد |
| **Blade** | - | محرك القوالب |

---

## Part 2️⃣: نماذج البيانات (Data Models)

### 2.1 User Model

```php
// app/Models/User.php
// الجدول: users
{
  "id": 1,
  "name": "أحمد محمد",
  "email": "ahmed@clinic.com",
  "password": "hashed_password",
  "role": "doctor",          // admin | doctor | patient
  "email_verified_at": null,
  "created_at": "2026-01-15T10:30:00Z",
  "updated_at": "2026-01-15T10:30:00Z"
}
```

**حقول قاعدة البيانات:**
- `id` — PK, auto-increment
- `name` — string(255)
- `email` — unique, string(255)
- `password` — hashed (bcrypt)
- `role` — enum: `admin`, `doctor`, `patient`
- `email_verified_at` — timestamp, nullable
- `remember_token` — string(100), nullable
- `created_at`, `updated_at` — timestamps

**العلاقات:**
```php
User → hasOne(Doctor)    // إذا role = doctor
User → hasOne(Patient)   // إذا role = patient
User → Notifiable        // إشعارات قاعدة البيانات
```

---

### 2.2 Doctor Model

```php
// app/Models/Doctor.php
// الجدول: doctors
{
  "id": 1,
  "user_id": 2,
  "specialty": "طب الأطفال",
  "license_number": "DOC123",
  "bio": "طبيب متخصص بخبرة 10 سنوات",
  "is_active": true,
  "created_at": "2026-01-15T10:30:00Z",
  "updated_at": "2026-01-15T10:30:00Z"
}
```

**حقول قاعدة البيانات:**
- `id` — PK, auto-increment
- `user_id` — FK → users, cascade delete
- `specialty` — string(255)
- `license_number` — string(255), unique
- `bio` — text, nullable
- `is_active` — boolean, default: true
- `created_at`, `updated_at` — timestamps

**العلاقات:**
```php
Doctor → belongsTo(User)
Doctor → hasMany(Appointment)
Doctor → hasMany(DoctorSchedule)
Doctor → belongsToMany(Patient, via appointments)
```

---

### 2.3 Patient Model

```php
// app/Models/Patient.php
// الجدول: patients
{
  "id": 1,
  "user_id": 3,
  "phone": "0591234567",
  "date_of_birth": "1990-05-15",
  "age": 35,                         // computed attribute
  "gender": "male",                  // male | female
  "blood_type": "A+",
  "address": "رام الله، شارع المدينة",
  "medical_history": "لا يوجد",
  "patient_type": "chronic_disease", // child | pregnant | chronic_disease | other
  "chronic_disease_type": "سكري",
  "chronic_disease_type2": null,
  "created_at": "2026-01-15T10:30:00Z"
}
```

**حقول قاعدة البيانات:**
- `id` — PK, auto-increment
- `user_id` — FK → users, cascade delete
- `phone` — string(20)
- `date_of_birth` — date
- `age` — integer, nullable
- `gender` — enum: `male`, `female`
- `blood_type` — string(10), nullable
- `address` — text, nullable
- `medical_history` — text, nullable
- `patient_type` — enum: `child`, `pregnant`, `chronic_disease`, `other`
- `chronic_disease_type` — string, nullable
- `chronic_disease_type2` — string, nullable
- `created_at`, `updated_at` — timestamps

**العلاقات:**
```php
Patient → belongsTo(User)
Patient → hasMany(Appointment)
Patient → hasMany(MedicalReport)
```

---

### 2.4 Appointment Model

```php
// app/Models/Appointment.php
// الجدول: appointments
{
  "id": 1,
  "patient_id": 1,
  "doctor_id": 1,
  "appointment_date": "2026-06-15T10:00:00Z",
  "requested_date": "2026-06-15T10:00:00Z",
  "status": "confirmed",   // pending | confirmed | completed | cancelled
  "notes": "مريض يشكو من ألم في الصدر",
  "disease_type": "أمراض القلب",
  "service_category": "كشف",
  "patient_description": "وصف المريض لحالته",
  "medicine_name": "أسبرين",
  "doctor_notes": "ملاحظات الطبيب",
  "created_at": "2026-06-10T08:00:00Z"
}
```

**حقول قاعدة البيانات:**
- `id` — PK, auto-increment
- `patient_id` — FK → patients, cascade delete
- `doctor_id` — FK → doctors, cascade delete
- `appointment_date` — datetime
- `requested_date` — datetime, nullable
- `status` — enum: `pending`, `confirmed`, `completed`, `cancelled`, default: `pending`
- `notes` — text, nullable
- `disease_type` — string, nullable
- `service_category` — string, nullable
- `patient_description` — text, nullable
- `medicine_name` — string, nullable
- `doctor_notes` — text, nullable
- `created_at`, `updated_at` — timestamps

**العلاقات:**
```php
Appointment → belongsTo(Patient)
Appointment → belongsTo(Doctor)
Appointment → hasOne(MedicalRecord)
Appointment → hasMany(MedicalRecord)
```

---

### 2.5 DoctorSchedule Model

```php
// app/Models/DoctorSchedule.php
// الجدول: doctor_schedules
{
  "id": 1,
  "doctor_id": 1,
  "day": "sunday",    // saturday | sunday | monday | tuesday | wednesday | thursday | friday
  "start_time": "08:00:00",
  "end_time": "14:00:00",
  "is_active": true,
  "created_at": "2026-01-15T10:30:00Z"
}
```

**حقول قاعدة البيانات:**
- `id` — PK, auto-increment
- `doctor_id` — FK → doctors, cascade delete
- `day` — enum: `saturday`, `sunday`, `monday`, `tuesday`, `wednesday`, `thursday`, `friday`
- `start_time` — time
- `end_time` — time
- `is_active` — boolean, default: true
- `created_at`, `updated_at` — timestamps

---

### 2.6 MedicalRecord Model

```php
// app/Models/MedicalRecord.php
// الجدول: medical_records
{
  "id": 1,
  "appointment_id": 1,   // nullable
  "doctor_id": 1,
  "patient_id": 1,
  "diagnosis": "التهاب في الحلق",
  "prescription": "أموكسيسيلين 500mg",
  "notes": "الراحة التامة لمدة أسبوع",
  "created_at": "2026-06-15T11:00:00Z"
}
```

**حقول قاعدة البيانات:**
- `id` — PK, auto-increment
- `appointment_id` — FK → appointments, nullable, cascade delete
- `doctor_id` — FK → doctors, cascade delete
- `patient_id` — FK → patients, cascade delete
- `diagnosis` — text, nullable
- `prescription` — text, nullable
- `notes` — text, nullable
- `created_at`, `updated_at` — timestamps

---

### 2.7 MedicalReport Model

```php
// app/Models/MedicalReport.php
// الجدول: medical_reports
{
  "id": 1,
  "patient_id": 1,
  "file_path": "medical-reports/1/report.pdf",
  "original_name": "تحليل_الدم.pdf",
  "description": "نتائج تحليل الدم الشهري",
  "created_at": "2026-06-10T09:00:00Z"
}
```

**حقول قاعدة البيانات:**
- `id` — PK, auto-increment
- `patient_id` — FK → patients, cascade delete
- `file_path` — string(500) — المسار في storage/app/public
- `original_name` — string(255)
- `description` — text, nullable
- `created_at`, `updated_at` — timestamps

---

### 2.8 AppointmentReminder Model

```php
// app/Models/AppointmentReminder.php
// الجدول: appointment_reminders
{
  "id": 1,
  "appointment_id": 1,
  "sent_at": "2026-06-14T09:00:00Z",
  "created_at": "2026-06-14T09:00:00Z"
}
```

**الغرض:** منع إرسال تذكير مكرر لنفس الموعد عند تشغيل الـ Scheduler.

---

### 2.9 Notifications (Laravel Built-in)

```php
// الجدول: notifications (Laravel default)
{
  "id": "uuid",
  "type": "App\\Notifications\\AppointmentStatusNotification",
  "notifiable_type": "App\\Models\\User",
  "notifiable_id": 3,
  "data": {
    "title": "إشعار بالموعد",
    "message": "تم تأكيد موعدك مع د. محمد",
    "appointment_id": 1
  },
  "read_at": null,
  "created_at": "2026-06-10T10:00:00Z"
}
```

---

## Part 3️⃣: المسارات (Routes)

### 3.1 مسارات المصادقة — `routes/auth.php`

| الميثود | المسار | الوظيفة |
|---|---|---|
| `GET` | `/register` | عرض صفحة التسجيل |
| `POST` | `/register` | تسجيل مستخدم جديد |
| `GET` | `/login` | عرض صفحة الدخول |
| `POST` | `/login` | تسجيل الدخول |
| `POST` | `/logout` | تسجيل الخروج |
| `GET` | `/forgot-password` | صفحة نسيت كلمة السر |
| `POST` | `/forgot-password` | إرسال رابط الاسترداد |
| `GET` | `/reset-password/{token}` | صفحة تغيير كلمة السر |
| `POST` | `/reset-password` | تغيير كلمة السر |

---

### 3.2 مسارات الأدمن — middleware: `auth`, `role:admin`

| الميثود | المسار | Controller | الوظيفة |
|---|---|---|---|
| `GET` | `/admin/dashboard` | AdminDashboardController@index | لوحة التحكم |
| `GET` | `/admin/appointments` | AppointmentController@index | جميع المواعيد |
| `GET` | `/doctors` | DoctorController@index | قائمة الأطباء |
| `GET` | `/doctors/create` | DoctorController@create | صفحة إضافة طبيب |
| `POST` | `/doctors` | DoctorController@store | حفظ طبيب جديد |
| `GET` | `/doctors/{id}` | DoctorController@show | تفاصيل طبيب |
| `GET` | `/doctors/{id}/edit` | DoctorController@edit | تعديل طبيب |
| `PATCH` | `/doctors/{id}` | DoctorController@update | حفظ التعديل |
| `DELETE` | `/doctors/{id}` | DoctorController@destroy | حذف طبيب |
| `GET` | `/patients` | PatientController@index | قائمة المرضى |
| `GET` | `/patients/create` | PatientController@create | صفحة إضافة مريض |
| `POST` | `/patients` | PatientController@store | حفظ مريض جديد |
| `GET` | `/patients/{id}` | PatientController@show | تفاصيل مريض |
| `GET` | `/patients/{id}/edit` | PatientController@edit | تعديل مريض |
| `PATCH` | `/patients/{id}` | PatientController@update | حفظ التعديل |
| `DELETE` | `/patients/{id}` | PatientController@destroy | حذف مريض |

---

### 3.3 مسارات الطبيب — middleware: `auth`, `role:doctor`

| الميثود | المسار | Controller | الوظيفة |
|---|---|---|---|
| `GET` | `/doctor/dashboard` | DoctorDashboardController@index | لوحة التحكم |
| `GET` | `/doctor/calendar` | DoctorDashboardController@calendar | تقويم المواعيد |
| `GET` | `/doctor/appointments` | AppointmentController@doctorIndex | مواعيد الطبيب |
| `GET` | `/doctor/appointments/create` | AppointmentController@doctorCreate | إنشاء موعد |
| `POST` | `/doctor/appointments` | AppointmentController@doctorStore | حفظ الموعد |
| `PATCH` | `/doctor/appointments/{id}/confirm` | AppointmentController@confirm | تأكيد الموعد |
| `PATCH` | `/doctor/appointments/{id}/complete` | AppointmentController@complete | إكمال الموعد |
| `PATCH` | `/doctor/appointments/{id}/cancel` | AppointmentController@cancel | إلغاء الموعد |
| `POST` | `/doctor/appointments/{id}/notes` | AppointmentController@addNotes | إضافة ملاحظات |
| `GET` | `/doctor/patients` | PatientRecordController@index | مرضى الطبيب |
| `GET` | `/doctor/patient/{id}` | PatientRecordController@show | ملف المريض |
| `GET` | `/doctor/schedule` | DoctorScheduleController@index | جدول العمل |
| `POST` | `/doctor/schedule` | DoctorScheduleController@store | إضافة يوم عمل |
| `PATCH` | `/doctor/schedule/{id}` | DoctorScheduleController@update | تعديل يوم عمل |
| `DELETE` | `/doctor/schedule/{id}` | DoctorScheduleController@destroy | حذف يوم عمل |
| `GET` | `/doctor/appointments/{id}/record` | MedicalRecordController@create | إنشاء سجل طبي |
| `POST` | `/doctor/appointments/{id}/record` | MedicalRecordController@store | حفظ السجل |
| `GET` | `/doctor/patients/{id}/history` | MedicalRecordController@patientHistory | سجلات المريض |
| `GET` | `/doctor/patient/report/{id}/download` | MedicalReportController@doctorDownload | تحميل تقرير |

---

### 3.4 مسارات المريض — middleware: `auth`, `role:patient`

| الميثود | المسار | Controller | الوظيفة |
|---|---|---|---|
| `GET` | `/patient/dashboard` | PatientDashboardController@index | لوحة التحكم |
| `GET` | `/patient/appointments` | AppointmentController@patientAppointments | مواعيد المريض |
| `PATCH` | `/patient/appointments/{id}/accept` | AppointmentController@acceptAppointment | قبول الموعد |
| `PATCH` | `/patient/appointments/{id}/decline` | AppointmentController@declineAppointment | رفض الموعد |
| `GET` | `/patient/medical-records` | MedicalRecordController@patientIndex | سجلاته الطبية |
| `GET` | `/patient/medical-records/print` | MedicalRecordController@printView | طباعة PDF |
| `GET` | `/patient/reports` | MedicalReportController@patientIndex | تقاريره |
| `POST` | `/patient/reports/upload` | MedicalReportController@upload | رفع تقرير |
| `GET` | `/patient/reports/{id}/download` | MedicalReportController@download | تحميل تقرير |
| `DELETE` | `/patient/reports/{id}` | MedicalReportController@destroy | حذف تقرير |

---

### 3.5 مسارات مشتركة — middleware: `auth`

| الميثود | المسار | Controller | الوظيفة |
|---|---|---|---|
| `GET` | `/notifications` | NotificationController@index | صفحة الإشعارات |
| `POST` | `/notifications/read-all` | NotificationController@markAllAsRead | تعليم الكل مقروء |
| `POST` | `/notifications/{id}/read` | NotificationController@markAsRead | تعليم واحد مقروء |
| `GET` | `/profile` | ProfileController@edit | تعديل الملف الشخصي |
| `PATCH` | `/profile` | ProfileController@update | حفظ التعديلات |
| `PATCH` | `/profile/password` | ProfileController@updatePassword | تغيير كلمة السر |
| `DELETE` | `/profile` | ProfileController@destroy | حذف الحساب |

---

## Part 4️⃣: المصادقة والصلاحيات

### 4.1 نظام المصادقة

يستخدم النظام **Laravel Breeze** مع جلسات (Sessions) بدلاً من JWT:

```php
// نظام الجلسات — Session-based Authentication
// المستخدم يسجل الدخول → يُحفظ في الجلسة → يُرفق مع كل طلب تلقائياً
```

### 4.2 RoleMiddleware — الصلاحيات بالأدوار

```php
// app/Http/Middleware/RoleMiddleware.php
// الاستخدام في routes:
Route::middleware(['auth', 'role:admin'])->group(...)
Route::middleware(['auth', 'role:doctor'])->group(...)
Route::middleware(['auth', 'role:patient'])->group(...)
```

### 4.3 جدول الصلاحيات

| المسار | أدمن | طبيب | مريض | زائر |
|---|:---:|:---:|:---:|:---:|
| `/admin/dashboard` | ✅ | ❌ | ❌ | ❌ |
| `/admin/appointments` | ✅ | ❌ | ❌ | ❌ |
| `/doctors/*` (CRUD) | ✅ | ❌ | ❌ | ❌ |
| `/patients/*` (CRUD) | ✅ | ❌ | ❌ | ❌ |
| `/doctor/dashboard` | ❌ | ✅ | ❌ | ❌ |
| `/doctor/appointments` | ❌ | ✅ | ❌ | ❌ |
| `/doctor/schedule` | ❌ | ✅ | ❌ | ❌ |
| `/doctor/patients` | ❌ | ✅ | ❌ | ❌ |
| `/patient/dashboard` | ❌ | ❌ | ✅ | ❌ |
| `/patient/appointments` | ❌ | ❌ | ✅ | ❌ |
| `/patient/medical-records` | ❌ | ❌ | ✅ | ❌ |
| `/notifications` | ✅ | ✅ | ✅ | ❌ |
| `/profile` | ✅ | ✅ | ✅ | ❌ |
| `/` `/about` `/join` | ✅ | ✅ | ✅ | ✅ |

---

## Part 5️⃣: نظام الإشعارات

### 5.1 أنواع الإشعارات

| الإشعار | Class | يُرسل عند | يُرسل لـ |
|---|---|---|---|
| تأكيد الموعد | `AppointmentStatusNotification` | تأكيد الموعد | المريض |
| إكمال الموعد | `AppointmentStatusNotification` | إكمال الموعد | المريض |
| إلغاء الموعد | `AppointmentStatusNotification` | إلغاء الموعد | المريض |
| تذكير بالموعد | `AppointmentReminderNotification` | يومياً الساعة 9:00 | المريض |
| طبيب جديد | `NewDoctorRegisteredNotification` | تسجيل طبيب | الأدمن |

### 5.2 تحسين ألوان الإشعارات (2026-06-18)

- **مُلوّنة حسب النوع** في `notifications/index.blade.php`:
  - **أخضر** ← حجز/موعد جديد (`notif-type-booked`)
  - **أزرق** ← تأكيد (`notif-type-confirmed`)
  - **نبيتي** ← إكمال (`notif-type-completed`)
  - **أحمر** ← إلغاء/رفض (`notif-type-cancelled`)
  - **عنبري** ← تذكير (`notif-type-reminder`)
  - **بنفسجي** ← تسجيل جديد (`notif-type-register`)
- ايقونات مختلفة حسب نوع الإشعار (SVG)
- كلاسات CSS مشتركة في `public/css/common.css`
- تحديث Dropdown الإشعارات في `layouts/app.blade.php`: آخر 5 إشعارات فقط + كلاسات CSS

### 5.3 Scheduler التلقائي

```php
// routes/console.php
Schedule::command('appointments:send-reminders')->dailyAt('09:00');

// app/Console/Commands/SendAppointmentReminders.php
// يبحث عن مواعيد مؤكدة لليوم التالي
// يتحقق من AppointmentReminder لمنع التكرار
// يرسل AppointmentReminderNotification للمريض
// يسجل في appointment_reminders
```

---

## Part 6️⃣: منطق الأعمال الأساسي (Business Logic)

### 6.1 فحص تعارض المواعيد

```php
// AppointmentController::confirm()
// يُرفض الموعد إذا وُجد موعد مؤكد آخر للطبيب خلال ساعة
$conflict = Appointment::where('doctor_id', $doctor->id)
    ->where('id', '!=', $appointment->id)
    ->where('status', 'confirmed')
    ->whereBetween('appointment_date', [
        $appointmentDate->copy()->subHour(),
        $appointmentDate->copy()->addHour(),
    ])->first();
```

### 6.2 التحقق من جدول الدوام

```php
// AppointmentController::confirm()
// يُرفض الموعد إذا كان الوقت خارج ساعات عمل الطبيب
$dayOfWeek = $dayMap[$appointmentDate->dayOfWeek];
$schedule = DoctorSchedule::where('doctor_id', $doctor->id)
    ->where('day', $dayOfWeek)
    ->where('is_active', true)
    ->first();
// مقارنة الوقت بـ start_time و end_time
```

### 6.3 تحميل الملفات

```php
// MedicalReportController::upload()
$filePath = $file->store('medical-reports/' . $patient->id, 'public');
// يُحفظ في: storage/app/public/medical-reports/{patient_id}/

// MedicalReportController::download()
$path = Storage::disk('public')->path($report->file_path);
return response()->download($path, $report->original_name);
```

---

## Part 7️⃣: إعداد البيئة

### 7.1 متطلبات التشغيل

```bash
# المتطلبات
PHP >= 8.2
MySQL >= 8.0
Composer
Node.js >= 18
npm
```

### 7.2 خطوات التثبيت

```bash
# 1. استنساخ المشروع
git clone https://github.com/farahraedrasheed/GraduatuionProject.git
cd GraduatuionProject

# 2. تثبيت الاعتماديات
composer install
npm install && npm run build

# 3. إعداد البيئة
copy .env.example .env
php artisan key:generate
```

### 7.3 إعداد ملف `.env`

```env
APP_NAME="المركز الطبي"
APP_ENV=local
APP_KEY=base64:xxxxx
APP_DEBUG=true
APP_URL=http://localhost:8000
APP_LOCALE=ar

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=clinic
DB_USERNAME=root
DB_PASSWORD=

FILESYSTEM_DISK=public
SESSION_DRIVER=database
QUEUE_CONNECTION=database
```

### 7.4 تشغيل قاعدة البيانات

```bash
# تشغيل الـ Migrations
php artisan migrate --seed

# ربط مجلد التخزين
php artisan storage:link

# تشغيل الخادم
php artisan serve
```

### 7.5 بيانات تجريبية (من UserSeeder)

| الدور | البريد الإلكتروني | كلمة السر |
|---|---|---|
| أدمن | admin@clinic.com | admin123 |
| طبيب | doctor@clinic.com | doctor123 |
| مريض | patient@clinic.com | patient123 |

---

## Part 8️⃣: مراحل التنفيذ

### ✅ المرحلة الأولى — البنية الأساسية (مكتملة)

- [x] إعداد مشروع Laravel 13
- [x] نظام المصادقة الكامل (Breeze)
- [x] RoleMiddleware للأدوار الثلاثة
- [x] توجيه تلقائي حسب الدور
- [x] نموذج User مع role field
- [x] إدارة الملف الشخصي (تعديل، تغيير كلمة السر، حذف الحساب)

---

### ✅ المرحلة الثانية — إدارة الأطباء والمرضى (مكتملة)

- [x] Doctor CRUD كامل (إضافة، تعديل، حذف، عرض)
- [x] Patient CRUD كامل من قِبل الأدمن
- [x] تسجيل طبيب/مريض من صفحة التسجيل
- [x] Pagination + بحث + فلترة في جميع القوائم
- [x] صفحة تفاصيل لكل طبيب ومريض

---

### ✅ المرحلة الثالثة — نظام المواعيد (مكتملة)

- [x] الطبيب يُنشئ مواعيد للمرضى
- [x] تأكيد / إكمال / إلغاء المواعيد
- [x] **فحص التعارض** — رفض موعد إذا كان خلال ساعة من موعد آخر
- [x] **التحقق من جدول الدوام** — رفض موعد خارج أوقات العمل
- [x] المريض يقبل أو يرفض الموعد
- [x] إضافة ملاحظات على الموعد
- [x] إدارة جدول عمل الطبيب (أيام وأوقات)
- [x] تقويم تفاعلي للطبيب (FullCalendar)

---

### ✅ المرحلة الرابعة — السجلات والتقارير الطبية (مكتملة)

- [x] الطبيب يُنشئ سجلات طبية (diagnosis, prescription, notes)
- [x] عرض سجلات كل مريض
- [x] طباعة / تصدير PDF للسجلات
- [x] المريض يرفع تقارير طبية (PDF، صور، مستندات)
- [x] تحميل وحذف التقارير
- [x] الطبيب يطّلع على تقارير مرضاه

---

### ✅ المرحلة الخامسة — الإشعارات (مكتملة)

- [x] إشعار للمريض عند تأكيد/إكمال/إلغاء الموعد
- [x] تذكير تلقائي يومياً الساعة 9 صباحاً
- [x] إشعار للأدمن عند تسجيل طبيب جديد
- [x] مركز إشعارات (جرس Bell) في الشريط العلوي
- [x] صفحة كاملة لعرض جميع الإشعارات
- [x] تعليم مقروء (فردي وجماعي)

---

### ✅ المرحلة السادسة — لوحات التحكم والتحليلات (مكتملة)

- [x] لوحة أدمن: إحصائيات شاملة + Chart.js (bar + donut)
- [x] جدول أفضل الأطباء نشاطاً
- [x] لوحة طبيب: ملخص يومي + إحصائيات
- [x] لوحة مريض: آخر المواعيد والسجلات

---

### 🔲 المرحلة السابعة — تحسينات مستقبلية (مقترحة)

- [ ] إشعارات بريد إلكتروني (Email Notifications via SMTP)
- [ ] إشعار للأدمن عند تسجيل مريض جديد
- [ ] نظام تقييم الأطباء من المرضى
- [ ] إدارة الوصفات الطبية والأدوية بشكل منفصل
- [ ] نظام فواتير ومدفوعات
- [ ] تصدير التقارير والإحصائيات (Excel/PDF)
- [ ] إشعارات فورية Real-time (Laravel Echo + Pusher)
- [ ] API RESTful لدعم تطبيق جوال مستقبلاً

---

## Part 9️⃣: الأمان (Security)

| الإجراء | التطبيق في المشروع |
|---|---|
| تشفير كلمات السر | `Hash::make()` — bcrypt |
| CSRF Protection | `@csrf` في جميع النماذج |
| SQL Injection | Eloquent ORM — parameterized queries |
| XSS Protection | Blade `{{ }}` — auto-escaped |
| Authorization | RoleMiddleware + policy checks في Controllers |
| File Upload | فحص MIME type وحجم الملف |
| Password Rules | min:8, confirmed |

---

## 📞 معلومات المشروع

**المطوّرة:** Farah Raed Rasheed
**المستودع:** https://github.com/farahraedrasheed/GraduatuionProject
**نوع المشروع:** مشروع تخرج — نظام إدارة مركز طبي
**إطار العمل:** Laravel 13 (MVC — Web Application)
