<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\PostController;

Route::middleware(['auth', 'verified', 'permission:admin.all'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/', [AdminController::class, 'index'])->name('dashboard');

        Route::get('posts', [PostController::class, 'index'])->name('posts.index');
        Route::get('posts/create', [PostController::class, 'create'])->name('posts.create');
        Route::post('posts', [PostController::class, 'store'])->middleware('precognition')->name('posts.store');

        Route::post('editor/upload', [AdminController::class, 'upload'])->name('editor.upload');
    });
