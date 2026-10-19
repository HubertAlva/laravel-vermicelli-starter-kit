<?php

use App\Http\Controllers\Admin\EditorImageController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\TestFormController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

Route::domain(config('app.admin_domain'))
    ->middleware(['auth', 'verified', 'permission:admin.view'])
    ->name('admin.')
    ->group(function () {
        Route::inertia('/', 'admin/dashboard/Page')->name('dashboard');

        Route::controller(UserController::class)->prefix('users')->name('users.')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('create', 'create')->name('create');
            Route::post('/', 'store')->middleware('precognitive')->name('store');
            Route::get('{user}/edit', 'edit')->name('edit');
            Route::put('{user}', 'update')->middleware('precognitive')->name('update');
            Route::delete('{user}', 'destroy')->name('destroy');
        });

        Route::controller(PostController::class)->prefix('posts')->name('posts.')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('create', 'create')->name('create');
            Route::post('/', 'store')->middleware('precognitive')->name('store');
            Route::get('{post}/edit', 'edit')->withTrashed()->name('edit');
            // POST (not PUT) so multipart thumbnail uploads are parsed by PHP...
            Route::post('{post}', 'update')->withTrashed()->middleware('precognitive')->name('update');
            Route::delete('{post}', 'destroy')->name('destroy');
            Route::post('{post}/restore', 'restore')->withTrashed()->name('restore');
            Route::delete('{post}/force', 'forceDestroy')->withTrashed()->name('force-destroy');
        });

        Route::controller(RoleController::class)->prefix('roles')->name('roles.')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('create', 'create')->name('create');
            Route::post('/', 'store')->middleware('precognitive')->name('store');
            Route::get('{role}/edit', 'edit')->name('edit');
            Route::put('{role}', 'update')->middleware('precognitive')->name('update');
        });

        Route::controller(PermissionController::class)->prefix('permissions')->name('permissions.')->group(function () {
            Route::get('create', 'create')->name('create');
            Route::post('/', 'store')->middleware('precognitive')->name('store');
            Route::get('{permission}/edit', 'edit')->name('edit');
            Route::put('{permission}', 'update')->middleware('precognitive')->name('update');
            Route::delete('{permission}', 'destroy')->name('destroy');
        });

        Route::post('editor/images', EditorImageController::class)
            ->middleware('throttle:60,1')
            ->name('editor.images.store');

        Route::get('test-form', [TestFormController::class, 'index'])->name('test-form.index');
        Route::post('test-form', [TestFormController::class, 'store'])->middleware('precognitive')->name('test-form.store');
    });
