<?php

namespace App\Livewire\Application;

use App\Models\Category;
use App\Models\Trip;
use Livewire\Component;
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
    public $hasExpenses = false; // Dodajemo ovu property

    public function mount()
    {
        $trip = Trip::where('user_id', Auth::user()->id)->latest()->first();
        $this->Budget = $trip->budget;
        $this->AllExpenses = $trip->expenses->sum('amount');
        $this->categories = $trip->categories;

        // Calculate expenses for each category
        foreach ($this->categories as $category) {
            $expenses = $trip->expenses()->where('category_id', $category->id)->sum('amount');
            if ($expenses > 0) {
                $this->hasExpenses = true;
                $this->categoryExpenses[] = $expenses;
                $this->categoryNames[] = $category->name;
            }
        }

        // Ako nema troškova, postavi default vrednosti
        if (!$this->hasExpenses) {
            $this->categoryNames = ['No Expenses Yet'];
            $this->categoryExpenses = [100];
        }
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
        return view('livewire.application.application', [
            'lastExpenses' => $lastExpenses,
            'categoryExpenses' => $this->categoryExpenses,
            'categoryNames' => $this->categoryNames
        ])->layout('layouts.application');
    }
}
