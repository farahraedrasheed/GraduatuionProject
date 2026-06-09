<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>نسيت كلمة المرور - المركز الطبي</title>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/common.css') }}">
    <style>
        body {
            font-family: 'Cairo', sans-serif;
            margin: 0;
            background: #f3f4f6;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding-top: 1.5rem;
        }
        .auth-card {
            width: 100%;
            max-width: 28rem;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);
            padding: 1.5rem 2rem;
            margin-top: 1.5rem;
            box-sizing: border-box;
        }
        .auth-card p {
            font-size: 0.9rem;
            color: #4b5563;
            margin-bottom: 1.5rem;
            line-height: 1.6;
        }
        .auth-card label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: #374151;
        }
        .auth-card input {
            width: 100%;
            padding: 0.6rem 0.75rem;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-family: 'Cairo', sans-serif;
            box-sizing: border-box;
        }
        .auth-card input:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59,130,246,0.2);
        }
        .btn-primary {
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
            color: white;
            padding: 0.6rem 1.5rem;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            font-family: 'Cairo', sans-serif;
            font-size: 0.9rem;
        }
        .btn-primary:hover {
            background: linear-gradient(135deg, #2563eb, #1e40af);
        }
        .success-msg {
            padding: 0.6rem;
            background: #d1fae5;
            border-radius: 8px;
            margin-bottom: 1rem;
            font-size: 0.85rem;
            color: #059669;
            display: none;
        }
        .back-link {
            display: block;
            text-align: center;
            margin-top: 1.5rem;
            font-size: 0.9rem;
            color: #3b82f6;
            text-decoration: none;
        }
        .back-link:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <div style="text-align: center;">
        <a href="{{ url('/') }}">
            <img src="{{ asset('Photo/logo.jpeg') }}" alt="المركز الطبي" style="width: 5rem; height: 5rem; border-radius: 50%; object-fit: cover;">
        </a>
    </div>

    <div class="auth-card">
        <p>نسيت كلمة المرور؟ لا مشكلة. أخبرنا ببريدك الإلكتروني وسنرسل لك رابط إعادة التعيين.</p>

        @if (session('status'))
            <div class="success-msg" style="display: block;">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <div>
                <label for="email">البريد الإلكتروني</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus>
            </div>

            <div style="display: flex; justify-content: flex-end; margin-top: 1.5rem;">
                <button type="submit" class="btn-primary">إرسال رابط إعادة التعيين</button>
            </div>
        </form>

        <a href="{{ route('login') }}" class="back-link">العودة إلى تسجيل الدخول</a>
    </div>
</body>
</html>
