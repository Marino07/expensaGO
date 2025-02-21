<?php

namespace App\Livewire\Analize;

use Carbon\Carbon;
use App\Models\Trip;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
class Analize extends Component
{
    public $Budget;
    public $AllExpenses;
    public $categoryExpenses = [];
    public $categoryNames = [];
    public $hasExpenses = false;
    public $trip;
    public $dailyLabels = [];
    public $dailyExpenses = [];
    public $dailyCategories = [];
    public $dailyCategoryData = []; // [ 'Food' => [..], 'Transport' => [..], ... ]

    public function mount()
    {
        $this->trip = Trip::where('user_id', \Auth::user()->id)->latest()->first();

        if ($this->trip) {
            $this->initializeBasicData();
            $this->processCategoryData();
            $this->processDailyExpenses();
            $this->processDailyCategoryExpenses();
        }
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/');
    }


    private function initializeBasicData()
    {
        $this->Budget = $this->trip->budget;
        $this->AllExpenses = $this->trip->expenses->sum('amount');
    }

    private function processCategoryData()
    {
        // Get categories with expenses for this trip
        $expensesByCategory = $this->trip->expenses()
            ->select('category_id')
            ->selectRaw('SUM(amount) as total_amount')
            ->groupBy('category_id')
            ->with('category')
            ->get();

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

    private function processDailyExpenses()
    {
        $startDate = Carbon::parse($this->trip->start_date);
        $duration = $this->trip->duration;

        for ($day = 0; $day < $duration; $day++) {
            $currentDay = $startDate->copy()->addDays($day);
            $this->dailyLabels[] = $currentDay->format('M d');
            $this->dailyExpenses[] = (float) $this->trip->expenses()
                ->whereDate('created_at', $currentDay)
                ->sum('amount');
        }
    }

    private function processDailyCategoryExpenses()
    {
        $expenses = $this->trip->expenses;
        $startDate = Carbon::parse($this->trip->start_date);
        $duration = $this->trip->duration;

        // Get unique category names
        $this->dailyCategories = $expenses->pluck('category.name')->unique()->values()->all();

        // Group expenses by date (key: YYYY-MM-DD)
        $expensesByDate = $expenses->groupBy(function ($expense) {
            return Carbon::parse($expense->created_at)->format('Y-m-d');
        });

        $this->initializeDailyCategoryData();
        $this->populateDailyCategoryData($startDate, $duration, $expensesByDate);
    }

    private function initializeDailyCategoryData()
    {
        foreach ($this->dailyCategories as $cat) {
            $this->dailyCategoryData[$cat] = [];
        }
    }

    private function populateDailyCategoryData($startDate, $duration, $expensesByDate)
    {
        for ($day = 0; $day < $duration; $day++) {
            $currentDate = $startDate->copy()->addDays($day)->format('Y-m-d');

            // Group daily expenses by category
            $grouped = isset($expensesByDate[$currentDate])
                ? $expensesByDate[$currentDate]->groupBy('category.name')
                : collect();

            foreach ($this->dailyCategories as $cat) {
                $sum = isset($grouped[$cat])
                    ? (float) $grouped[$cat]->sum('amount')
                    : 0;
                $this->dailyCategoryData[$cat][] = $sum;
            }
        }
    }

    public function render()
    {
        return view('livewire.analize.analize', [
            'categoryNames' => $this->categoryNames,
            'categoryExpenses' => $this->categoryExpenses,
            'dailyLabels' => $this->dailyLabels,
            'dailyExpenses' => $this->dailyExpenses,
            'dailyCategories' => $this->dailyCategories,
            'dailyCategoryData' => $this->dailyCategoryData,
        ])->layout('layouts.analize');
    }
}
