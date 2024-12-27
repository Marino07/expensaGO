<?php

namespace App\Livewire\Chat;

use Livewire\Component;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class Chat extends Component
{
    public $message;
    public $responses = [];

    private function getApiUrl()
    {
        return config('services.ollama.url', 'http://127.0.0.1:11434');
    }

    public function sendMessage()
    {
        if (empty($this->message)) {
            return;
        }

        try {
            $apiUrl = $this->getApiUrl();
            Log::info('Attempting API request', [
                'url' => $apiUrl,
                'message' => $this->message,
                'curl_version' => curl_version()
            ]);

            $response = Http::timeout(config('services.ollama.timeout', 30))
                ->retry(3, 100, function ($exception) {
                    Log::warning('Retrying due to error', [
                        'error' => $exception->getMessage(),
                        'class' => get_class($exception)
                    ]);
                    return true;
                })
                ->withHeaders([
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json'
                ])
                ->post($apiUrl . '/api/generate', [
                    'model' => 'qwen2.5-coder:0.5b',
                    'prompt' => $this->message,
                    'stream' => false
                ]);

            Log::info('Raw response', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            if ($response->successful()) {
                $responseData = $response->json();
                $this->responses[] = $responseData['response'] ?? 'No response received';
            } else {
                throw new \Exception('API request failed: ' . $response->status() . ' - ' . $response->body());
            }
        } catch (\Exception $e) {
            Log::error('Chat error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'url' => $this->getApiUrl()
            ]);
            $this->responses[] = "Connection error. Please try again or check the logs.";
        }

        $this->message = '';
    }

    public function render()
    {
        return view('livewire.chat.chat', ['responses' => $this->responses]);
    }
}
