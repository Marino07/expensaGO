<?php

namespace App\Livewire\Event;

use Livewire\Component;

class Event extends Component
{
    public function render()
    {
        return view('livewire.event.event')->layout('layouts.event');
    }
}
