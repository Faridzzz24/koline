<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('consultations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('doctor_id')->constrained()->cascadeOnDelete();
            $table->date('consultation_date');
            $table->time('consultation_time');
            $table->enum('status', ['pending', 'confirmed', 'active', 'completed', 'cancelled'])->default('pending');
            $table->text('complaint'); // Keluhan pasien
            $table->text('diagnosis')->nullable();
            $table->text('prescription')->nullable(); // Resep
            $table->text('notes')->nullable();
            $table->decimal('fee', 10, 2)->default(0);
            $table->integer('rating')->nullable(); // 1-5
            $table->text('review')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('consultations');
    }
};
