<?php

namespace App\Livewire\Trip;

use App\Models\Trip;
use Livewire\Component;

class EditTrip extends Component
{
    public Trip $trip;
    public $showEndTripModal = false;
    public $endTripReason = '';
    public $destinations = ['Paris', 'Rome', 'Barcelona'];

    protected $rules = [
        'trip.name' => 'required|string|max:255',
        'trip.budget' => 'required|numeric|min:0',
        'trip.start_date' => 'required|date',
        'trip.end_date' => 'nullable|date|after_or_equal:trip.start_date',
        'trip.description' => 'nullable|string',
        'trip.status' => 'required|in:active,completed',
    ];

    public function mount(Trip $trip)
    {
        $this->trip = $trip;
    }

    public function updateTrip()
    {
        $this->validate();
        $this->trip->save();
        session()->flash('message', 'Trip updated successfully.');
    }

    public function endTrip()
    {
        $this->validate([
            'endTripReason' => 'required|string|min:5'
        ]);

        $this->trip->end_date = now();
        $this->trip->status = 'completed';
        $this->trip->end_reason = $this->endTripReason;
        $this->trip->save();

        $this->showEndTripModal = false;
        $this->endTripReason = '';

        session()->flash('message', 'Trip ended successfully.');
    }    public function render()
    {
        return view('livewire.trip.edit-trip')->layout('layouts.trip');
    }
}
