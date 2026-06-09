<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'المركز الطبي | صحتك في أيدٍ أمينة')</title>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/common.css') }}">
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
            <!-- Right Side: Logo + Navigation (visually right in RTL) -->
            <div class="nav-right">
                <div class="logo-section">
                    <img src="{{ asset('Photo/logo.jpeg') }}" alt="logo" class="logo"/>
                    <span class="clinic-name">المركز الطبي</span>
                </div>
                <div class="nav-overlay" onclick="toggleNav()"></div>
                <nav class="nav-links" id="navMenu">
                    <a href="{{ url('/') }}">الرئيسية</a>
                    <a href="{{ url('/about') }}">من نحن</a>
                </nav>
            </div>
            
            <!-- Left Side: Auth Buttons (visually left in RTL) -->
            <div class="nav-left">
                @guest
                    <a href="{{ route('login') }}" class="btn btn-ghost">دخول</a>
                    <a href="{{ route('register') }}" class="btn btn-primary">حساب</a>
                @else
                    <a href="{{ route('dashboard') }}" class="btn btn-ghost">لوحة</a>
                    <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                        @csrf
                        <button type="submit" class="btn btn-danger">خروج</button>
                    </form>
                @endguest
            </div>
        </div>
    </header>

    <main>
        @yield('content')
    </main>
    
    <footer class="footer">
        <div class="container">
            <p>© 2026 المركز الطبي. جميع الحقوق محفوظة.</p>
        </div>
    </footer>

    <script>
        function toggleNav() {
            document.getElementById('navMenu').classList.toggle('open');
            document.querySelector('.nav-overlay').classList.toggle('active');
        }
    </script>
</body>
</html>
