<?php

namespace App\Livewire\Application;

use App\Models\Trip;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Application extends Component
{
    public $Budget;
    public $AllExpenses;
    public function mount()
    {
        $trip = Trip::where('user_id', Auth::user()->id)->latest()->first();
        $this->Budget = $trip->budget;
        $this->AllExpenses = $trip->expenses->sum('amount');
    }
    public function logout()
    {
        Auth::logout(); // Odjava korisnika
        return redirect('/'); // Preusmeravanje na početnu stranicu
    }
    public function render()
    {
        $user = Auth::user();
        $lastExpenses = $user->Lastexpenses();
        return view('livewire.application.application', ['lastExpenses' => $lastExpenses])->layout('layouts.application');
    }
}
