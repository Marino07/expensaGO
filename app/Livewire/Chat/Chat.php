<?php

namespace App\Livewire\Chat;

use Livewire\Component;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class Chat extends Component
{
    public $message;
    public $messages = [];
    public $isTyping = false;

    private function getApiUrl()
    {
        //return config('services.ollama.url', 'http://127.0.0.1:11434');

        return 'http://127.0.0.1:11434'; // there is not port mapping in docker-compose

    }

    public function sendMessage()
    {
        if (empty($this->message)) {
            return;
        }

        // Store message and clear input immediately
        $userMessage = $this->message;
        $this->messages[] = [
            'type' => 'user',
            'content' => $userMessage
        ];
        $this->message = '';

        // Force Livewire to render immediately
        $this->dispatch('messageAdded');

        $aiMessageIndex = count($this->messages);
        // Create empty AI response
        $this->messages[] = [
            'type' => 'ai',
            'content' => ''
        ];

        $this->isTyping = true;

        // Log the API request details
        Log::info('Sending API request', [
            'url' => $this->getApiUrl() . '/api/generate',
            'message' => $userMessage
        ]);

        // Make the API call without blocking
        $this->js("
            fetch('{$this->getApiUrl()}/api/generate', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    model: 'qwen2.5-coder:0.5b',
                    prompt: '$userMessage',
                    stream: true
                })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok: ' + response.statusText);
                }
                const reader = response.body.getReader();
                const decoder = new TextDecoder();

                function readChunk() {
                    reader.read().then(({value, done}) => {
                        if (done) {
                            \$wire.completeResponse();
                            return;
                        }

                        const chunk = decoder.decode(value);
                        const lines = chunk.split('\\n');

                        lines.forEach(line => {
                            if (line.trim()) {
                                try {
                                    const data = JSON.parse(line);
                                    if (data.response) {
                                        \$wire.appendToResponse($aiMessageIndex, data.response);
                                    }
                                } catch (e) {
                                    console.error('Error parsing line:', line, e);
                                }
                            }
                        });

                        readChunk();
                    }).catch(error => {
                        console.error('Error reading chunk:', error);
                        \$wire.logError('Error reading chunk: ' + error.message);
                    });
                }

                readChunk();
            }).catch(error => {
                console.error('Fetch error:', error);
                \$wire.logError('Fetch error: ' + error.message);
            });
        ");
    }

    public function appendToResponse($index, $text)
    {
        $this->messages[$index]['content'] .= $text;
    }

    public function completeResponse()
    {
        $this->isTyping = false;
    }

    public function logError($message)
    {
        Log::error($message);
    }

    public function render()
    {
        return view('livewire.chat.chat');
    }
}
