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
        if($this->trip){
            $userPreferences = UserPreference::where('trip_id', $this->trip->id)
            ->where('has_completed_questionnaire', true)
            ->first();

        $this->showQuestionnaire = !$userPreferences;

        }
        // Check if user has already completed the questionnaire

    }

    public function hasAtLeastOneSelection()
    {
        return collect($this->preferences)->contains(true);
    }

    public function validateStep($step)
    {
        switch($step) {
            case 1:
                return $this->preferences['attractions'] || $this->preferences['events'];
            case 2:
                return $this->preferences['restaurants'] || $this->preferences['localCuisine'];
            case 3:
                return $this->preferences['shopping'] || $this->preferences['nature'];
            default:
                return true;
        }
    }

    public function savePreferences()
    {
        if (!$this->trip) {
            return;
        }

        // Validate all steps before saving
        if (!$this->validateStep(1) || !$this->validateStep(2) || !$this->validateStep(3)) {
            session()->flash('error', 'Please select at least one option from each category for a more personalized travel experience.');
            return;
        }

        if (!$this->hasAtLeastOneSelection()) {
            session()->flash('error', 'Please select at least one preference');
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
