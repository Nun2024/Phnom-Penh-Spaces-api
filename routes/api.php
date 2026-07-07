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

// Public space and booking creation routes
Route::get('/space-types', [SpaceTypeController::class, 'index']);
Route::get('/space-types/{id}', [SpaceTypeController::class, 'show']);
Route::get('/spaces', [SpaceController::class, 'index']);
Route::get('/spaces/{id}', [SpaceController::class, 'show']);
Route::get('/spaces/{id}/bookings', [SpaceController::class, 'bookings']);
Route::post('/bookings', [BookingController::class, 'store']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    // Auth profile & logout
    Route::get('/profile', [AuthController::class, 'profile']);
    Route::post('/logout', [AuthController::class, 'logout']);

    // Admin space management
    Route::post('/space-types', [SpaceTypeController::class, 'store']);
    Route::put('/space-types/{id}', [SpaceTypeController::class, 'update']);
    Route::delete('/space-types/{id}', [SpaceTypeController::class, 'destroy']);
    
    Route::post('/spaces', [SpaceController::class, 'store']);
    Route::put('/spaces/{id}', [SpaceController::class, 'update']);

    // Booking management (view and update)
    Route::get('/bookings', [BookingController::class, 'index']);
    Route::put('/bookings/{id}', [BookingController::class, 'update']);
    
    // Dashboard stats
    Route::get('/dashboard/stats', [DashboardController::class, 'stats']);
});
