<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;

Route::get('/', function () {
    return Inertia::render('welcome', [
        'canRegister' => Features::enabled(Features::registration()),
    ]);
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/categories', [App\Http\Controllers\CategoryController::class, 'index'])->name('categories.index');
Route::get('/categories/{category:slug}', [App\Http\Controllers\CategoryController::class, 'show'])->name('categories.show');

Route::get('/categories/{category:slug}', [App\Http\Controllers\CategoryController::class, 'show'])->name('categories.show');

Route::middleware(['auth'])->group(function (): void {
    Route::get('/discussions/create', [App\Http\Controllers\DiscussionController::class, 'create'])->name('discussions.create');
    Route::post('/discussions', [App\Http\Controllers\DiscussionController::class, 'store'])->name('discussions.store');
});

require __DIR__.'/settings.php';
