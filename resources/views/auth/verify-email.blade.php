<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تأكيد البريد الإلكتروني - المركز الطبي</title>
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
        }
        .logout-link {
            color: #6b7280;
            font-size: 0.85rem;
            text-decoration: underline;
        }
        .logout-link:hover { color: #374151; }
    </style>
</head>
<body>
    <div style="text-align: center;">
        <a href="{{ url('/') }}">
            <img src="{{ asset('Photo/logo.jpeg') }}" alt="المركز الطبي" style="width: 5rem; height: 5rem; border-radius: 50%; object-fit: cover;">
        </a>
    </div>

    <div class="auth-card">
        <p>شكراً للتسجيل! قبل البدء، يرجى التحقق من بريدك الإلكتروني والنقر على الرابط الذي أرسلناه لك. إذا لم تستلم البريد، سنرسل لك آخر بكل سرور.</p>

        @if (session('status') == 'verification-link-sent')
            <div class="success-msg" style="display: block;">
                تم إرسال رابط تحقق جديد إلى البريد الإلكتروني الذي استخدمته عند التسجيل.
            </div>
        @endif

        <div style="display: flex; align-items: center; justify-content: space-between;">
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button type="submit" class="btn-primary">إعادة إرسال رابط التحقق</button>
            </form>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="logout-link" style="background: none; border: none; cursor: pointer; font-family: 'Cairo', sans-serif;">تسجيل الخروج</button>
            </form>
        </div>
    </div>
</body>
</html>
