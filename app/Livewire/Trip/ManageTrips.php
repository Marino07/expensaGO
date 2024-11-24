<?php

namespace App\Livewire\Trip;

use Livewire\Component;

class ManageTrips extends Component
{
    public function render()
    {
        return view('livewire.trip.manage-trips')->layout('layouts.trip');
    }
}
