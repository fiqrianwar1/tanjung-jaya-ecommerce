<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GroqApiService
{
    protected string $apiKey;

    protected string $apiUrl;

    public function __construct()
    {
        $this->apiKey = config('services.groq.key') ?? '';
        $this->apiUrl = config('services.groq.url') ?? 'https://api.groq.com/openai/v1/chat/completions';
    }

    /**
     * Send a message to Groq API and get the response.
     *
     * @param  array  $messages  Array of messages in format [['role' => 'user', 'content' => '...']]
     * @param  string  $systemPrompt  Optional system prompt to set the context
     */
    public function sendMessage(array $messages, string $systemPrompt = ''): string
    {
        if (empty($this->apiKey)) {
            // Mock response for testing if no API key is provided
            return 'Maaf, layanan asisten AI belum dikonfigurasi (GROQ_API_KEY kosong). '.
                   'Silakan hubungi admin atau gunakan menu pencarian di katalog.';
        }

        // Prepare the payload
        $payloadMessages = [];

        if (! empty($systemPrompt)) {
            $payloadMessages[] = [
                'role' => 'system',
                'content' => $systemPrompt,
            ];
        }

        $payloadMessages = array_merge($payloadMessages, $messages);

        try {
            // Model utama dengan fallback bila model utama sedang tidak tersedia di Groq
            foreach ($this->models() as $model) {
                $response = Http::withToken($this->apiKey)
                    ->acceptJson()
                    ->timeout(45)
                    ->post($this->apiUrl, [
                        'model' => $model,
                        'messages' => $payloadMessages,
                        'temperature' => 0.7,
                        'max_tokens' => 700,
                        // Model reasoning Groq bisa boros token; batasi agar balasan cepat & tidak terpotong
                        'reasoning_effort' => 'low',
                    ]);

                if ($response->successful()) {
                    $content = trim((string) ($response->json('choices.0.message.content') ?? ''));

                    if ($content !== '') {
                        return $content;
                    }
                }

                Log::error("Groq API Error [{$model}]: ".$response->status().' '.$response->body());

                // 404/400 = nama model tidak dikenal, coba model berikutnya.
                // Selain itu (401, 429, 5xx) hentikan percobaan karena bukan masalah model.
                if (! in_array($response->status(), [400, 404], true)) {
                    break;
                }
            }

            return 'Maaf, asisten AI kami sedang sibuk. Silakan coba lagi sebentar lagi ya.';

        } catch (\Throwable $e) {
            Log::error('Groq API Exception: '.$e->getMessage());

            return 'Maaf, koneksi ke layanan AI terputus. Silakan coba lagi.';
        }
    }

    /**
     * Daftar model Groq yang dipakai berurutan (utama, lalu cadangan).
     *
     * @return array<int, string>
     */
    private function models(): array
    {
        $configured = (string) (config('services.groq.model') ?? '');

        return array_values(array_unique(array_filter([
            $configured !== '' ? $configured : 'openai/gpt-oss-120b',
            'openai/gpt-oss-20b',
        ])));
    }
}
