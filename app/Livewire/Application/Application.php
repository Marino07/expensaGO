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
    public $hasExpenses = false;

    public function mount()
    {
        $trip = Trip::where('user_id', Auth::user()->id)->latest()->first();
        if (!$trip) {
            return redirect('/trips');
        }

        $this->Budget = $trip->budget;
        $this->AllExpenses = $trip->expenses->sum('amount');

        // Get categories that have expenses for this specific trip
        $expensesByCategory = $trip->expenses()
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
