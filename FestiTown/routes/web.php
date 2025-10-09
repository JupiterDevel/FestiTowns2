<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\TownController;
use App\Http\Controllers\FestiveController;
use App\Http\Controllers\AdvertisementController;

// Home route
Route::get('/', [HomeController::class, 'index'])->name('home');

// Towns routes
Route::resource('towns', TownController::class);

// Festives routes
Route::resource('festives', FestiveController::class);

// Advertisements routes
Route::resource('advertisements', AdvertisementController::class);
