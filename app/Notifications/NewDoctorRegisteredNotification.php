<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewDoctorRegisteredNotification extends Notification
{
    use Queueable;

    public function __construct(public User $doctor) {}

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        return [
            'title'     => 'طبيب جديد انضم للنظام',
            'message'   => 'قام ' . $this->doctor->name . ' بالتسجيل في النظام',
            'doctor_id' => $this->doctor->doctor->id ?? null,
        ];
    }
}
