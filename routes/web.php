<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\MyItemController;
use App\Http\Controllers\RentalController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\Admin;

// ── Public ────────────────────────────────────────────────────────────────────
Route::get('/', [WelcomeController::class, 'index'])->name('welcome');
Route::get('/explore', [ItemController::class, 'explore'])->name('explore');
Route::get('/items/{item}', [ItemController::class, 'show'])->name('items.show');

// ── Auth (guest only) ─────────────────────────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/login',     [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login',    [AuthController::class, 'login']);
    Route::get('/register',  [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// ── User (auth) ───────────────────────────────────────────────────────────────
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', fn() => redirect()->route('my-items.index'))->name('dashboard');

    // My Items
    Route::resource('my-items', MyItemController::class);
    Route::post('/my-items/{myItem}/toggle-status', [MyItemController::class, 'toggleStatus'])->name('my-items.toggle-status');

    // Rentals
    Route::get('/rentals',                    [RentalController::class, 'index'])->name('rentals.index');
    Route::post('/rentals',                   [RentalController::class, 'store'])->name('rentals.store');
    Route::get('/rentals/{rental}',           [RentalController::class, 'show'])->name('rentals.show');
    Route::post('/rentals/{rental}/action',   [RentalController::class, 'action'])->name('rentals.action');

    // Chat
    Route::get('/chat',   [ChatController::class, 'index'])->name('chat.index');
    Route::post('/chat',  [ChatController::class, 'store'])->name('chat.store');

    // Report
    Route::get('/report',  [ReportController::class, 'index'])->name('report.index');
    Route::post('/report', [ReportController::class, 'store'])->name('report.store');

    // Payment
    Route::post('/rentals/{rental}/pay',  [PaymentController::class, 'checkout'])->name('rentals.pay');
    Route::get('/payments/{rental}/status', [PaymentController::class, 'checkStatus'])->name('payments.status');
    Route::post('/rentals/{rental}/mock-pay', [PaymentController::class, 'mockPayment'])->name('rentals.mock-pay');
    Route::post('/rentals/{rental}/pay/success', [PaymentController::class, 'paySuccess'])->name('rentals.pay.success');

    // Midtrans Webhook
    Route::post('/payments/callback', [PaymentController::class, 'callback'])->name('payments.callback')->withoutMiddleware('auth');

    // Profile
    Route::get('/profile',               [ProfileController::class, 'index'])->name('profile.index');
    Route::put('/profile',               [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile',            [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/profile/verification', [ProfileController::class, 'uploadVerification'])->name('profile.verification');
});

// ── Admin ─────────────────────────────────────────────────────────────────────
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [Admin\DashboardController::class, 'index'])->name('dashboard');

    Route::get('/items',           [Admin\ItemController::class, 'index'])->name('items.index');
    Route::patch('/items/{item}',  [Admin\ItemController::class, 'update'])->name('items.update');
    Route::delete('/items/{item}', [Admin\ItemController::class, 'destroy'])->name('items.destroy');

    Route::get('/users',           [Admin\UserController::class, 'index'])->name('users.index');
    Route::patch('/users/{user}',  [Admin\UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [Admin\UserController::class, 'destroy'])->name('users.destroy');

    Route::get('/rentals',           [Admin\RentalController::class, 'index'])->name('rentals.index');
    Route::get('/rentals/{rental}',  [Admin\RentalController::class, 'show'])->name('rentals.show');

    Route::get('/reports',             [Admin\ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/{report}',    [Admin\ReportController::class, 'show'])->name('reports.show');
    Route::patch('/reports/{report}',  [Admin\ReportController::class, 'update'])->name('reports.update');

    Route::get('/verifications',                  [Admin\VerificationController::class, 'index'])->name('verifications.index');
    Route::patch('/verifications/{verification}', [Admin\VerificationController::class, 'update'])->name('verifications.update');
});
