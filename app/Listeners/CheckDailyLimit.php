<?php

namespace App\Listeners;

use Carbon\Carbon;
use App\Models\Trip;
use App\Models\Expense;
use App\Mail\DailyLimitExceeded;
use App\Events\TransactionCreated;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\DailyExceedLog;

class CheckDailyLimit implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(TransactionCreated $event): void
    {
        Log::info('CheckDailyLimit listener started.');

        $user = $event->user;
        Log::info('User ID: ' . $user->id);

        $trip = Trip::where('user_id', $user->id)
            ->where('status', 'active')
            ->latest()
            ->first();
        Log::info('Trip ID: ' . ($trip ? $trip->id : 'No active trip found'));

        if (!$trip) {
            Log::error('No active trip found for user ID: ' . $user->id);
            return;
        }

        $dailyLimit = $trip->budget / abs($trip->duration);
        Log::info('Daily Limit: ' . $dailyLimit);

        $today = Carbon::today();
        Log::info('Today: ' . $today);

        $totalSpentToday = Expense::where('trip_id', $trip->id)
            ->whereDate('created_at', $today)
            ->sum('amount');
        Log::info('Total Spent Today: ' . $totalSpentToday);

        if ($totalSpentToday > $dailyLimit * 1.15) {
            $alreadyNotified = DailyExceedLog::where('user_id', $user->id)
                ->where('trip_id', $trip->id)
                ->whereDate('logged_date', $today)
                ->exists();

            if (!$alreadyNotified) {
                Mail::to($user->email)->send(new DailyLimitExceeded($user, $totalSpentToday));
                DailyExceedLog::create([
                    'user_id' => $user->id,
                    'trip_id' => $trip->id,
                    'logged_date' => $today
                ]);
            }
        } else {
            Log::info('Daily limit not exceeded.');
        }
    }
}
