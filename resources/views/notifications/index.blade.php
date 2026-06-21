@extends('layouts.app')

@section('title', 'الإشعارات - المركز الطبي')

@section('content')
<div class="notif-page">
    <div class="notif-card">

        <div class="notif-header">
            <h2 class="notif-title">
                الإشعارات
                @if(auth()->user()->unreadNotifications->count() > 0)
                <span class="notif-badge">
                    {{ auth()->user()->unreadNotifications->count() }} جديد
                </span>
                @endif
            </h2>
            @if(auth()->user()->unreadNotifications->count() > 0)
            <form method="POST" action="{{ route('notifications.read-all') }}">
                @csrf
                <button type="submit" class="notif-mark-all-btn">
                    تعليم الكل كمقروء
                </button>
            </form>
            @endif
        </div>

        @if(session('success'))
        <div class="alert-success">
            {{ session('success') }}
        </div>
        @endif

        <div>
            @forelse($notifications as $notification)
            @php
                $title = $notification->data['title'] ?? '';
                $msg = $notification->data['message'] ?? '';
                $isUnread = is_null($notification->read_at);

                if (str_contains($title, 'حجز') || str_contains($msg, 'حجز')) {
                    $type = 'booked';
                } elseif (str_contains($title, 'تأكيد') || str_contains($msg, 'تأكيد')) {
                    $type = 'confirmed';
                } elseif (str_contains($title, 'إكمال') || str_contains($msg, 'إكمال')) {
                    $type = 'completed';
                } elseif (str_contains($title, 'إلغاء') || str_contains($msg, 'إلغاء') || str_contains($title, 'رفض') || str_contains($msg, 'رفض')) {
                    $type = 'cancelled';
                } elseif (str_contains($title, 'تذكير') || str_contains($msg, 'تذكير')) {
                    $type = 'reminder';
                } elseif (str_contains($title, 'تسجيل') || str_contains($msg, 'تسجيل') || str_contains($title, 'انضم') || str_contains($msg, 'انضم')) {
                    $type = 'register';
                } else {
                    $type = 'default';
                }
            @endphp
            <div class="notif-item notif-type-{{ $type }} {{ $isUnread ? 'unread' : '' }}">
                <div class="notif-icon">
                    @if($type === 'booked' || $type === 'confirmed')
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    @elseif($type === 'completed')
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    @elseif($type === 'cancelled')
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    @elseif($type === 'reminder')
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    @elseif($type === 'register')
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                    </svg>
                    @else
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                    @endif
                </div>
                <div class="notif-body">
                    <p class="notif-text">{{ $msg ?: $title }}</p>
                    <p class="notif-meta">{{ $notification->created_at->diffForHumans() }}</p>
                </div>
                @if($isUnread)
                <form method="POST" action="{{ route('notifications.read', $notification->id) }}">
                    @csrf
                    <button type="submit" class="notif-read-btn">تعليم كمقروء</button>
                </form>
                @endif
            </div>
            @empty
            <div class="notif-empty">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                </svg>
                <p>لا توجد إشعارات</p>
            </div>
            @endforelse
        </div>

        @if($notifications->hasPages())
        <div style="margin-top: 1.5rem;">
            {{ $notifications->links() }}
        </div>
        @endif

    </div>
</div>
@endsection