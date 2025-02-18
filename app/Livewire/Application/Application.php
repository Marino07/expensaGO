<?php

namespace App\Livewire\Application;

use App\Models\SuggestionImages;
use App\Models\Trip;
use App\Models\Expense;
use Livewire\Component;
use App\Models\Category;
use App\Models\SavedItem;
use App\Models\LocalEvent;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class Application extends Component
{
    public $Budget;
    public $AllExpenses;
    public $categories;
    public $expenseCategories;
    public $expenseCategory;
    public $categoryExpenses = [];
    public $categoryNames = [];
    public $trip;
    public $hasExpenses = false;
    public $openFirst = false;
    public $lastFiveExpenses = [];
    public $countSavedItems;
    public $suggestPlaces;
    public $suggestEvents;

    protected $listeners = ['echo:openq-channel,OpenQEvent' => 'OpenFirstVisitQuestionnaire'];

    public function mount()
    {
        $this->suggestEvents = SuggestionImages::whereNotNull('event_image')->latest()->limit(3)->pluck('event_image')->toArray();
        $this->suggestPlaces = SuggestionImages::whereNotNull('place_image')->latest()->limit(3)->pluck('place_image')->toArray();

        $this->openFirst = session('openFirst', false);
        session()->forget('openFirst'); // we clearing after using it

        Log::info('Mounting Application component and registering listeners.');
        $this->trip = Trip::where('user_id', Auth::user()->id)->latest()->first();
        if($this->trip){
            $this->Budget = $this->trip->budget;

            $this->AllExpenses = $this->trip->expenses->sum('amount');

            // Get categories that have expenses for this specific trip
            $expensesByCategory = $this->trip->expenses()
                ->select('category_id')
                ->selectRaw('SUM(amount) as total_amount')
                ->groupBy('category_id')
                ->with('category')
                ->get();

            // Reset arrays
            $this->categoryExpenses = [];
            $this->categoryNames = [];

            foreach ($expensesByCategory as $expense) {
                if ($expense->total_amount > 0) {
                    $this->hasExpenses = true;
                    $this->categoryExpenses[] = $expense->total_amount;
                    $this->categoryNames[] = $expense->category->name;
                }
            }

            if (!$this->hasExpenses) {
                $this->categoryNames = ['No Expenses Yet'];
                $this->categoryExpenses = [0];
            }
            $this->lastFiveExpenses = Expense::with('category')
            ->where('trip_id', $this->trip->id)
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($expense) {
                return [
                    'id' => $expense->id,
                    'category' => $expense->category?->name ?? 'Other',
                    'type' => $expense->title,
                    'amount' => $expense->amount,
                    'date' => $expense->date->format('M d, Y')
                ];
            });

            Log::info('Initial value of openFirst: ' . $this->openFirst);
            Log::info('Listeners registered: ' . json_encode($this->getListeners()));
            $this->countSavedItems = SavedItem::where('user_id', Auth::user()->id)->count();

        }

    }

    #[On('OpenQ')]
    public function OpenFirstVisitQuestionnaire()
    {
        Log::info('OpenFirstVisitQuestionnaire method triggered.');
        $this->openFirst = true;
        Log::info('Value of openFirst after triggering: ' . $this->openFirst);
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/');
    }

    public function render()
    {
        $user = Auth::user();
        $lastExpenses = $user->Lastexpenses();
        return view('livewire.application.application', [
            'lastExpenses' => $lastExpenses,
            'categoryExpenses' => $this->categoryExpenses,
            'categoryNames' => $this->categoryNames
        ])->layout('layouts.application');
    }
}
