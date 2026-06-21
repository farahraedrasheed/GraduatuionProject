<?php

namespace App\Notifications;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class AdminNotification extends Notification
{
    use Queueable;

    public string $title;
    public string $message;
    public ?int $appointmentId;

    public function __construct(string $title, string $message, ?int $appointmentId = null)
    {
        $this->title = $title;
        $this->message = $message;
        $this->appointmentId = $appointmentId;
    }

    public static function appointmentCreated(Appointment $appointment): self
    {
        $patientName = $appointment->patient->user->name;
        $doctorName = $appointment->doctor->user->name;
        return new self(
            'حجز موعد جديد',
            'تم حجز موعد جديد للمريض ' . $patientName . ' مع د. ' . $doctorName,
            $appointment->id
        );
    }

    public static function appointmentCancelled(Appointment $appointment): self
    {
        $patientName = $appointment->patient->user->name;
        $doctorName = $appointment->doctor->user->name;
        return new self(
            'إلغاء موعد',
            'تم إلغاء موعد المريض ' . $patientName . ' مع د. ' . $doctorName,
            $appointment->id
        );
    }

    public static function patientRegistered(string $patientName): self
    {
        return new self(
            'مريض جديد',
            'قام المريض ' . $patientName . ' بالتسجيل في النظام'
        );
    }

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        return [
            'title'          => $this->title,
            'message'        => $this->message,
            'appointment_id' => $this->appointmentId,
        ];
    }
}
