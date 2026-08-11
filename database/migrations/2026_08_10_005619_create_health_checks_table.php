<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('health_checks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->enum('type', ['bmi', 'symptom_checker', 'blood_pressure', 'blood_sugar']);
            $table->json('input_data'); // Data input dari user
            $table->json('result_data'); // Hasil kalkulasi/analisis
            $table->string('result_summary')->nullable();
            $table->enum('risk_level', ['low', 'moderate', 'high'])->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('health_checks');
    }
};
