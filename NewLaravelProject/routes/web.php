<?php

use App\Http\Controllers\AdvertisementController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LocalityController;
use App\Http\Controllers\FestivityController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VoteController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('localidades', [LocalityController::class, 'index'])->name('localities.index');
Route::get('festividades', [FestivityController::class, 'index'])->name('festivities.index');
Route::get('mas-votadas', [VoteController::class, 'mostVoted'])->name('festivities.most-voted');
Route::get('festividades/cercanas', [FestivityController::class, 'nearby'])->name('festivities.nearby');
Route::get('festividades/mapa', [FestivityController::class, 'forMap'])->name('festivities.map');

// SEO routes
Route::get('sitemap.xml', [\App\Http\Controllers\SitemapController::class, 'index'])->name('sitemap');
Route::get('robots.txt', [\App\Http\Controllers\SitemapController::class, 'robots'])->name('robots');

// Authentication routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Comment routes
    Route::post('festividades/{festivity}/comentarios', [CommentController::class, 'store'])->name('comments.store');
    Route::patch('comentarios/{comment}/aprobar', [CommentController::class, 'approve'])->name('comments.approve');
    Route::patch('comentarios/{comment}/rechazar', [CommentController::class, 'reject'])->name('comments.reject');
    Route::get('comentarios/pendientes', [CommentController::class, 'pending'])->name('comments.pending');
    
    // Vote routes
    Route::post('festividades/{festivity}/votar', [VoteController::class, 'store'])->name('votes.store');
    
    // Protected localities routes
    Route::get('localidades/crear', [LocalityController::class, 'create'])->name('localities.create');
    Route::post('localidades', [LocalityController::class, 'store'])->name('localities.store');
    Route::get('localidades/{locality}', [LocalityController::class, 'show'])->name('localities.show');
    Route::get('localidades/{locality}/editar', [LocalityController::class, 'edit'])->name('localities.edit');
    Route::put('localidades/{locality}', [LocalityController::class, 'update'])->name('localities.update');
    Route::patch('localidades/{locality}', [LocalityController::class, 'update'])->name('localities.patch');
    Route::delete('localidades/{locality}', [LocalityController::class, 'destroy'])->name('localities.destroy');
    
    // Protected festivities routes
    Route::get('festividades/crear', [FestivityController::class, 'create'])->name('festivities.create');
    Route::post('festividades', [FestivityController::class, 'store'])->name('festivities.store');
    Route::get('festividades/{festivity}', [FestivityController::class, 'show'])->name('festivities.show');
    Route::get('festividades/{festivity}/editar', [FestivityController::class, 'edit'])->name('festivities.edit');
    Route::put('festividades/{festivity}', [FestivityController::class, 'update'])->name('festivities.update');
    Route::patch('festividades/{festivity}', [FestivityController::class, 'update'])->name('festivities.patch');
    Route::delete('festividades/{festivity}', [FestivityController::class, 'destroy'])->name('festivities.destroy');
    
    // Protected events routes
    Route::get('festividades/{festivity}/eventos', [EventController::class, 'index'])->name('events.index');
    Route::get('festividades/{festivity}/eventos/crear', [EventController::class, 'create'])->name('events.create');
    Route::post('festividades/{festivity}/eventos', [EventController::class, 'store'])->name('events.store');
    Route::get('festividades/{festivity}/eventos/{event}', [EventController::class, 'show'])->name('events.show');
    Route::get('festividades/{festivity}/eventos/{event}/editar', [EventController::class, 'edit'])->name('events.edit');
    Route::put('festividades/{festivity}/eventos/{event}', [EventController::class, 'update'])->name('events.update');
    Route::patch('festividades/{festivity}/eventos/{event}', [EventController::class, 'update'])->name('events.patch');
    Route::delete('festividades/{festivity}/eventos/{event}', [EventController::class, 'destroy'])->name('events.destroy');
    
    // Protected users routes
    Route::get('users', [UserController::class, 'index'])->name('users.index');
    Route::get('users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('users', [UserController::class, 'store'])->name('users.store');
    Route::get('users/{user}', [UserController::class, 'show'])->name('users.show');
    Route::get('users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::patch('users/{user}', [UserController::class, 'update'])->name('users.patch');
    Route::delete('users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

    // Premium advertisements admin
    Route::resource('advertisements', AdvertisementController::class)->except(['show']);
    Route::patch('advertisements/{advertisement}/toggle-active', [AdvertisementController::class, 'toggle'])->name('advertisements.toggle');
});

require __DIR__.'/auth.php';
