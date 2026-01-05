<?php

use App\Http\Controllers\PageBlogController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('home/index/Page');
})->name('home');

Route::get('blog', [PageBlogController::class, 'index'])->name('blog.index');
Route::get('blog/{post:slug}', [PageBlogController::class, 'show'])->name('blog.show');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__ . '/admin.php';

require __DIR__ . '/settings.php';
