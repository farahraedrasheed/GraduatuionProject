<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class NotificationController extends Controller
{
    public function index(): View
    {
        $notifications = $this->authUser()->notifications()->latest()->paginate(20);
        return view('notifications.index', compact('notifications'));
    }

    public function markAsRead(string $id): RedirectResponse
    {
        $this->authUser()->notifications()->where('id', $id)->first()?->markAsRead();
        return back();
    }

    public function markAllAsRead(): RedirectResponse
    {
        $this->authUser()->unreadNotifications->markAsRead();
        return back()->with('success', 'تم تعليم جميع الإشعارات كمقروءة');
    }
}
