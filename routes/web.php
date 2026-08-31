<?php

use App\Http\Controllers\PageBlogController;
use App\Http\Controllers\PageContactController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

require __DIR__.'/admin.php';

Route::domain(config('app.app_domain'))->group(function () {
    Route::get('/', function () {
        return Inertia::render('home/index/Page');
    })->name('home');

    Route::get('blog', [PageBlogController::class, 'index'])->name('blog.index');
    Route::get('blog/{post:slug}', [PageBlogController::class, 'show'])->name('blog.show');

    Route::get('contact', [PageContactController::class, 'index'])->name('contact.index');
    Route::post('contact', [PageContactController::class, 'store'])->middleware('precognitive')->name('contact.store');

    Route::get('dashboard', function () {
        return Inertia::render('Dashboard');
    })->middleware(['auth', 'verified'])->name('dashboard');

    require __DIR__.'/settings.php';
});
