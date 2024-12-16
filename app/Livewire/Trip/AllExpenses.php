<?php

namespace App\Livewire\Trip;

use Livewire\Component;
use App\Models\Trip;
use App\Models\Category;
use App\Models\Expense; // Add this import
use Auth;
use Illuminate\Support\Facades\Http;

class AllExpenses extends Component
{

    public $search = '';
    public $selectedTrip = '';
    public $selectedCategory = '';
    public $environment;
    public $trip;


    public function mount(){
        $this->trip = Trip::where('user_id',auth()->id())->latest()->first();
    }
    public function __construct()
    {
        $this->environment = env('PLAID_ENV');
    }

    public function logout()
    {
        Auth::logout(); // Odjava korisnika
        return redirect('/'); // Preusmeravanje na početnu stranicu
    }

    public function createExpense($expense)
    {
        if (strtolower($expense['category'][0] ?? '') === 'transfer') {
            \Log::info('Skipping transfer transaction');
            return;
        }

        try {
            // Check if expense already exists
            $existingExpense = Expense::where([
                'title' => $expense['name'],
                'amount' => $expense['amount'],
                'date' => \Carbon\Carbon::parse($expense['date']),
                'trip_id' => $this->trip->id
            ])->first();

            if (!$existingExpense) {
                \Log::info('Creating new expense:', $expense);

                Expense::create([
                    'title' => $expense['name'],
                    'amount' => $expense['amount'],
                    'date' => \Carbon\Carbon::parse($expense['date']),
                    'category_id' => Category::firstOrCreate(['name' => $expense['category'][0] ?? 'Uncategorized'])->id,
                    'trip_id' => $this->trip->id,
                    'is_recurring' => 0
                ]);
            } else {
                \Log::info('Expense already exists, skipping');
            }
        } catch (\Exception $e) {
            \Log::error('Error creating expense:', [
                'error' => $e->getMessage(),
                'expense' => $expense
            ]);
        }
    }

    public function getCardExpenses()
    {
        \Log::info('Attempting to fetch card expenses');

        $response = Http::post("https://{$this->environment}.plaid.com/transactions/get", [
            'client_id' => env('PLAID_CLIENT_ID'),
            'secret' => env('PLAID_SECRET'),
            'access_token' => auth()->user()->plaid_access_token,
            'start_date' => auth()->user()->created_at->format('Y-m-d'), // opc
            'end_date' => date('Y-m-d')
        ]);

        \Log::info('Plaid API Response:', [
            'status' => $response->status(),
            'body' => $response->json()
        ]);

        if ($response->successful()) {
            $transactions = $response->json()['transactions'];
            \Log::info('Found transactions:', ['count' => count($transactions)]);

            foreach ($transactions as $transaction) {
                \Log::info('Processing transaction:', $transaction);
                $this->createExpense($transaction);
            }
            return $transactions;
        }

        \Log::error('Failed to fetch transactions:', [
            'status' => $response->status(),
            'body' => $response->json()
        ]);

        return [];
    }

    public function render()
    {
        // Fetch card expenses and store them in the database
        //$this->getCardExpenses();

        // Fetch all expenses from the database
        $expenses = Expense::with(['category', 'trip'])
            ->where('trip_id', $this->trip->id)
            ->latest()
            ->get();

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
