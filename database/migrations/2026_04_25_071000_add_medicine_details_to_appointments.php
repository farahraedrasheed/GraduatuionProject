<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->string('medicine_name')->nullable()->after('prescribed_medications');
            $table->text('doctor_notes')->nullable()->after('medicine_name');
        });
    }

    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropColumn('medicine_name');
            $table->dropColumn('doctor_notes');
        });
    }
};