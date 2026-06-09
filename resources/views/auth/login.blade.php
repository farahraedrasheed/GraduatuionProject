@extends('layouts.main')

@section('title', 'تسجيل الدخول - المركز الطبي')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/common.css') }}">
    <style>
        .login-container {
            max-width: 500px;
            margin: 3rem auto;
            padding: 2rem;
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
    </style>
@endsection

@section('content')
    <div class="login-container">
        <h2 style="text-align: center; margin-bottom: 0.5rem; color: #1f2937;">تسجيل الدخول</h2>
        <p style="text-align: center; color: #6b7280; margin-bottom: 2rem;">مرحبا بكم في المركز الطبي</p>
        
        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div style="margin-bottom: 1rem;">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">البريد الإلكتروني</label>
                <input type="email" name="email" value="{{ old('email') }}" required autofocus 
                       style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 8px;">
            </div>

            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">كلمة المرور</label>
                <input type="password" name="password" required 
                       style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 8px;">
            </div>

            @if ($errors->any())
                <div style="margin-bottom: 1rem; padding: 10px; background: #fee2e2; border-radius: 8px;">
                    @foreach ($errors->all() as $error)
                        <p style="color: #dc2626; font-size: 0.9rem;">{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <div style="display: flex; justify-content: space-between; align-items: center;">
                <a href="{{ route('password.request') }}" style="font-size: 0.9rem; color: #6b7280;">نسيت كلمة المرور؟</a>
                <button type="submit" style="background: linear-gradient(135deg, #3b82f6, #1d4ed8); color: white; padding: 10px 30px; border: none; border-radius: 8px; font-weight: 600; cursor: pointer;">تسجيل الدخول</button>
            </div>
        </form>
        
        <p style="text-align: center; margin-top: 1.5rem; color: #6b7280;">
            ليس لديك حساب؟ <a href="{{ route('register') }}" style="color: #3b82f6;">إنشاء حساب</a>
        </p>
    </div>
@endsection
