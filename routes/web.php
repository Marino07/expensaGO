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
use App\Livewire\Application\Application;
use App\Livewire\Places\RestaurantFinder;

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
});

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__.'/auth.php';
