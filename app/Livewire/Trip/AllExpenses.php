<?php

namespace App\Livewire\Trip;

use Livewire\Component;

class AllExpenses extends Component
{
    public function render()
    {
        return view('livewire.trip.all-expenses')->layout('layouts.trip');
    }
}
