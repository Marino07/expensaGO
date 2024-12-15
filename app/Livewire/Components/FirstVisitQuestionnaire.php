<?php
namespace App\Livewire\Components;

use Livewire\Component;
use App\Models\UserPreference;

class FirstVisitQuestionnaire extends Component
{
    public $trip;
    public $showQuestionnaire = false;
    public $preferences = [
        'attractions' => false,
        'events' => false,
        'restaurants' => false,
        'localCuisine' => false,
        'shopping' => false,
        'nature' => false
    ];

    public function mount()
    {
        $this->trip = auth()->user()->lastTrip();
        // Check if user has already completed the questionnaire
        $userPreferences = UserPreference::where('trip_id', $this->trip->id)
            ->where('has_completed_questionnaire', true)
            ->first();

        $this->showQuestionnaire = !$userPreferences;
    }

    public function savePreferences()
    {
        if (!$this->trip) {
            return;
        }

        UserPreference::updateOrCreate(
            ['trip_id' => $this->trip->id],
            [
                'trip_id' => $this->trip->id, // Explicitly set trip_id here as well
                'preferences' => $this->preferences,
                'has_completed_questionnaire' => true
            ]
        );

        $this->showQuestionnaire = false;
        $this->dispatch('preferences-saved');
    }

    public function render()
    {
        return view('livewire.components.first-visit-questionnaire');
    }
}
