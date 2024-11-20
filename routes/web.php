<?php

use App\Livewire\WelcomeComponent;
use Illuminate\Support\Facades\Route;

Route::get('/', WelcomeComponent::class)->name('index');


Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__.'/auth.php';
