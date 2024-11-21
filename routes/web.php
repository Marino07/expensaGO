<?php

use App\Livewire\WelcomeComponent;
use Illuminate\Support\Facades\Route;
use App\Livewire\Application\Application;

Route::get('/', WelcomeComponent::class)->name('index');
Route::get('/app', Application::class)->name('app');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__.'/auth.php';
