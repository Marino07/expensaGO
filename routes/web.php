<?php

use App\Livewire\Trip\AllExpenses;
use App\Livewire\Trip\EditTrip;
use App\Livewire\Trip\ManageTrips;
use App\Livewire\Trip\StartTrip;
use App\Livewire\Trip\NewExpense;
use App\Livewire\WelcomeComponent;
use Illuminate\Support\Facades\Route;
use App\Livewire\Application\Application;

Route::get('/', WelcomeComponent::class)->name('index');

Route::middleware('auth')->group(function () {
    Route::get('/app', Application::class)->name('app');
    Route::get('start-trip', StartTrip::class)->name('start-trip');
    Route::get('new-expense', NewExpense::class)->name('new-expense');
    Route::get('manage_trips', ManageTrips::class)->name('manage-trips');
    Route::get('all_expenses', AllExpenses::class)->name('all-expenses');
    Route::get('edit_trip/{trip}', EditTrip::class)->name('edit-trip');

});

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__.'/auth.php';
