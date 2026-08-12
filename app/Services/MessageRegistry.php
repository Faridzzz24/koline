<?php

namespace App\Services;

use App\Models\ConsultationMessage;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\File;

class MessageRegistry
{
    private static function getFilePath(int $consultationId): string
    {
        $dir = storage_path('app');
        if (!File::exists($dir)) {
            @File::makeDirectory($dir, 0777, true, true);
        }
        return storage_path("app/consultation_messages_{$consultationId}.json");
    }

    private static function getMessagesData(int $consultationId): array
    {
        $messages = [];

        // 1. Load from file if exists
        try {
            $path = self::getFilePath($consultationId);
            if (File::exists($path)) {
                $fileData = json_decode(File::get($path), true);
                if (is_array($fileData)) {
                    $messages = array_merge($messages, $fileData);
                }
            }
        } catch (\Throwable $e) {}

        // 2. Load from HTTP cookie if exists (Vercel cross-container sync)
        try {
            $cookieKey = "koline_chat_msgs_{$consultationId}";
            $rawCookie = request()->cookie($cookieKey) ?? ($_COOKIE[$cookieKey] ?? null);
            if ($rawCookie) {
                if (is_string($rawCookie) && substr($rawCookie, 0, 1) !== '[') {
                    $decoded = @base64_decode($rawCookie);
                    if ($decoded) {
                        $rawCookie = $decoded;
                    }
                }
                $cookieData = is_array($rawCookie) ? $rawCookie : json_decode($rawCookie, true);
                if (is_array($cookieData)) {
                    foreach ($cookieData as $item) {
                        $messages[] = $item;
                    }
                }
            }
        } catch (\Throwable $e) {}

        // Deduplicate messages by sender_id + message + created_time
        $unique = [];
        foreach ($messages as $msg) {
            if (empty($msg['message']) || empty($msg['sender_id'])) continue;
            $key = $msg['sender_id'] . '_' . md5($msg['message']) . '_' . ($msg['created_at'] ?? '');
            $unique[$key] = $msg;
        }

        return array_values($unique);
    }

    public static function syncMessages(int $consultationId): void
    {
        try {
            $registry = self::getMessagesData($consultationId);
            if (empty($registry)) {
                return;
            }

            foreach ($registry as $data) {
                if (empty($data['message']) || empty($data['sender_id'])) {
                    continue;
                }

                $exists = ConsultationMessage::where('consultation_id', $consultationId)
                    ->where('sender_id', $data['sender_id'])
                    ->where('message', $data['message'])
                    ->exists();

                if (!$exists) {
                    ConsultationMessage::create([
                        'consultation_id' => $consultationId,
                        'sender_id' => $data['sender_id'],
                        'message' => $data['message'],
                        'type' => $data['type'] ?? 'text',
                        'is_read' => true,
                    ]);
                }
            }
        } catch (\Throwable $e) {}
    }

    public static function recordMessage(int $consultationId, ConsultationMessage $msg): void
    {
        try {
            $registry = self::getMessagesData($consultationId);

            $newMsg = [
                'id' => $msg->id,
                'consultation_id' => $consultationId,
                'sender_id' => (int) $msg->sender_id,
                'message' => $msg->message,
                'type' => $msg->type ?? 'text',
                'created_at' => $msg->created_at ? $msg->created_at->format('H:i') : date('H:i'),
            ];

            $registry[] = $newMsg;

            // Deduplicate
            $unique = [];
            foreach ($registry as $m) {
                if (empty($m['message']) || empty($m['sender_id'])) continue;
                $key = $m['sender_id'] . '_' . md5($m['message']) . '_' . ($m['created_at'] ?? '');
                $unique[$key] = $m;
            }
            $finalList = array_values($unique);

            // 1. Save to File
            try {
                $path = self::getFilePath($consultationId);
                File::put($path, json_encode($finalList, JSON_PRETTY_PRINT));
            } catch (\Throwable $e) {}

            // 2. Save to Cookie (Vercel cross-container sync)
            try {
                $payload = base64_encode(json_encode($finalList));
                $cookieKey = "koline_chat_msgs_{$consultationId}";
                Cookie::queue(cookie()->make($cookieKey, $payload, 60 * 24 * 7, '/', null, false, false));
                if (!headers_sent()) {
                    setcookie($cookieKey, $payload, time() + (60 * 24 * 7 * 60), '/', '', false, false);
                }
            } catch (\Throwable $e) {}

        } catch (\Throwable $e) {}
    }
}
