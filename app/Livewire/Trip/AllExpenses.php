<?php

namespace App\Livewire\Trip;

use Livewire\Component;
use App\Models\Trip;
use App\Models\Category;
use Auth;
use Illuminate\Support\Facades\Http;

class AllExpenses extends Component
{

    public $search = '';
    public $selectedTrip = '';
    public $selectedCategory = '';
    public $environment;

    public function __construct()
    {
        $this->environment = env('PLAID_ENV');
    }

    public function logout()
    {
        Auth::logout(); // Odjava korisnika
        return redirect('/'); // Preusmeravanje na početnu stranicu
    }

    public function getCardExpenses()
    {
        $response = Http::post("https://{$this->environment}.plaid.com/transactions/get", [
            'client_id' => env('PLAID_CLIENT_ID'),
            'secret' => env('PLAID_SECRET'),
            'access_token' => auth()->user()->plaid_access_token,
            'start_date' => auth()->user()->created_at->format('Y-m-d'), // opc
            'end_date' => date('Y-m-d')
        ]);

        if ($response->successful()) {
            return $response->json()['transactions'];
        }

        return [];
    }

    public function render()
    {
        // Fetch ručno dodani troškovi
        $expenses = Auth::user()
            ->trips()
            ->with(['expenses.category'])
            ->latest()
            ->get()
            ->flatMap->expenses;

        // Fetch troškovi sa kartice
        $cardExpenses = collect($this->getCardExpenses());

        // Mapiranje troškova sa kartice u isti format kao ručno dodani troškovi
        $cardExpenses = $cardExpenses->map(function ($expense) {
            return (object) [
                'title' => $expense['name'],
                'amount' => $expense['amount'],
                'date' => \Carbon\Carbon::parse($expense['date']),
                'category' => (object) ['name' => $expense['category'][0] ?? 'Uncategorized'],
                'trip' => (object) ['name' => 'Card Expense'],
            ];
        });

        // Kombinovanje troškova
        $expenses = $expenses->merge($cardExpenses);

        // Filtriranje po tripu
        if ($this->selectedTrip) {
            $expenses = $expenses->where('trip_id', $this->selectedTrip);
        }

        // Filtriranje po kategoriji
        if ($this->selectedCategory) {
            $expenses = $expenses->where('category_id', $this->selectedCategory);
        }

        // Pretraga po naslovu
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
