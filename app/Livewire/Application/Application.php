<?php

namespace App\Livewire\Application;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Application extends Component
{
    public function logout()
    {
        Auth::logout(); // Odjava korisnika
        return redirect('/'); // Preusmeravanje na početnu stranicu
    }
    public function render()
    {
        return view('livewire.application.application')->layout('layouts.application');
    }
}
