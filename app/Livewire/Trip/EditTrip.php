<?php

namespace App\Livewire\Trip;

use App\Models\Trip;
use Livewire\Component;

class EditTrip extends Component
{
    public Trip $trip;
    public $location;
    public $budget;
    public $start_date;
    public $end_date;
    public $description;

    protected $rules = [
        'location' => 'required|string|max:255',
        'budget' => 'required|numeric|min:0',
        'start_date' => 'required|date',
        'end_date' => 'required|date|after_or_equal:start_date',
        'description' => 'required|string'
    ];

    public function mount(Trip $trip)
    {
        $this->trip = $trip;
        $this->location = $trip->location;
        $this->budget = $trip->budget;
        $this->start_date = $trip->start_date;
        $this->end_date = $trip->end_date;
        $this->description = $trip->description;
    }

    public function updateTrip()
    {
        $validatedData = $this->validate();

        $this->trip->update($validatedData);

        session()->flash('message', 'Trip updated successfully!');

        return redirect()->route('edit-trip', $this->trip);
    }

    public function render()
    {
        return view('livewire.trip.edit-trip')->layout('layouts.trip');
    }
}
