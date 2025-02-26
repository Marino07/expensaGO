<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Trip;
use App\Jobs\SyncPlaidTransactions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PlaidWebhookController extends Controller
{
    public function handleWebhook(Request $request)
    {
        Log::info('Plaid webhook received', $request->all());

        if ($request->webhook_type === 'TRANSACTIONS' &&
            $request->webhook_code === 'SYNC_UPDATES_AVAILABLE') {

            $user = User::where('plaid_item_id', $request->item_id)->first();

            if ($user) {
                $latestTrip = Trip::where('user_id', $user->id)
                    ->latest()
                    ->first();

                if ($latestTrip) {
                    Log::info('Dispatching SyncPlaidTransactions job for user:', [
                        'user_id' => $user->id,
                        'trip_start' => $latestTrip->start_date,
                        'trip_end' => $latestTrip->end_date
                    ]);

                    SyncPlaidTransactions::dispatch($user, $latestTrip->start_date, $latestTrip->end_date);
                    return response()->json(['status' => 'success']);
                } else {
                    Log::error('No trips found for user:', ['user_id' => $user->id]);
                    return response()->json(['status' => 'ignored']);
                }
            } else {
                Log::error('User not found for item_id:', ['item_id' => $request->item_id]);
                return response()->json(['status' => 'ignored']);
            }
        }
        return response()->json(['status' => 'ignored']);
    }
}
