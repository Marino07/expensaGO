<?php

namespace App\Jobs;

use App\Models\Trip;
use App\Models\User;
use App\Models\Expense;
use App\Models\Category;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SyncPlaidTransactions implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function handle()
    {
        Log::info('Starting SyncPlaidTransactions job for user:', ['user_id' => $this->user->id]);

        $response = Http::post("https://" . env('PLAID_ENV') . ".plaid.com/transactions/sync", [
            'client_id' => env('PLAID_CLIENT_ID'),
            'secret' => env('PLAID_SECRET'),
            'access_token' => $this->user->plaid_access_token,
            'cursor' => $this->user->plaid_cursor
        ]);

        if ($response->successful()) {
            $data = $response->json();

            // Log the response for debugging
            Log::info('Plaid sync response:', $data);

            // Process new transactions
            foreach ($data['added'] as $transaction) {
                $this->processTransaction($transaction);
            }

            // Update cursor
            $this->user->update(['plaid_cursor' => $data['next_cursor']]);
            Log::info('Updated plaid_cursor for user:', ['user_id' => $this->user->id, 'next_cursor' => $data['next_cursor']]);
        } else {
            Log::error('Plaid sync error:', $response->json());
        }
    }

    protected function processTransaction($transaction)
    {
        Log::info('Processing transaction:', $transaction);

        // Skip transfers
        if (in_array('Transfer', $transaction['category'] ?? [])) {
            Log::info('Skipping transfer transaction:', $transaction['transaction_id']);
            return;
        }

        // Get or create category
        $category = Category::firstOrCreate([
            'name' => $transaction['category'][0] ?? 'Other'
        ]);

        // Create expense
        $expense = Expense::create([
            'user_id' => $this->user->id,
            'trip_id' => 1 ,
            'title' => $transaction['name'],
            'amount' => $transaction['amount'],
            'category_id' => 1,
            'date' => $transaction['date'],
        ]);

        Log::info('Expense created:', $expense);
    }
}
