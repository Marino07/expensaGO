<?php

namespace App\Livewire\Chat;

use Livewire\Component;

class Chat extends Component
{
    public $message;
    public $responses = [];

    public function sendMessage()
    {
        try {
            $client = \OpenAI::client(env('OPENAI_API_KEY'));

            $response = $client->completions()->create([
                'model' => 'davinci',
                'prompt' => $this->message,
                'max_tokens' => 150,
            ]);

            $this->responses[] = $response['choices'][0]['text'];
        } catch (\Exception $e) {
            //sumulirani odgovor kada dodje do greske
            $this->responses[] = "Simulirani odgovor: Trenutno nismo u mogućnosti obraditi vaš zahtjev. Molimo provjerite svoj plan i detalje naplate.";
        }

        $this->message = '';
    }

    public function render()
    {
        return view('livewire.chat.chat', ['responses' => $this->responses]);
    }
}
