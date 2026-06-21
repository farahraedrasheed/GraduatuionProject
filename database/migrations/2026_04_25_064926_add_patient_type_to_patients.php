<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            $table->enum('patient_type', ['child', 'pregnant', 'chronic_disease', 'other'])->nullable()->after('age');
            $table->string('chronic_disease_type')->nullable()->after('patient_type');
        });

        Schema::table('doctors', function (Blueprint $table) {
            $table->string('specialty')->change();
        });
    }

    public function down(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            $table->dropColumn(['patient_type', 'chronic_disease_type']);
        });
    }
};