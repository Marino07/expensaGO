<?php

namespace App\Livewire\Trip;

use Livewire\Component;

class EditTrip extends Component
{
    public function render()
    {
        return view('livewire.trip.edit-trip')->layout('layouts.trip');
    }
}
