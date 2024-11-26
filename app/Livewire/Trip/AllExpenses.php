<?php

namespace App\Livewire\Trip;

use Livewire\Component;
use App\Models\Trip;
use App\Models\Category;
use Auth;

class AllExpenses extends Component
{
    public $search = '';
    public $selectedTrip = '';
    public $selectedCategory = '';
    
    public function render()
    {
        $expenses = Auth::user()
            ->trips()
            ->with(['expenses.category'])
            ->get()
            ->flatMap->expenses;

        if ($this->selectedTrip) {
            $expenses = $expenses->where('trip_id', $this->selectedTrip);
        }

        if ($this->selectedCategory) {
            $expenses = $expenses->where('category_id', $this->selectedCategory);
        }

        if ($this->search) {
            $expenses = $expenses->filter(function($expense) {
                return str_contains(strtolower($expense->title), strtolower($this->search));
            });
        }

        $trips = Auth::user()->trips;
        $categories = Category::all();

        return view('livewire.trip.all-expenses', [
            'expenses' => $expenses,
            'trips' => $trips,
            'categories' => $categories,
        ])->layout('layouts.trip');
    }
}
