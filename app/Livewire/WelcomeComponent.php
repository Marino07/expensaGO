<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class WelcomeComponent extends Component
{
    public $ime;
    public function mount(){
        $this->ime = 'Marino';
    }
    public function logout()
    {
        Auth::logout(); // Odjava korisnika
        return redirect('/'); // Preusmeravanje na početnu stranicu
    }
    public function render()
    {
        return view('livewire.welcome-component')->layout('layouts.welc_layout');
    }
}
