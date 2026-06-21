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
            
            <!-- Left Section: Notifications + Auth Buttons -->
            <div class="nav-left" style="display:flex; align-items:center; gap:0.75rem;">

                <!-- Notification Bell -->
                @php $unreadCount = auth()->user()->unreadNotifications->count(); @endphp
                <div x-data="{ open: false }" @click.away="open = false" style="position: relative;">
                    <button @click.stop="open = !open" style="background:none; border:none; cursor:pointer; position:relative; padding:0.4rem; color:#374151; display:flex; align-items:center;">
                        <svg style="width:1.5rem;height:1.5rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                        @if($unreadCount > 0)
                        <span style="position:absolute;top:0;left:0;background:#ef4444;color:white;border-radius:9999px;font-size:0.65rem;width:1.1rem;height:1.1rem;display:flex;align-items:center;justify-content:center;font-weight:700;">
                            {{ $unreadCount > 9 ? '9+' : $unreadCount }}
                        </span>
                        @endif
                    </button>

                    <div x-show="open" x-transition class="notif-dropdown">
                        <div class="notif-dropdown-header">
                            <span>الإشعارات</span>
                            @if($unreadCount > 0)
                            <form method="POST" action="{{ route('notifications.read-all') }}">
                                @csrf
                                <button type="submit" style="font-size:0.75rem;color:#3b82f6;background:none;border:none;cursor:pointer;font-family:inherit;">تعليم الكل</button>
                            </form>
                            @endif
                        </div>
                        @forelse(auth()->user()->notifications()->latest()->take(5)->get() as $notif)
                        <div class="notif-dropdown-item {{ $notif->read_at ? '' : 'unread' }}">
                            <p>{{ $notif->data['message'] ?? 'إشعار جديد' }}</p>
                            <p class="notif-time">{{ $notif->created_at->diffForHumans() }}</p>
                        </div>
                        @empty
                        <p style="padding:1.5rem;text-align:center;color:#9ca3af;font-size:0.875rem;">لا توجد إشعارات</p>
                        @endforelse
                        <a href="{{ route('notifications.index') }}" class="notif-dropdown-all">
                            عرض كل الإشعارات
                        </a>
                    </div>
                </div>

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
