<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\MangaController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\PasswordController;
use App\Http\Controllers\Admin\ShareLinkController;
use App\Http\Controllers\ReaderController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('admin.login');
});

Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('login', [AuthController::class, 'showLogin'])->name('login');
        Route::post('login', [AuthController::class, 'login'])
            ->middleware('throttle:5,1')
            ->name('login.submit');
    });

    Route::middleware('auth')->group(function () {
        Route::post('logout', [AuthController::class, 'logout'])->name('logout');
        Route::get('/', DashboardController::class)->name('dashboard');

        Route::get('password', [PasswordController::class, 'edit'])->name('password.edit');
        Route::put('password', [PasswordController::class, 'update'])->name('password.update');

        Route::resource('mangas', MangaController::class);

        Route::prefix('mangas/{manga}')->name('mangas.')->group(function () {
            Route::get('pages', [PageController::class, 'index'])->name('pages.index');
            Route::get('pages/create', [PageController::class, 'create'])->name('pages.create');
            Route::post('pages', [PageController::class, 'store'])->name('pages.store');
            Route::get('pages/{page}/edit', [PageController::class, 'edit'])->name('pages.edit');
            Route::put('pages/{page}', [PageController::class, 'update'])->name('pages.update');
            Route::delete('pages/{page}', [PageController::class, 'destroy'])->name('pages.destroy');
            Route::post('pages/reorder', [PageController::class, 'reorder'])->name('pages.reorder');
            Route::get('pages/{page}/image', [PageController::class, 'image'])->name('pages.image');

            Route::get('links', [ShareLinkController::class, 'index'])->name('links.index');
            Route::post('links', [ShareLinkController::class, 'store'])->name('links.store');
            Route::get('links/{link}', [ShareLinkController::class, 'show'])->name('links.show');
            Route::get('links/{link}/countries', [ShareLinkController::class, 'countries'])->name('links.countries');
            Route::put('links/{link}', [ShareLinkController::class, 'update'])->name('links.update');
            Route::delete('links/{link}', [ShareLinkController::class, 'destroy'])->name('links.destroy');
            Route::post('links/{link}/regenerate', [ShareLinkController::class, 'regenerate'])->name('links.regenerate');
        });
    });
});

Route::prefix('r')->name('reader.')->group(function () {
    Route::get('{token}', [ReaderController::class, 'show'])->name('show');
    Route::get('{token}/page/{page}', [ReaderController::class, 'page'])->name('page');
});
