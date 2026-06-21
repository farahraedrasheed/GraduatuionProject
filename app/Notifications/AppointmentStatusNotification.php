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
            'new_appointment' => $this->appointment->requested_date
                ? 'تم حجز موعد لك مع د. ' . $this->appointment->doctor->user->name . ' بتاريخ ' . $this->appointment->requested_date->format('Y-m-d')
                : 'تم حجز موعد لك مع د. ' . $this->appointment->doctor->user->name,
            'confirmed' => 'تم تأكيد موعدك مع د. ' . $this->appointment->doctor->user->name,
            'completed' => 'تم إكمال موعدك بنجاح',
            'cancelled' => 'تم إلغاء موعدك',
            'notes_added' => 'تم إضافة ملاحظات جديدة على موعدك مع د. ' . $this->appointment->doctor->user->name,
            'edited' => 'تم تعديل موعدك مع د. ' . $this->appointment->doctor->user->name . ' إلى ' . ($this->appointment->appointment_date ? $this->appointment->appointment_date->format('Y-m-d H:i') : 'تاريخ جديد'),
        ];

        return [
            'title' => 'إشعار بالموعد',
            'message' => $messages[$this->type] ?? 'تحديث على موعدك',
            'appointment_id' => $this->appointment->id,
        ];
    }
}