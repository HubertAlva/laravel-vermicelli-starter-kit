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
        Route::get('posts/{post}/edit', [PostController::class, 'edit'])->withTrashed()->name('posts.edit');
        Route::post('posts/{post}', [PostController::class, 'update'])->middleware('precognition')->name('posts.update');
        Route::delete('/posts/{post}', [PostController::class, 'destroy'])->withTrashed()->name('posts.destroy');
        Route::delete('posts/{post}/soft-delete', [PostController::class, 'soft_delete'])->name('posts.soft-delete');
        Route::post('posts/{post}/restore', [PostController::class, 'restore'])->withTrashed()->name('posts.restore');

        Route::post('editor/upload', [AdminController::class, 'upload'])->name('editor.upload');
    });
