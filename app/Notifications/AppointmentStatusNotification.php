<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class AppointmentStatusNotification extends Notification
{
    use Queueable;

    public $appointment;
    public $type;

    public function __construct($appointment, $type)
    {
        $this->appointment = $appointment;
        $this->type = $type;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        $messages = [
            'confirmed' => 'تم تأكيد موعدك مع د. ' . $this->appointment->doctor->user->name,
            'completed' => 'تم إكمال موعدك بنجاح',
            'cancelled' => 'تم إلغاء موعدك',
        ];

        return [
            'title' => 'إشعار بالموعد',
            'message' => $messages[$this->type] ?? 'تحديث على موعدك',
            'appointment_id' => $this->appointment->id,
        ];
    }
}