<?php

namespace App\Services;

use App\Models\User;
use App\Models\Doctor;
use Illuminate\Support\Facades\Cookie;
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

    private static function getRegistryData(): array
    {
        $registry = [];

        // 1. Load from file if exists
        try {
            $path = self::getFilePath();
            if (File::exists($path)) {
                $fileData = json_decode(File::get($path), true);
                if (is_array($fileData)) {
                    $registry = array_merge($registry, $fileData);
                }
            }
        } catch (\Throwable $e) {}

        // 2. Load from HTTP cookie if exists (Vercel Serverless cross-container persistence)
        try {
            $rawCookie = request()->cookie('koline_custom_users') ?? ($_COOKIE['koline_custom_users'] ?? null);
            if ($rawCookie) {
                if (is_string($rawCookie) && substr($rawCookie, 0, 1) !== '{') {
                    $decoded = @base64_decode($rawCookie);
                    if ($decoded) {
                        $rawCookie = $decoded;
                    }
                }
                $cookieData = is_array($rawCookie) ? $rawCookie : json_decode($rawCookie, true);
                if (is_array($cookieData)) {
                    $registry = array_merge($registry, $cookieData);
                }
            }
        } catch (\Throwable $e) {}

        return $registry;
    }

    public static function syncFromRegistry(): void
    {
        try {
            $registry = self::getRegistryData();
            if (empty($registry)) {
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
            $registry = self::getRegistryData();

            $registry[$user->email] = [
                'name' => $user->name,
                'email' => $user->email,
                'password' => $user->password,
                'role' => $user->role,
                'phone' => $user->phone,
                'gender' => $user->gender,
                'doctor_data' => $doctorData,
            ];

            // 1. Save to File
            try {
                $path = self::getFilePath();
                File::put($path, json_encode($registry, JSON_PRETTY_PRINT));
            } catch (\Throwable $e) {}

            // 2. Save to Long-Lived Cookie (Vercel cross-container sync)
            try {
                $payload = base64_encode(json_encode($registry));
                Cookie::queue(cookie()->make('koline_custom_users', $payload, 60 * 24 * 365, '/', null, false, false));
                if (!headers_sent()) {
                    setcookie('koline_custom_users', $payload, time() + (60 * 24 * 365 * 60), '/', '', false, false);
                }
            } catch (\Throwable $e) {}

        } catch (\Throwable $e) {}
    }

    public static function removeUser(string $email): void
    {
        try {
            $registry = self::getRegistryData();
            if (isset($registry[$email])) {
                unset($registry[$email]);

                // 1. Save to File
                try {
                    $path = self::getFilePath();
                    File::put($path, json_encode($registry, JSON_PRETTY_PRINT));
                } catch (\Throwable $e) {}

                // 2. Save to Cookie
                try {
                    $payload = base64_encode(json_encode($registry));
                    Cookie::queue(cookie()->make('koline_custom_users', $payload, 60 * 24 * 365, '/', null, false, false));
                    if (!headers_sent()) {
                        setcookie('koline_custom_users', $payload, time() + (60 * 24 * 365 * 60), '/', '', false, false);
                    }
                } catch (\Throwable $e) {}
            }
        } catch (\Throwable $e) {}
    }
}
