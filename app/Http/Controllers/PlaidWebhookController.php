<?php

namespace App\Http\Controllers;

use App\Models\User;
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
                Log::info('Dispatching SyncPlaidTransactions job for user:', ['user_id' => $user->id]);
                SyncPlaidTransactions::dispatch($user);
                return response()->json(['status' => 'success']);
            } else {
                Log::error('User not found for item_id:', ['item_id' => $request->item_id]);
            }
        }

        return response()->json(['status' => 'ignored']);
    }
}
