<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ItemController;
use App\Http\Controllers\Api\RentalController;
use App\Http\Controllers\Api\ChatController;
use App\Http\Controllers\Api\ReportController;
use App\Http\Controllers\Api\ProfileController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application.
|
*/

// ── PUBLIC AUTH ROUTES ────────────────────────────────────────────────
Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
});

// ── PUBLIC ITEM ROUTES ────────────────────────────────────────────────
Route::prefix('items')->group(function () {
    Route::get('/', [ItemController::class, 'index']);
    Route::get('/categories', [ItemController::class, 'categories']);
    Route::get('/{item}', [ItemController::class, 'show']);
});

// ── PUBLIC PROFILE ROUTES ─────────────────────────────────────────────
Route::prefix('profiles')->group(function () {
    Route::get('/{user}', [ProfileController::class, 'show']);
    Route::get('/{user}/items', [ProfileController::class, 'items']);
    Route::get('/{user}/statistics', [ProfileController::class, 'statistics']);
});

// ── PROTECTED ROUTES (require authentication) ─────────────────────────
Route::middleware('auth:sanctum')->group(function () {

    // ── Auth Routes ───────────────────────────────────────────────
    Route::prefix('auth')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/me', [AuthController::class, 'me']);
    });

    // ── Profile Routes ────────────────────────────────────────────
    Route::prefix('profile')->group(function () {
        Route::get('/', [ProfileController::class, 'me']);
        Route::put('/', [ProfileController::class, 'update']);
        Route::post('/change-password', [ProfileController::class, 'changePassword']);
    });

    // ── My Items Routes (User's own items) ─────────────────────────
    Route::prefix('my-items')->group(function () {
        Route::get('/', [ItemController::class, 'index']);
        Route::post('/', [ItemController::class, 'store']);
        Route::put('/{item}', [ItemController::class, 'update']);
        Route::delete('/{item}', [ItemController::class, 'destroy']);
        Route::post('/{item}/toggle-status', [ItemController::class, 'toggleStatus']);
    });

    // ── Rental Routes ─────────────────────────────────────────────
    Route::prefix('rentals')->group(function () {
        Route::get('/', [RentalController::class, 'index']);
        Route::post('/', [RentalController::class, 'store']);
        Route::get('/{rental}', [RentalController::class, 'show']);
        Route::post('/{rental}/action', [RentalController::class, 'action']);
        Route::post('/{rental}/review', [RentalController::class, 'review']);
    });

    // ── Chat Routes ───────────────────────────────────────────────
    Route::prefix('chat')->group(function () {
        Route::get('/', [ChatController::class, 'index']);
        Route::post('/', [ChatController::class, 'store']);
        Route::get('/user/{user}', [ChatController::class, 'show']);
        Route::post('/message/{message}/read', [ChatController::class, 'markAsRead']);
        Route::post('/user/{user}/read-all', [ChatController::class, 'markAllAsRead']);
    });

    // ── Report Routes ─────────────────────────────────────────────
    Route::prefix('reports')->group(function () {
        Route::get('/', [ReportController::class, 'index']);
        Route::post('/', [ReportController::class, 'store']);
        Route::get('/{report}', [ReportController::class, 'show']);
    });

    // ── Admin Report Routes ───────────────────────────────────────
    Route::prefix('admin/reports')->middleware('admin')->group(function () {
        Route::get('/', [ReportController::class, 'adminIndex']);
        Route::put('/{report}', [ReportController::class, 'adminUpdate']);
    });
});

// routes/api.php
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\MidtransController;

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/checkout', [PaymentController::class, 'checkout']);
});

// Webhook HARUS exclude dari CSRF & auth!
Route::post('/midtrans/webhook', [MidtransController::class, 'webhook']);