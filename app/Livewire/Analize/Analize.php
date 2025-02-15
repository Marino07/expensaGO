<?php

namespace App\Livewire\Analize;

use Livewire\Component;

class Analize extends Component
{
    public $Budget;
    public $AllExpenses;
    public $categoryExpenses = [];
    public $categoryNames = [];
    public $hasExpenses = false;
    public $trip;

    public function mount()
    {
        $this->trip = \App\Models\Trip::where('user_id', \Auth::user()->id)->latest()->first();
        
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
            $this->categoryNames = [];
            $this->categoryExpenses = [];

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
    }

    public function render()
    {
        return view('livewire.analize.analize', [
            'categoryExpenses' => $this->categoryExpenses,
            'categoryNames' => $this->categoryNames
        ])->layout('layouts.analize');
    }
}
