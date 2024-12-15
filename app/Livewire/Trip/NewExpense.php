<?php

namespace App\Livewire\Trip;

use App\Models\Trip;
use App\Models\Expense;
use Livewire\Component;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;

class NewExpense extends Component
{
    public $tripId;
    public $expenseTitle;
    public $expenseDate;
    public $amount;
    public $category_id;
    public $isRecurring;
    public $test;
    protected $listeners = ['OpenQ' => 'test'];
    public function  mount(){
        $this->isRecurring = 0;
    }


    protected $rules = [
        'tripId' => 'required|exists:trips,id',
        'expenseTitle' => 'required|string|max:255',
        'expenseDate' => 'required|date|after_or_equal:start_date',
        'amount' => 'required|numeric|min:0',
    ];

    public function submit()
    {
        $this->validate();

        Expense::create([
            'trip_id' => $this->tripId,
            'title' => $this->expenseTitle,
            'amount' => $this->amount,
            'date' => $this->expenseDate,
            'is_recurring' => $this->isRecurring,
            'category_id' => $this->category_id,
        ]);

        // Attach the category to the trip if it's not already attached
        $trip = Trip::find($this->tripId);
        if (!$trip->categories()->where('category_id', $this->category_id)->exists()) {
            $trip->categories()->attach($this->category_id);
        }

        session()->flash('message', 'Expense added successfully.');
        return redirect()->route('new-expense');
    }

    public function logout()
    {
        Auth::logout(); // Odjava korisnika
        return redirect()->route('new-expense');
    }
    public function test(){
        $this->test = 'test';
    }
    public function render()
    {
        $categories = Category::all();
        $trips = Trip::where('user_id',Auth::id())->get();
        return view('livewire.trip.new-expense', compact('trips','categories'))->layout('layouts.trip');
    }
}
