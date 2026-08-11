<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Consultation extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id', 'doctor_id', 'consultation_date', 'consultation_time', 'duration_hours',
        'status', 'complaint', 'diagnosis', 'prescription', 'notes', 'fee', 'rating', 'review',
    ];

    protected $casts = [
        'consultation_date' => 'date',
        'fee' => 'decimal:2',
        'duration_hours' => 'integer',
    ];

    public function patient() { return $this->belongsTo(User::class, 'patient_id'); }
    public function doctor() { return $this->belongsTo(Doctor::class); }
    public function messages() { return $this->hasMany(ConsultationMessage::class)->orderBy('created_at'); }

    public function getStartDateTimeAttribute(): \Carbon\Carbon
    {
        $dateStr = $this->consultation_date ? $this->consultation_date->format('Y-m-d') : date('Y-m-d');
        $timeStr = $this->consultation_time ?? '08:00:00';
        return \Carbon\Carbon::parse("{$dateStr} {$timeStr}");
    }

    public function getEndDateTimeAttribute(): \Carbon\Carbon
    {
        $scheduledEnd = $this->start_date_time->copy()->addHours($this->duration_hours ?? 1);
        if (in_array($this->status, ['confirmed', 'active'])) {
            $baseTime = $this->updated_at ?: $this->created_at;
            if ($baseTime && \Carbon\Carbon::now()->gte($scheduledEnd)) {
                return $baseTime->copy()->addHours($this->duration_hours ?? 1);
            }
        }
        return $scheduledEnd;
    }

    public function getRemainingSecondsAttribute(): int
    {
        $now = \Carbon\Carbon::now();
        $end = $this->end_date_time;
        if ($now->gte($end)) return 0;
        return (int) $now->diffInSeconds($end);
    }

    public function getStartCountdownSecondsAttribute(): int
    {
        $now = \Carbon\Carbon::now();
        $start = $this->start_date_time;
        if ($now->gte($start)) return 0;
        return (int) $now->diffInSeconds($start);
    }

    public function getIsStartedAttribute(): bool
    {
        if (in_array($this->status, ['confirmed', 'active'])) {
            return true;
        }
        return \Carbon\Carbon::now()->gte($this->start_date_time);
    }

    public function getIsExpiredAttribute(): bool
    {
        if (in_array($this->status, ['completed', 'cancelled'])) {
            return true;
        }
        return \Carbon\Carbon::now()->gte($this->end_date_time);
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'pending' => 'Menunggu',
            'confirmed' => 'Dikonfirmasi',
            'active' => 'Berlangsung',
            'completed' => 'Selesai',
            'cancelled' => 'Dibatalkan',
            default => $this->status,
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'pending' => 'warning',
            'confirmed' => 'info',
            'active' => 'success',
            'completed' => 'secondary',
            'cancelled' => 'danger',
            default => 'secondary',
        };
    }
}
