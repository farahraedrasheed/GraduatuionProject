<?php

namespace App\Console\Commands;

use App\Models\Appointment;
use App\Models\AppointmentReminder;
use App\Notifications\AppointmentReminderNotification;
use App\Notifications\DoctorAppointmentNotification;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SendAppointmentReminders extends Command
{
    protected $signature = 'appointments:send-reminders';
    protected $description = 'Send 24-hour reminders for tomorrow\'s confirmed appointments';

    public function handle(): void
    {
        $tomorrow = Carbon::tomorrow();

        $appointments = Appointment::where('status', 'confirmed')
            ->whereBetween('appointment_date', [
                $tomorrow->copy()->startOfDay(),
                $tomorrow->copy()->endOfDay(),
            ])
            ->with(['patient.user', 'doctor.user'])
            ->get();

        $sent = 0;
        foreach ($appointments as $appointment) {
            $alreadySent = AppointmentReminder::where('appointment_id', $appointment->id)
                ->where('reminder_type', '24h')
                ->where('status', 'sent')
                ->exists();

            if ($alreadySent) {
                continue;
            }

            try {
                $appointment->patient->user->notify(new AppointmentReminderNotification($appointment));
                $appointment->doctor->user->notify(new DoctorAppointmentNotification($appointment, 'reminder'));

                AppointmentReminder::create([
                    'appointment_id' => $appointment->id,
                    'reminder_type'  => '24h',
                    'status'         => 'sent',
                    'scheduled_for'  => now(),
                    'sent_at'        => now(),
                ]);

                $sent++;
            } catch (\Exception $e) {
                AppointmentReminder::create([
                    'appointment_id' => $appointment->id,
                    'reminder_type'  => '24h',
                    'status'         => 'failed',
                    'scheduled_for'  => now(),
                    'error_message'  => $e->getMessage(),
                ]);

                $this->error("فشل إرسال تذكير للموعد #{$appointment->id}: " . $e->getMessage());
            }
        }

        $this->info("تم إرسال {$sent} تذكير من أصل {$appointments->count()} موعد.");
    }
}
