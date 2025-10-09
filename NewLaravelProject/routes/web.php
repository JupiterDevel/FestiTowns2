<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LocalityController;
use App\Http\Controllers\FestivityController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('localities', [LocalityController::class, 'index'])->name('localities.index');
Route::get('festivities', [FestivityController::class, 'index'])->name('festivities.index');

// Authentication routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Comment routes
    Route::post('festivities/{festivity}/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::patch('comments/{comment}/approve', [CommentController::class, 'approve'])->name('comments.approve');
    Route::patch('comments/{comment}/reject', [CommentController::class, 'reject'])->name('comments.reject');
    Route::get('comments/pending', [CommentController::class, 'pending'])->name('comments.pending');
    
    // Protected localities routes
    Route::get('localities/create', [LocalityController::class, 'create'])->name('localities.create');
    Route::post('localities', [LocalityController::class, 'store'])->name('localities.store');
    Route::get('localities/{locality}', [LocalityController::class, 'show'])->name('localities.show');
    Route::get('localities/{locality}/edit', [LocalityController::class, 'edit'])->name('localities.edit');
    Route::put('localities/{locality}', [LocalityController::class, 'update'])->name('localities.update');
    Route::patch('localities/{locality}', [LocalityController::class, 'update'])->name('localities.patch');
    Route::delete('localities/{locality}', [LocalityController::class, 'destroy'])->name('localities.destroy');
    
    // Protected festivities routes
    Route::get('festivities/create', [FestivityController::class, 'create'])->name('festivities.create');
    Route::post('festivities', [FestivityController::class, 'store'])->name('festivities.store');
    Route::get('festivities/{festivity}', [FestivityController::class, 'show'])->name('festivities.show');
    Route::get('festivities/{festivity}/edit', [FestivityController::class, 'edit'])->name('festivities.edit');
    Route::put('festivities/{festivity}', [FestivityController::class, 'update'])->name('festivities.update');
    Route::patch('festivities/{festivity}', [FestivityController::class, 'update'])->name('festivities.patch');
    Route::delete('festivities/{festivity}', [FestivityController::class, 'destroy'])->name('festivities.destroy');
    
    // Protected users routes
    Route::get('users', [UserController::class, 'index'])->name('users.index');
    Route::get('users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('users', [UserController::class, 'store'])->name('users.store');
    Route::get('users/{user}', [UserController::class, 'show'])->name('users.show');
    Route::get('users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::patch('users/{user}', [UserController::class, 'update'])->name('users.patch');
    Route::delete('users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
});

require __DIR__.'/auth.php';
