<?php

namespace App\Livewire\Trip;

use Livewire\Component;

class ManageTrips extends Component
{
    public function render()
    {
        $trips = auth()->user()->trips;
        return view('livewire.trip.manage-trips',compact('trips'))->layout('layouts.trip');
    }
}
