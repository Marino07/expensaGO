<?php

namespace App\Http\Livewire\Chat;

use Livewire\Component;

class Chat extends Component
{
    public $message;
    public $responses = [];

    public function sendMessage()
    {
        if (empty($this->message)) {
            return;
        }

        // Add user message
        $this->responses[] = $this->message;

        // Simulate AI response (replace with actual AI integration)
        $this->responses[] = "I'm your AI travel assistant. I'm here to help you with your trip!";

        $this->message = '';
    }

    public function render()
    {
        return view('livewire.chat.chat');
    }
}
