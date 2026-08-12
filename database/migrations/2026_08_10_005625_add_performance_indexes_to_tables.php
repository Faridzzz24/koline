<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('consultations', function (Blueprint $table) {
            $table->index(['doctor_id', 'status']);
            $table->index(['patient_id', 'status']);
        });

        Schema::table('consultation_messages', function (Blueprint $table) {
            $table->index(['consultation_id', 'id']);
        });
    }

    public function down(): void
    {
        Schema::table('consultations', function (Blueprint $table) {
            $table->dropIndex(['doctor_id', 'status']);
            $table->dropIndex(['patient_id', 'status']);
        });

        Schema::table('consultation_messages', function (Blueprint $table) {
            $table->dropIndex(['consultation_id', 'id']);
        });
    }
};
