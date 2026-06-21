@extends('layouts.main')
 
@section('title', 'المركز الطبي | صحتك في أيدٍ أمينة')
 
@section('styles')
    <link rel="stylesheet" href="{{ asset('css/index.css') }}">
@endsection
 
@section('content')
    <div class="hero-wrapper">
        <div class="content-area">
            <h1>صحتك في أيدٍ أمينة</h1>
            <p>نحن نقدم أفضل الخدمات الطبية المتميزة لضمان حياة صحية وسعيدة لك ولعائلتك، مع نخبة من الأطباء المتخصصين.</p>
            
            <div class="cta-container">
                <a href="{{ route('register') }}" class="main-cta">انضم إلينا</a>
                <a href="{{ url('/about') }}" class="secondary-cta">من نحن</a>       
            </div>
        </div>
    </div>
    
    <div class="services-section">
        <div class="container">
            <h2 class="section-title">خدماتنا الطبية</h2>
            <p style="text-align: center; color: #6b7280; margin-bottom: 3rem; max-width: 600px; margin-left: auto; margin-right: auto;">
                اختر الفئة المناسبة لك للحصول على الرعاية الطبية المتخصصة
            </p>
            <div class="services-grid">
                <div class="service-card">
                    <div class="service-icon blue">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                    </div>
                    <h3>عيادة الأطفال</h3>
                    <p>متابعة وتطعيمات الأطفال من الولادة حتى المدرسة مع أفضل أطباء الأطفال</p>
                    <a href="{{ route('register') }}?role=patient&type=child" class="service-link">تسجيل كمريض طفل</a>
                </div>
                
                <div class="service-card">
                    <div class="service-icon pink">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                        </svg>
                    </div>
                    <h3>عيادة الحوامل</h3>
                    <p>رعاية طبية شاملة للحامل طوال فترة الحمل مع أطباء النساء والتوليد</p>
                    <a href="{{ route('register') }}?role=patient&type=pregnant" class="service-link">تسجيل كحامل</a>
                </div>
                
                <div class="service-card">
                    <div class="service-icon green">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                    <h3>عيادة الأمراض المزمنة</h3>
                    <p>متابعة وعلاج القلب والسكر وأمراض الكبد والكلى والغدد</p>
                    <a href="{{ route('register') }}?role=patient&type=chronic" class="service-link">تسجيل كمريض مزمن</a>
                </div>
            </div>
        </div>
    </div>
@endsection