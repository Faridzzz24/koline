<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HealthCheck extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'type', 'input_data', 'result_data', 'result_summary', 'risk_level'];

    protected $casts = [
        'input_data' => 'array',
        'result_data' => 'array',
    ];

    public function user() { return $this->belongsTo(User::class); }

    public function getTypeLabelAttribute(): string
    {
        return match($this->type) {
            'bmi' => 'Kalkulator BMI',
            'symptom_checker' => 'Cek Gejala',
            'blood_pressure' => 'Tekanan Darah',
            'blood_sugar' => 'Gula Darah',
            default => $this->type,
        };
    }
}
