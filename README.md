# 🏥 المركز الطبي — Clinic Management System

### منصة متكاملة لإدارة المراكز الطبية بشكل احترافي وذكي.

[![Version](https://img.shields.io/badge/version-1.0.0-blueviolet?style=for-the-badge)](https://github.com/farahraedrasheed/GraduatuionProject)
[![License](https://img.shields.io/badge/license-MIT-green?style=for-the-badge)](LICENSE)
[![Laravel](https://img.shields.io/badge/Laravel-13-red?style=for-the-badge&logo=laravel)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.2+-blue?style=for-the-badge&logo=php)](https://php.net)
[![TailwindCSS](https://img.shields.io/badge/Tailwind-CSS-38bdf8?style=for-the-badge&logo=tailwindcss)](https://tailwindcss.com)

---

## 🌟 نظرة عامة

**المركز الطبي** هو نظام ويب متكامل لإدارة العيادات والمراكز الطبية، يربط بين الأطباء والمرضى والإدارة في منصة واحدة سهلة الاستخدام. يحل النظام مشكلة إدارة المواعيد اليدوية، ويوفر تجربة رقمية متكاملة لجميع أطراف المنظومة الصحية.

---

## ✨ مميزات النظام

### 🛡️ للأدمن
- **لوحة تحكم تحليلية** مع إحصائيات شاملة ومخططات بيانية تفاعلية
- **إدارة الأطباء** — إضافة، تعديل، حذف، عرض تفاصيل كل طبيب
- **إدارة المرضى** — إضافة، تعديل، حذف، عرض ملف كل مريض
- **إدارة المواعيد** — متابعة جميع المواعيد في النظام
- **إشعارات فورية** عند انضمام طبيب جديد للنظام

### 👨‍⚕️ للطبيب
- **لوحة تحكم** مع ملخص يومي للمواعيد
- **تقويم تفاعلي** لعرض المواعيد (FullCalendar)
- **إدارة المواعيد** — تأكيد، إكمال، إلغاء مع فحص التعارض تلقائياً
- **إدارة جدول العمل** — تحديد أيام وأوقات الدوام
- **السجلات الطبية** — إنشاء وعرض السجلات لكل مريض
- **تقارير المرضى** — الاطلاع على التقارير الطبية المرفوعة

### 🧑‍🦽 للمريض
- **لوحة تحكم** مع آخر المواعيد والسجلات
- **متابعة المواعيد** — قبول أو رفض المواعيد المحددة
- **السجلات الطبية** — عرض وطباعة/تصدير PDF
- **التقارير الطبية** — رفع، تحميل، وحذف الملفات
- **إشعارات فورية** عند تأكيد أو إلغاء أي موعد

---

## 🚀 الميزات التقنية

- 🔐 **نظام مصادقة كامل** — تسجيل، دخول، استعادة كلمة السر
- 👥 **صلاحيات بثلاثة أدوار** — أدمن، طبيب، مريض
- 📅 **فحص تعارض المواعيد** — رفض تلقائي عند وجود موعد خلال ساعة
- 🕐 **التحقق من جدول الدوام** — لا يُقبل موعد خارج أوقات عمل الطبيب
- 🔔 **مركز إشعارات** — جرس في الشريط العلوي مع عداد الإشعارات غير المقروءة
- ⏰ **تذكير تلقائي** — يُرسل للمريض قبل يوم من موعده (Scheduler يومي 9 صباحاً)
- 📄 **طباعة PDF** للسجلات الطبية
- 📊 **مخططات بيانية** — Chart.js للإحصائيات
- 📆 **تقويم تفاعلي** — FullCalendar v6
- 📋 **Pagination** في جميع القوائم مع الحفاظ على فلاتر البحث
- 🌐 **واجهة عربية RTL** بالكامل

---

## 🛠️ التقنيات المستخدمة

| التقنية | الاستخدام |
|---|---|
| **Laravel 13** | إطار العمل الخلفي |
| **PHP 8.2+** | لغة البرمجة |
| **MySQL** | قاعدة البيانات |
| **Tailwind CSS** | التصميم والواجهة |
| **Alpine.js** | التفاعلية في الواجهة |
| **Chart.js** | الرسوم البيانية |
| **FullCalendar v6** | تقويم المواعيد |
| **XAMPP** | بيئة التطوير المحلية |

---

## 📂 هيكل المشروع

```text
clinic-app/
├── app/
│   ├── Console/Commands/        # SendAppointmentReminders scheduler
│   ├── Http/Controllers/        # Controllers لكل دور
│   ├── Models/                  # Appointment, Doctor, Patient, User, ...
│   └── Notifications/           # إشعارات المواعيد والأطباء
├── database/
│   ├── migrations/              # جداول قاعدة البيانات
│   └── seeders/                 # بيانات تجريبية
├── resources/views/
│   ├── admin/                   # واجهات الأدمن
│   ├── doctor/                  # واجهات الطبيب
│   ├── patient/                 # واجهات المريض
│   ├── notifications/           # مركز الإشعارات
│   └── layouts/                 # القوالب العامة
├── routes/
│   ├── web.php                  # مسارات الويب
│   ├── auth.php                 # مسارات المصادقة
│   └── console.php              # المهام المجدولة
├── public/
│   ├── css/                     # ملفات CSS المخصصة
│   └── Photo/                   # الصور
├── ROADMAP.md                   # خارطة طريق المشروع
└── README.md                    # هذا الملف
```

---

## 🏁 طريقة التشغيل

### المتطلبات
- PHP 8.2+
- MySQL
- Composer
- Node.js & npm
- XAMPP (أو أي بيئة مشابهة)

### خطوات التثبيت

```bash
# 1. استنساخ المشروع
git clone https://github.com/farahraedrasheed/GraduatuionProject.git
cd GraduatuionProject

# 2. تثبيت اعتماديات PHP
composer install

# 3. نسخ ملف البيئة وتعديله
copy .env.example .env
```

**عدّل ملف `.env`:**
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=clinic
DB_USERNAME=root
DB_PASSWORD=
```

```bash
# 4. توليد مفتاح التطبيق
php artisan key:generate

# 5. تشغيل الـ Migrations والـ Seeders
php artisan migrate --seed

# 6. ربط مجلد التخزين
php artisan storage:link

# 7. تثبيت اعتماديات JavaScript وبناء الـ assets
npm install
npm run build

# 8. تشغيل الخادم
php artisan serve
```

ثم افتح المتصفح على: `http://localhost:8000`

---

## 👤 بيانات الدخول التجريبية

| الدور | البريد الإلكتروني | كلمة السر |
|---|---|---|
| أدمن | admin@clinic.com | admin123 |
| طبيب | doctor@clinic.com | doctor123 |
| مريض | patient@clinic.com | patient123 |

---

## 📌 خارطة الطريق

راجع ملف [ROADMAP.md](ROADMAP.md) للاطلاع على المراحل المكتملة والمخطط لها.

---

> **🏆 تم التطوير بواسطة:** Farah Raed Rasheed — مشروع تخرج
