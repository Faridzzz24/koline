<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AiChatController extends Controller
{
    /**
     * Process message sent to KoLine AI via Groq Llama Instant API.
     */
    public function message(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
            'history' => 'nullable|array',
        ]);

        $userMessage = trim($request->input('message'));
        $history = $request->input('history', []);

        $apiKey = config('services.groq.api_key');
        $model = config('services.groq.model', 'llama-3.1-8b-instant');

        if (!$apiKey) {
            return response()->json([
                'status' => 'error',
                'reply' => 'Layanan KoLine AI belum terkonfigurasi API Key secara lengkap.',
            ], 500);
        }

        // Medical System Prompt
        $systemMessage = [
            'role' => 'system',
            'content' => "Anda adalah 'KoLine AI', asisten medis terpercaya dari KoLine (Konsultasi Online).
Tugas: Memberikan jawaban medis ringkas, empati, dan bermanfaat dalam Bahasa Indonesia.
Aturan:
1. Berikan penjelasan ringkas (maksimal 2-3 paragraf pendek atau poin-poin).
2. Jika pengguna mengeluhkan sakit/gejala, berikan saran awal yang menenangkan dan aman.
3. Ingatkan di akhir jawaban secara ramah bahwa untuk diagnosis pasti, mereka bisa berkonsultasi dengan Dokter Spesialis KoLine."
        ];

        $messages = [$systemMessage];

        // Include last 4 messages for context without bloating request size
        if (!empty($history) && is_array($history)) {
            $recentHistory = array_slice($history, -4);
            foreach ($recentHistory as $chat) {
                if (isset($chat['role']) && isset($chat['content']) && in_array($chat['role'], ['user', 'assistant'])) {
                    $messages[] = [
                        'role' => $chat['role'],
                        'content' => (string) $chat['content']
                    ];
                }
            }
        }

        $messages[] = [
            'role' => 'user',
            'content' => $userMessage
        ];

        try {
            $response = Http::withoutVerifying()->withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type' => 'application/json',
            ])->timeout(12)->post('https://api.groq.com/openai/v1/chat/completions', [
                'model' => $model,
                'messages' => $messages,
                'temperature' => 0.5,
                'max_tokens' => 450,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $reply = $data['choices'][0]['message']['content'] ?? 'Maaf, KoLine AI belum dapat memproses pertanyaan Anda saat ini.';

                return response()->json([
                    'status' => 'success',
                    'reply' => $reply,
                ]);
            } else {
                Log::error('Groq API Error: ' . $response->body());
                return response()->json([
                    'status' => 'error',
                    'reply' => 'Maaf, terjadi kendala respon dari AI. Silakan ulangi pertanyaan Anda.',
                ], 500);
            }
        } catch (\Exception $e) {
            Log::error('Groq API Exception: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'reply' => 'Maaf, koneksi layanan KoLine AI mengalami kendala. Silakan coba lagi.',
            ], 500);
        }
    }
}
