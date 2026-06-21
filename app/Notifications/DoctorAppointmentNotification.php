<?php

namespace App\Notifications;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class DoctorAppointmentNotification extends Notification
{
    use Queueable;

    public function __construct(public Appointment $appointment, public string $type) {}

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        $patientName = $this->appointment->patient->user->name;

        $appointmentDate = $this->appointment->appointment_date
            ? $this->appointment->appointment_date->format('Y-m-d H:i')
            : $this->appointment->requested_date?->format('Y-m-d');

        $messages = [
            'accepted'            => 'قبل المريض ' . $patientName . ' الموعد',
            'declined'            => 'رفض المريض ' . $patientName . ' الموعد',
            'cancelled_by_patient' => 'ألغى المريض ' . $patientName . ' الموعد المؤكد',
            'appointment_booked'  => 'تم حجز موعد جديد للمريض ' . $patientName . ' بنجاح' . ($appointmentDate ? ' بتاريخ ' . $appointmentDate : ''),
            'reminder'            => 'لديك موعد غداً مع المريض ' . $patientName . ($this->appointment->appointment_date ? ' الساعة ' . $this->appointment->appointment_date->format('H:i') : ''),
        ];

        $titles = [
            'accepted'            => 'قبول موعد',
            'declined'            => 'رفض موعد',
            'cancelled_by_patient' => 'إلغاء موعد من المريض',
            'appointment_booked'  => 'تأكيد حجز موعد',
            'reminder'            => 'تذكير بموعد',
        ];

        return [
            'title'          => $titles[$this->type] ?? 'تحديث على موعد',
            'message'        => $messages[$this->type] ?? 'تحديث على موعد',
            'appointment_id' => $this->appointment->id,
        ];
    }
}
