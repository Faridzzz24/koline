<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'specialization_id', 'str_number', 'experience_years',
        'consultation_fee', 'bio', 'hospital', 'education',
        'rating', 'total_reviews', 'total_patients', 'is_available', 'is_verified',
    ];

    protected $casts = [
        'is_available' => 'boolean',
        'is_verified' => 'boolean',
        'consultation_fee' => 'decimal:2',
        'rating' => 'decimal:2',
    ];

    public function user() { return $this->belongsTo(User::class); }
    public function specialization() { return $this->belongsTo(Specialization::class); }
    public function schedules() { return $this->hasMany(DoctorSchedule::class); }
    public function consultations() { return $this->hasMany(Consultation::class); }

    public function getFormattedFeeAttribute(): string
    {
        return 'Rp ' . number_format($this->consultation_fee, 0, ',', '.');
    }

    public function getStarsAttribute(): array
    {
        $full = floor($this->rating);
        $half = ($this->rating - $full) >= 0.5 ? 1 : 0;
        $empty = 5 - $full - $half;
        return compact('full', 'half', 'empty');
    }
}
