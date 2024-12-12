<?php

use App\Livewire\SOSComponent;
use App\Livewire\Trip\EditTrip;
use App\Livewire\Trip\StartTrip;
use App\Livewire\Trip\NewExpense;
use App\Livewire\Trip\AllExpenses;
use App\Livewire\Trip\ManageTrips;
use App\Livewire\WelcomeComponent;
use App\Livewire\Trip\GeneratePlan;
use Illuminate\Support\Facades\Route;
use App\Livewire\Trip\ItineraryBuilder;
use App\Http\Controllers\PlaidController;
use App\Livewire\Application\Application;
use App\Livewire\Places\RestaurantFinder;
use App\Http\Controllers\PlaidWebhookController;
use App\Models\User;

Route::get('/', WelcomeComponent::class)->name('index');

Route::middleware('auth')->group(function () {
    Route::get('/app', Application::class)->name('app');
    Route::get('start-trip', StartTrip::class)->name('start-trip');
    Route::get('new-expense', NewExpense::class)->name('new-expense');
    Route::get('manage_trips', ManageTrips::class)->name('manage-trips');
    Route::get('all_expenses', AllExpenses::class)->name('all-expenses');
    Route::get('finish_trip/{trip}', EditTrip::class)->name('finish-trip');
    Route::get('/restaurants', RestaurantFinder::class)->name('restaurants');
    Route::get('/generate_plan/{trip}', GeneratePlan::class)->name('generate-plan');
    Route::get('/sos', SOSComponent::class)->name('sos');
    Route::get('/itinerary_builder/{trip}', ItineraryBuilder::class)->name('itinerary_builder');
    Route::post('/plaid/create-link-token', [PlaidController::class, 'createLinkToken']);
    Route::post('/plaid/get-access-token', [PlaidController::class, 'getAccessToken']);
    Route::get('/plaid/transactions', [PlaidController::class, 'getTransactions'])->middleware('auth');
    Route::post('/webhook/plaid', [PlaidWebhookController::class, 'handleWebhook']);
});

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

Route::get('/test-webhook', function () {
    $user = User::first(); // Fetch the first user for testing
    return view('test-webhook', ['user' => $user]);
});

require __DIR__.'/auth.php';
