<?php

use App\Http\Controllers\BlogController;
use App\Http\Controllers\ContactController;
use Illuminate\Support\Facades\Route;

Route::domain(config('app.domain'))->group(function () {
    Route::inertia('/', 'home/index/Page')->name('home');

    Route::get('blog', [BlogController::class, 'index'])->name('blog.index');
    Route::get('blog/{post:slug}', [BlogController::class, 'show'])->name('blog.show');

    Route::get('contact', [ContactController::class, 'show'])->name('contact.show');
    Route::post('contact', [ContactController::class, 'store'])
        ->middleware(['precognitive', 'throttle:5,1'])
        ->name('contact.store');

    Route::middleware(['auth', 'verified'])->group(function () {
        Route::inertia('dashboard', 'Dashboard')->name('dashboard');
    });

    require __DIR__.'/settings.php';
});
