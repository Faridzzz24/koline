<?php

namespace App\Services;

use App\Models\Consultation;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\File;

class ConsultationRegistry
{
    private static function getFilePath(): string
    {
        $dir = storage_path('app');
        if (!File::exists($dir)) {
            @File::makeDirectory($dir, 0777, true, true);
        }
        return storage_path('app/consultation_registry.json');
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

        // 2. Load from HTTP cookie if exists (Vercel cross-container sync)
        try {
            $rawCookie = request()->cookie('koline_consultation_states') ?? ($_COOKIE['koline_consultation_states'] ?? null);
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

            foreach ($registry as $id => $data) {
                if (empty($id) || empty($data['status'])) {
                    continue;
                }

                $consultation = Consultation::find($id);
                if ($consultation) {
                    $updates = [];
                    if (!empty($data['status']) && $consultation->status !== $data['status']) {
                        $updates['status'] = $data['status'];
                    }
                    if (isset($data['diagnosis']) && $consultation->diagnosis !== $data['diagnosis']) {
                        $updates['diagnosis'] = $data['diagnosis'];
                    }
                    if (isset($data['prescription']) && $consultation->prescription !== $data['prescription']) {
                        $updates['prescription'] = $data['prescription'];
                    }
                    if (isset($data['notes']) && $consultation->notes !== $data['notes']) {
                        $updates['notes'] = $data['notes'];
                    }
                    if (!empty($updates)) {
                        $consultation->update($updates);
                    }
                }
            }
        } catch (\Throwable $e) {}
    }

    public static function recordConsultation(Consultation $consultation): void
    {
        try {
            $registry = self::getRegistryData();

            $registry[$consultation->id] = [
                'id' => $consultation->id,
                'patient_id' => $consultation->patient_id,
                'doctor_id' => $consultation->doctor_id,
                'status' => $consultation->status,
                'diagnosis' => $consultation->diagnosis,
                'prescription' => $consultation->prescription,
                'notes' => $consultation->notes,
                'updated_at' => date('Y-m-d H:i:s'),
            ];

            // 1. Save to File
            try {
                $path = self::getFilePath();
                File::put($path, json_encode($registry, JSON_PRETTY_PRINT));
            } catch (\Throwable $e) {}

            // 2. Save to Cookie
            try {
                $payload = base64_encode(json_encode($registry));
                Cookie::queue(cookie()->make('koline_consultation_states', $payload, 60 * 24 * 30, '/', null, false, false));
                if (!headers_sent()) {
                    setcookie('koline_consultation_states', $payload, time() + (60 * 24 * 30 * 60), '/', '', false, false);
                }
            } catch (\Throwable $e) {}

        } catch (\Throwable $e) {}
    }
}
