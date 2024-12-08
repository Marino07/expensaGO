<?php
namespace App\Livewire\Components;

use Livewire\Component;
use App\Models\UserPreference;

class FirstVisitQuestionnaire extends Component
{
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
        // Check if user has already completed the questionnaire
        $userPreferences = UserPreference::where('user_id', auth()->id())
            ->where('has_completed_questionnaire', true)
            ->first();

        $this->showQuestionnaire = !$userPreferences;
    }

    public function savePreferences()
    {
        UserPreference::updateOrCreate(
            ['user_id' => auth()->id()],
            [
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
