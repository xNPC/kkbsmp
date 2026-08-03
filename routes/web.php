<?php

use App\Livewire\Portals\Portals;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('welcome');

Route::view('/', 'theme.home')->name('home');

Route::view('contacts', 'theme.contacts')->name('contacts');

Route::get('/portals', Portals::class)->name('portals');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');
});

require __DIR__.'/settings.php';
