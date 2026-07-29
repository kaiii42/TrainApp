<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PromoBannerController;
use App\Http\Controllers\Admin\ScheduleController;
use App\Http\Controllers\Admin\StationController;
use App\Http\Controllers\Admin\TrainController;
use App\Http\Controllers\Admin\TransactionController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Route;

// Redirect root to login or dashboard
Route::get('/', function () {
    return auth()->check() ? redirect()->route('admin.dashboard') : redirect()->route('login');
});

// Auth Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

// Admin Routes
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Stations
    Route::resource('stations', StationController::class)->except(['show']);

    // Trains
    Route::resource('trains', TrainController::class)->except(['show']);

    // Schedules
    Route::resource('schedules', ScheduleController::class)->except(['show']);

    // Promo Banners
    Route::resource('banners', PromoBannerController::class)->except(['show']);

    // Transactions
    Route::get('transactions', [TransactionController::class, 'index'])->name('transactions.index');
    Route::get('transactions/{transaction}', [TransactionController::class, 'show'])->name('transactions.show');
    Route::patch('transactions/{transaction}/status', [TransactionController::class, 'updateStatus'])->name('transactions.update-status');

    // Users
    Route::resource('users', UserController::class)->except(['show']);
});
