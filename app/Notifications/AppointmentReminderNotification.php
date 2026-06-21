<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class AppointmentReminderNotification extends Notification
{
    use Queueable;

    public function __construct(public $appointment) {}

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        return [
            'title'          => 'تذكير بموعد',
            'message'        => 'لديك موعد غداً مع د. ' . $this->appointment->doctor->user->name
                                . ' الساعة ' . $this->appointment->appointment_date->format('H:i'),
            'appointment_id' => $this->appointment->id,
        ];
    }
}
