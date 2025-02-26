<?php

namespace App\Jobs;

use App\Models\Trip;
use App\Models\User;
use App\Models\Expense;
use App\Models\Category;
use Illuminate\Bus\Queueable;
use App\Events\TransactionCreated;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Carbon\Carbon;

class SyncPlaidTransactions implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user;
    protected $startDate;
    protected $endDate;

    public function __construct(User $user, $startDate, $endDate)
    {
        $this->user = $user;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function handle()
    {
        Log::info('Starting SyncPlaidTransactions job for user:', [
            'user_id' => $this->user->id,
            'start_date' => $this->startDate,
            'end_date' => $this->endDate
        ]);

        $cursor = $this->user->plaid_cursor ?? '';

        Log::info('Current plaid_cursor:', ['plaid_cursor' => $cursor]);

        $response = Http::post("https://" . env('PLAID_ENV') . ".plaid.com/transactions/sync", [
            'client_id' => env('PLAID_CLIENT_ID'),
            'secret' => env('PLAID_SECRET'),
            'access_token' => $this->user->plaid_access_token,
            'cursor' => $cursor
        ]);

        if ($response->successful()) {
            $data = $response->json();

            Log::info('Plaid sync response:', $data);

            if (isset($data['next_cursor'])) {
                Log::info('Next cursor found in response:', ['next_cursor' => $data['next_cursor']]);
            } else {
                Log::warning('Next cursor not found in response.');
            }

            foreach ($data['added'] as $transaction) {
                Log::info('Processing transaction ID:', ['transaction_id' => $transaction['transaction_id']]);
                $this->processTransaction($transaction);
            }

            $this->user->plaid_cursor = $data['next_cursor'];
            $this->user->name = 'marino';
            if ($this->user->save()) {
                Log::info('Updated plaid_cursor for user:', ['user_id' => $this->user->id, 'next_cursor' => $data['next_cursor']]);
            } else {
                Log::error('Failed to update plaid_cursor for user:', ['user_id' => $this->user->id]);
            }
        } else {
            Log::error('Plaid sync error:', $response->json());
        }
    }

    protected function processTransaction($transaction)
    {
        Log::info('Processing transaction:', $transaction);

        // Skip transfers and payments
        if (in_array('Transfer', $transaction['category'] ?? []) || in_array('Payment', $transaction['category'] ?? [])) {
            Log::info('Skipping transfer/payment transaction:', ['transaction_id' => $transaction['transaction_id']]);
            return;
        }

        $transactionDate = Carbon::parse($transaction['date']);
        Log::info('Date checking engine', ['date' => $transaction['date']]);

        $startDate = Carbon::parse($this->startDate);
        $endDate = Carbon::parse($this->endDate);

        if ($transactionDate->lt($startDate) || $transactionDate->gt($endDate)) {
            Log::info('Skipping transaction outside trip dates:', [
                'transaction_id' => $transaction['transaction_id'],
                'transaction_date' => $transactionDate->toDateString(),
                'trip_start' => $startDate->toDateString(),
                'trip_end' => $endDate->toDateString()
            ]);
            return;
        }

        $category = Category::firstOrCreate([
            'name' => $transaction['category'][0] ?? 'Other'
        ]);
        $last_active_trip = Trip::where('user_id', $this->user->id)->where('status', 'active')->latest()->first();

        if ($last_active_trip) {
            Log::info('Last active', $last_active_trip->toArray());
            $expense = Expense::create([
                'trip_id' => $last_active_trip->id,
                'title' => $transaction['merchant_name'] ?? $transaction['name'],
                'amount' => $transaction['amount'],
                'category_id' => $category->id,
                'date' => $transaction['date'],
            ]);
            event(new TransactionCreated($expense, $this->user));



            Log::info('Expense created:', $expense->toArray());
        }

    }
}
