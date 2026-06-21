<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE appointment_reminders MODIFY COLUMN reminder_type ENUM('email', 'sms', 'push', '24h') NOT NULL");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE appointment_reminders MODIFY COLUMN reminder_type ENUM('email', 'sms', 'push') NOT NULL");
    }
};
