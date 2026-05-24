<?php

namespace App\Services\Chatbot;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
class ChatbotService
{
    protected $chatbotUrl;

    public function __construct()
    {
        $this->chatbotUrl = config('services.chatbot.url', 'http://localhost:5000');
    }

    public function sendMessage(string $message): array
    {
        try {
            $response = Http::timeout(30)->post("{$this->chatbotUrl}/chat", [
                'message' => $message
            ]);

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('Chatbot API error', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            return [
                'response' => 'Sorry, the chatbot service is currently unavailable.',
                'status' => 'error'
            ];
        } catch (\Exception $e) {
            Log::error('Chatbot connection error', ['error' => $e->getMessage()]);

            return [
                'response' => 'Unable to connect to chatbot service.',
                'status' => 'error'
            ];
        }
    }
}
