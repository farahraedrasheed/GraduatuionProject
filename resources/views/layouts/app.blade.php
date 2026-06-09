<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'المركز الطبي | صحتك في أيدٍ أمينة')</title>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/common.css') }}">
    @vite('resources/css/app.css')
    @yield('styles')
</head>
<body>
    <header class="header">
        <div class="nav-container">
            <button class="hamburger" onclick="toggleNav()">
                <span></span>
                <span></span>
                <span></span>
            </button>
            <!-- Right Section: Logo + Navigation -->
            <div class="nav-right">
                <div class="logo-section">
                    <img src="{{ asset('Photo/logo.jpeg') }}" alt="logo" class="logo"/>
                    <span class="clinic-name">المركز الطبي</span>
                </div>
                <div class="nav-overlay" onclick="toggleNav()"></div>
                <nav class="nav-links" id="navMenu">
                    @if(Auth::user()->role == 'admin')
                        <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">لوحة التحكم</a>
                        <a href="{{ route('doctors.index') }}" class="{{ request()->routeIs('doctors.*') ? 'active' : '' }}">الأطباء</a>
                        <a href="{{ route('patients.index') }}" class="{{ request()->routeIs('patients.*') ? 'active' : '' }}">المرضى</a>
                        <a href="{{ route('appointments.index') }}" class="{{ request()->routeIs('appointments.*') ? 'active' : '' }}">المواعيد</a>
                        <a href="{{ route('profile.edit') }}" class="{{ request()->routeIs('profile.*') ? 'active' : '' }}">ملفي الشخصي</a>
                    @elseif(Auth::user()->role == 'doctor')
                        <a href="{{ route('doctor.dashboard') }}" class="{{ request()->routeIs('doctor.dashboard') ? 'active' : '' }}">لوحة التحكم</a>
                        <a href="{{ route('doctor.appointments') }}" class="{{ request()->routeIs('doctor.appointments*') ? 'active' : '' }}">المواعيد</a>
                        <a href="{{ route('doctor.patients') }}" class="{{ request()->routeIs('doctor.patients*') ? 'active' : '' }}">مرضاي</a>
                        <a href="{{ route('doctor.schedule') }}" class="{{ request()->routeIs('doctor.schedule*') ? 'active' : '' }}">الجدول</a>
                        <a href="{{ route('profile.edit') }}" class="{{ request()->routeIs('profile.*') ? 'active' : '' }}">ملفي الشخصي</a>
                    @else
                        <a href="{{ route('patient.dashboard') }}" class="{{ request()->routeIs('patient.dashboard') ? 'active' : '' }}">لوحة التحكم</a>
                        <a href="{{ route('patient.appointments') }}" class="{{ request()->routeIs('patient.appointments*') ? 'active' : '' }}">مواعيدي</a>
                        <a href="{{ route('patient.medical-records') }}" class="{{ request()->routeIs('patient.medical-records*') ? 'active' : '' }}">السجلات الطبية</a>
                        <a href="{{ route('patient.reports') }}" class="{{ request()->routeIs('patient.reports*') ? 'active' : '' }}">التقارير الطبية</a>
                        <a href="{{ route('profile.edit') }}" class="{{ request()->routeIs('profile.*') ? 'active' : '' }}">ملفي الشخصي</a>
                    @endif
                </nav>
            </div>
            
            <!-- Left Section: Auth Buttons -->
            <div class="nav-left">
                <a href="{{ route('dashboard') }}" class="btn btn-ghost">لوحة التحكم</a>
                <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn btn-danger">تسجيل خروج</button>
                </form>
            </div>
        </div>
    </header>

    <div class="dashboard-content" style="padding: 2rem; background: #f1f5f9; min-height: calc(100vh - 70px);">
        @yield('content')
    </div>

    <footer class="footer">
        <div class="container">
            <p>© 2026 المركز الطبي. جميع الحقوق محفوظة.</p>
        </div>
    </footer>

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        function toggleNav() {
            document.getElementById('navMenu').classList.toggle('open');
            document.querySelector('.nav-overlay').classList.toggle('active');
        }
    </script>
</body>
</html>
