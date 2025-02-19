<?php

namespace App\Livewire\Trip;

use App\Models\Trip;
use Livewire\Component;
use App\Events\OpenQEvent;
use App\Models\SuggestionImages;
use App\Jobs\ExecuteLivewireLogic;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\Console\Completion\Suggestion;

class StartTrip extends Component
{
    public $location;
    public $start_date;
    public $end_date;
    public $budget;
    public $description;

    protected $rules = [
        'location' => 'required|string|max:255',
        'start_date' => 'required|date',
        'end_date' => 'nullable|date|after_or_equal:start_date',
        'budget' => 'required|integer|min:0',
        'description' => 'nullable|string',
    ];

    public function submit()
    {
        $this->validate();

        $newTrip = Trip::create([
            'user_id' => Auth::id(),
            'location' => $this->location,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'budget' => $this->budget,
            'description' => $this->description,
        ]);

        if ($newTrip) {
            SuggestionImages::where('user_id', Auth::id())->delete();
            ExecuteLivewireLogic::dispatch($newTrip->id, Auth::id());

            session()->flash('message', 'Your trip "' . $this->location . '" has been successfully created. Get ready for an amazing adventure!');
            session()->put('openFirst', true);
            session()->flash('tripCreated', true);

            return redirect()->route('app');
        }

        session()->flash('error', 'There was an error creating your trip.');
        return null;
    }

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
