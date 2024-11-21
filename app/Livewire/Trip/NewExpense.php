<?php

namespace App\Livewire\Trip;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class NewExpense extends Component
{

    public function logout()
    {
        Auth::logout(); // Odjava korisnika
        return redirect('/'); // Preusmeravanje na početnu stranicu
    }
    public function render()
    {
        return view('livewire.trip.new-expense')->layout('layouts.trip');
    }
}
