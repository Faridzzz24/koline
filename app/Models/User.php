<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'role', 'phone', 'avatar',
        'date_of_birth', 'gender', 'address', 'blood_type',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'date_of_birth' => 'date',
        ];
    }

    public function isAdmin(): bool { return $this->role === 'admin'; }
    public function isDoctor(): bool { return $this->role === 'doctor'; }
    public function isPatient(): bool { return $this->role === 'patient'; }

    public function doctor() { return $this->hasOne(Doctor::class); }
    public function consultationsAsPatient() { return $this->hasMany(Consultation::class, 'patient_id'); }
    public function healthChecks() { return $this->hasMany(HealthCheck::class); }
    public function orders() { return $this->hasMany(Order::class); }
    public function articles() { return $this->hasMany(Article::class, 'author_id'); }
    public function sentMessages() { return $this->hasMany(ConsultationMessage::class, 'sender_id'); }

    public function getAvatarUrlAttribute(): string
    {
        if ($this->avatar) {
            return asset('storage/' . $this->avatar);
        }
        $cleanName = preg_replace('/^(drg\.|dr\.|Prof\.|Sp\.[A-Z]+)\s*/i', '', $this->name);
        $cleanName = preg_replace('/,?\s*Sp\.[A-Z]+.*$/i', '', $cleanName);
        $initials = urlencode(trim($cleanName));
        return "https://ui-avatars.com/api/?name={$initials}&background=0284C7&color=fff&bold=true&size=128";
    }

    public function getAgeAttribute(): ?int
    {
        return $this->date_of_birth ? $this->date_of_birth->age : null;
    }
}
