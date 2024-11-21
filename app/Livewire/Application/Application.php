<?php

namespace App\Livewire\Application;

use Livewire\Component;

class Application extends Component
{
    public function render()
    {
        return view('livewire.application.application')->layout('layouts.application');
    }
}
