<?php

namespace App\Services;

use App\Models\User;
use App\Models\Doctor;
use Illuminate\Support\Facades\File;

class UserRegistry
{
    private static function getFilePath(): string
    {
        $dir = storage_path('app');
        if (!File::exists($dir)) {
            @File::makeDirectory($dir, 0777, true, true);
        }
        return storage_path('app/user_registry.json');
    }

    public static function syncFromRegistry(): void
    {
        try {
            $path = self::getFilePath();
            if (!File::exists($path)) {
                return;
            }

            $json = File::get($path);
            $registry = json_decode($json, true);
            if (!is_array($registry)) {
                return;
            }

            foreach ($registry as $data) {
                if (empty($data['email'])) {
                    continue;
                }

                $user = User::where('email', $data['email'])->first();
                if (!$user) {
                    $user = User::create([
                        'name' => $data['name'],
                        'email' => $data['email'],
                        'password' => $data['password'], // Already hashed password
                        'role' => $data['role'] ?? 'patient',
                        'phone' => $data['phone'] ?? null,
                        'gender' => $data['gender'] ?? null,
                        'email_verified_at' => now(),
                    ]);
                }

                if (($user->role === 'doctor' || ($data['role'] ?? '') === 'doctor') && !empty($data['doctor_data'])) {
                    $docData = $data['doctor_data'];
                    if (!Doctor::where('user_id', $user->id)->exists()) {
                        Doctor::create([
                            'user_id' => $user->id,
                            'specialization_id' => $docData['specialization_id'] ?? 1,
                            'str_number' => $docData['str_number'] ?? ('STR-' . rand(100, 999) . '-' . date('Y')),
                            'consultation_fee' => $docData['consultation_fee'] ?? 50000,
                            'experience_years' => $docData['experience_years'] ?? 1,
                            'hospital' => $docData['hospital'] ?? 'RS Partner KoLine',
                            'bio' => $docData['bio'] ?? null,
                            'is_verified' => true,
                            'is_available' => true,
                        ]);
                    }
                }
            }
        } catch (\Throwable $e) {}
    }

    public static function registerUser(User $user, ?array $doctorData = null): void
    {
        try {
            $path = self::getFilePath();
            $registry = [];
            if (File::exists($path)) {
                $registry = json_decode(File::get($path), true) ?: [];
            }

            $registry[$user->email] = [
                'name' => $user->name,
                'email' => $user->email,
                'password' => $user->password,
                'role' => $user->role,
                'phone' => $user->phone,
                'gender' => $user->gender,
                'doctor_data' => $doctorData,
            ];

            File::put($path, json_encode($registry, JSON_PRETTY_PRINT));
        } catch (\Throwable $e) {}
    }

    public static function removeUser(string $email): void
    {
        try {
            $path = self::getFilePath();
            if (!File::exists($path)) {
                return;
            }

            $registry = json_decode(File::get($path), true) ?: [];
            if (isset($registry[$email])) {
                unset($registry[$email]);
                File::put($path, json_encode($registry, JSON_PRETTY_PRINT));
            }
        } catch (\Throwable $e) {}
    }
}
