<?php

namespace App\Services;

use App\Models\GeminiArchive;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeminiService
{
    protected string $apiUrl;
    protected string $apiKey;
    protected string $model;

    public function __construct()
    {
        $this->apiUrl = 'https://generativelanguage.googleapis.com/v1beta/models';
        $this->apiKey = config('services.gemini.key');
        $this->model = config('services.gemini.model', 'gemini-2.0-flash');
    }

    public function generateContent(string $prompt): array
    {
        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post("{$this->apiUrl}/{$this->model}:generateContent?key={$this->apiKey}", [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $prompt]
                        ]
                    ]
                ]
            ]);

            if ($response->successful()) {
                $responseData = $response->json();
                $textResponse = $responseData['candidates'][0]['content']['parts'][0]['text'] ?? 'No response text found';

                $archive = GeminiArchive::create([
                    'prompt' => $prompt,
                    'answer' => $textResponse,
                    'user_id' => auth()->id()
                ]);

                return [
                    'success' => true,
                    'data' => $archive
                ];
            }

            Log::error('Gemini API Error: ' . $response->body());
            return [
                'success' => false,
                'error' => 'API request failed'
            ];

        } catch (\Exception $e) {
            Log::error('Gemini Service Error: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => 'Service unavailable'
            ];
        }
    }

    public function getChatHistory()
    {
        return GeminiArchive::latest()
            ->take(20)
            ->get();
    }
}