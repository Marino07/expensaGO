<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PlaidController extends Controller
{
    private $clientId;
    private $secret;
    private $environment;
    private $redirectUri;

    public function __construct()
    {
        $this->clientId = env('PLAID_CLIENT_ID');
        $this->secret = env('PLAID_SECRET');
        $this->environment = env('PLAID_ENV', 'sandbox');
        $this->redirectUri = env('PLAID_REDIRECT_URI');
    }

    public function createLinkToken()
    {
        try {
            $response = Http::post("https://{$this->environment}.plaid.com/link/token/create", [
                'client_id' => $this->clientId,
                'secret' => $this->secret,
                'client_name' => 'ExpensaGO',
                'products' => ['transactions'],
                'country_codes' => ['US'],
                'language' => 'en',
                'redirect_uri' => $this->redirectUri,
                'user' => [
                    'client_user_id' => (string)auth()->id()
                ]
            ]);

            if (!$response->successful()) {
                Log::error('Plaid error', [
                    'status' => $response->status(),
                    'body' => $response->json()
                ]);
                return response()->json(['error' => 'Failed to create link token'], 500);
            }

            return $response->json();

        } catch (\Exception $e) {
            Log::error('Plaid exception', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Internal server error'], 500);
        }
    }

    public function getAccessToken(Request $request)
    {
        try {
            $response = Http::post("https://{$this->environment}.plaid.com/item/public_token/exchange", [
                'client_id' => $this->clientId,
                'secret' => $this->secret,
                'public_token' => $request->public_token
            ]);

            if ($response->successful()) {
                $data = $response->json();

                // Explicitly log the response to debug
                \Log::info('Plaid access token response:', $data);

                // Update user with access token
                auth()->user()->update([
                    'plaid_access_token' => $data['access_token']
                ]);

                return response()->json(['success' => true]);
            }

            \Log::error('Plaid error:', $response->json());
            return response()->json(['success' => false, 'error' => 'Failed to exchange token'], 400);

        } catch (\Exception $e) {
            \Log::error('Plaid exception:', ['error' => $e->getMessage()]);
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    public function getTransactions()
    {
        try {
            $response = Http::post("https://{$this->environment}.plaid.com/transactions/get", [
                'client_id' => $this->clientId,
                'secret' => $this->secret,
                'access_token' => auth()->user()->plaid_access_token,
                'start_date' => '2024-01-01',
                'end_date' => date('Y-m-d')
            ]);

            if ($response->successful()) {
                \Log::info('Plaid transactions:', $response->json());
                return response()->json($response->json());
            }

            return response()->json(['error' => 'Failed to fetch transactions'], 400);
        } catch (\Exception $e) {
            \Log::error('Plaid transaction error:', ['error' => $e->getMessage()]);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
