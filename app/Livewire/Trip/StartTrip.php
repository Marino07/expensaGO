<?php

namespace App\Livewire\Trip;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class StartTrip extends Component
{
    public function logout()
    {
        Auth::logout(); // Odjava korisnika
        return redirect('/'); // Preusmeravanje na početnu stranicu
    }
    public function render()
    {
        return view('livewire.trip.start-trip')->layout('layouts.trip');
    }
}
