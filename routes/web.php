<?php

use App\Livewire\Analize\Analize;
use App\Models\User;
use App\Livewire\Event\Event;
use App\Livewire\SOSComponent;
use App\Livewire\Trip\EditTrip;
use App\Livewire\Trip\StartTrip;
use App\Livewire\Trip\NewExpense;
use App\Livewire\Trip\AllExpenses;
use App\Livewire\Trip\ManageTrips;
use App\Livewire\Trip\TripPlanner;
use App\Livewire\WelcomeComponent;
use App\Livewire\Trip\GeneratePlan;
use App\Livewire\Places\PlaceFinder;
use Illuminate\Support\Facades\Route;
use App\Livewire\SavedItems\SavedItems;
use App\Livewire\Trip\ItineraryBuilder;
use App\Http\Controllers\PlaidController;
use App\Livewire\Application\Application;
use App\Livewire\Places\RestaurantFinder;
use App\Http\Controllers\PlaidWebhookController;

Route::get('/', WelcomeComponent::class)->name('index');
Route::get('/email_test', function () {
    $event = \App\Models\LocalEvent::where('id', 16)->first();
    $actionUrl = url('/events');
    return view('emails.event-notification', ['event' => $event, 'formattedDate' => '2021-09-09', 'actionUrl' => $actionUrl, 'user' => User::first()]);
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/app', Application::class)->name('app');
    Route::get('start-trip', StartTrip::class)->name('start-trip');
    Route::get('new-expense', NewExpense::class)->name('new-expense');
    Route::get('manage_trips', ManageTrips::class)->name('manage-trips');
    Route::get('all_expenses', AllExpenses::class)->name('all-expenses');
    Route::get('finish_trip/{trip}', EditTrip::class)->name('finish-trip');
    Route::get('/places', PlaceFinder::class)->name('places');
    Route::get('/sos', SOSComponent::class)->name('sos');
    Route::get('/trip-planner/{trip}', TripPlanner::class)->name('trip-planner');
    Route::post('/plaid/create-link-token', [PlaidController::class, 'createLinkToken']);
    Route::post('/plaid/get-access-token', [PlaidController::class, 'getAccessToken']);
    Route::get('/plaid/transactions', [PlaidController::class, 'getTransactions'])->middleware('auth');
    Route::post('/webhook/plaid', [PlaidWebhookController::class, 'handleWebhook']);
    Route::get('/events',Event::class)->name('events');
    Route::get('/saved-items',SavedItems::class)->name('saved-items');
    Route::get('/analitycs',Analize::class)->name('analytics');

});
Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

Route::get('/test-webhook', function () {
    $user = User::first(); // Fetch the first user for testing
    return view('test-webhook', ['user' => $user]);
});

require __DIR__.'/auth.php';
