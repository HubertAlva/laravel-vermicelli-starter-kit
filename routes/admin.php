<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UploadEditorImagesController;

Route::middleware(['auth', 'verified', 'permission:admin.all'])
    ->domain('admin.'.config('app.app_domain'))
    ->name('admin.')
    ->group(function () {

        Route::get('/', [AdminController::class, 'index'])->name('dashboard');

        Route::get('posts', [PostController::class, 'index'])->name('posts.index');
        Route::get('posts/create', [PostController::class, 'create'])->name('posts.create');
        Route::post('posts', [PostController::class, 'store'])->middleware('precognitive')->name('posts.store');
        Route::get('posts/{post}/edit', [PostController::class, 'edit'])->withTrashed()->name('posts.edit');
        Route::post('posts/{post}', [PostController::class, 'update'])->middleware('precognitive')->name('posts.update');
        Route::delete('/posts/{post}', [PostController::class, 'destroy'])->withTrashed()->name('posts.destroy');
        Route::delete('posts/{post}/soft-delete', [PostController::class, 'soft_delete'])->name('posts.soft-delete');
        Route::post('posts/{post}/restore', [PostController::class, 'restore'])->withTrashed()->name('posts.restore');

        Route::post('editor/upload', UploadEditorImagesController::class)->name('editor.upload');
    });
