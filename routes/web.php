<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\SlotLockController;
use App\Http\Controllers\ReceiptController;
use App\Http\Controllers\BlockedSlotController;

// ── PUBLIC ROUTES ──
Route::get('/', [WelcomeController::class, 'index']);

Route::get('/courts/{id}', fn($id) => view('court-detail', ['courtId' => $id]))
    ->where('id', '[0-2]');

Route::get('/book',  [ReservationController::class, 'create'])->name('reservations.create');
Route::post('/book', [ReservationController::class, 'store'])->name('reservations.store');

Route::get('/booking/confirmation/{reference}', [ReservationController::class, 'confirmation'])
    ->name('reservations.confirmation');

// Specific route BEFORE wildcard {reference} route
Route::get('/booking/status/{reference}', function (string $reference) {
    $r = \App\Models\Reservation::where('reference_number', $reference)->firstOrFail();
    return response()->json([
        'status'         => $r->status,
        'payment_status' => $r->payment_status,
        'confirmed'      => $r->status === 'confirmed',
    ]);
})->name('reservations.status.poll');

Route::get('/booking/{reference}', [ReservationController::class, 'show'])
    ->name('reservations.show');

// ── RECEIPT UPLOAD ──
Route::post('/booking/{reference}/receipt', [ReceiptController::class, 'upload'])->name('receipt.upload');

// ── API ROUTES ──
// SlotLockController handles booked + locked + blocked slots (only ONE /api/slots route)
Route::get('/api/slots',             [SlotLockController::class, 'availability'])->name('slots.availability');
Route::post('/api/slots/lock',       [SlotLockController::class, 'lock'])->name('slots.lock');
Route::post('/api/slots/unlock',     [SlotLockController::class, 'unlock'])->name('slots.unlock');
Route::post('/api/slots/unlock-all', [SlotLockController::class, 'unlockAll'])->name('slots.unlock-all');

// ── ADMIN AUTH (no middleware) ──
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login',  [AdminAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('login.post');
    Route::post('/logout',[AdminAuthController::class, 'logout'])->name('logout');
});

// ── ADMIN PANEL (protected by AdminMiddleware) ──
// FIXED: merged into ONE group — previously had 3 duplicate groups, only the first was ever reached
Route::prefix('admin')->name('admin.')->middleware('admin')->group(function () {
    Route::get('/',              [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/dashboard/revenue-month', [AdminController::class, 'revenueForMonth'])->name('dashboard.revenue-month');

    Route::get('/reservations',  [AdminController::class, 'reservations'])->name('reservations');
    Route::get('/reservations/{reservation}',          [AdminController::class, 'show'])->name('reservations.show');
    Route::patch('/reservations/{reservation}/status', [AdminController::class, 'updateStatus'])->name('reservations.status');
    Route::patch('/reservations/{reservation}/payment',[AdminController::class, 'updatePayment'])->name('reservations.payment');
    Route::delete('/reservations/{reservation}',       [AdminController::class, 'destroy'])->name('reservations.destroy');

    // ── Blocked Slots management ──
    Route::get('/blocked-slots',                  [BlockedSlotController::class, 'index'])->name('blocked-slots');
    Route::post('/blocked-slots',                 [BlockedSlotController::class, 'store'])->name('blocked-slots.store');
    Route::delete('/blocked-slots/{blockedSlot}',  [BlockedSlotController::class, 'destroy'])->name('blocked-slots.destroy');
    Route::post('/blocked-slots/bulk-destroy',     [BlockedSlotController::class, 'bulkDestroy'])->name('blocked-slots.bulk-destroy');
});