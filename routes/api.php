<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SpaceController;
use App\Http\Controllers\SpaceTypeController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\DashboardController;

// Authentication routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Public space routes
Route::apiResource('space-types', SpaceTypeController::class)->only(['index', 'show']);
Route::apiResource('spaces', SpaceController::class)->only(['index', 'show']);
Route::get('/spaces/{id}/bookings', [SpaceController::class, 'bookings']);

// Public booking creation
Route::post('/bookings', [BookingController::class, 'store']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    // Auth profile & logout
    Route::get('/profile', [AuthController::class, 'profile']);
    Route::post('/logout', [AuthController::class, 'logout']);

    // Admin space management
    Route::apiResource('space-types', SpaceTypeController::class)->except(['index', 'show']);
    Route::apiResource('spaces', SpaceController::class)->except(['index', 'show']);

    // Booking management (view, update, delete)
    Route::apiResource('bookings', BookingController::class)->except(['store']);
    
    // Dashboard stats
    Route::prefix('dashboard')->group(function () {
        Route::get('/stats', [DashboardController::class, 'stats']);
        Route::get('/reservations', [DashboardController::class, 'reservations']);
        Route::get('/revenue', [DashboardController::class, 'revenue']);
        Route::get('/revenue-weekly', [DashboardController::class, 'weeklyReport']);
    });
});

