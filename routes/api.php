<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ChatbotController;
use App\Http\Controllers\Api\PromoBannerController;
use App\Http\Controllers\Api\ScheduleController;
use App\Http\Controllers\Api\StationController;
use App\Http\Controllers\Api\TransactionController;
use App\Http\Controllers\Api\VoucherController;
use Illuminate\Support\Facades\Route;

// Public API Routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Stations
Route::get('/stations', [StationController::class, 'index']);
Route::get('/stations/{station}', [StationController::class, 'show']);

// Schedules
Route::get('/schedules', [ScheduleController::class, 'index']);
Route::get('/schedules/search', [ScheduleController::class, 'search']);
Route::get('/schedules/{schedule}', [ScheduleController::class, 'show']);

// Promo Banners
Route::get('/banners', [PromoBannerController::class, 'index']);

// Protected API Routes
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::put('/user/profile', [AuthController::class, 'updateProfile']);

    // Transactions
    Route::get('/transactions', [TransactionController::class, 'index']);
    Route::post('/transactions', [TransactionController::class, 'store']);
    Route::get('/transactions/{transaction}', [TransactionController::class, 'show']);
    Route::post('/transactions/{transaction}/cancel', [TransactionController::class, 'cancel']);

    // Chatbot — Rule 1: enforces daily limit, awards XP (Rule 2), generates voucher (Rule 3)
    Route::post('/chat', [ChatbotController::class, 'send']);

    // Vouchers — Rule 3/5
    Route::get('/vouchers', [VoucherController::class, 'index']);
    Route::post('/vouchers/{code}/redeem', [VoucherController::class, 'redeem']);
});
