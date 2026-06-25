<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\SlotLockController;

// ── PUBLIC ROUTES ──
Route::get('/', [WelcomeController::class, 'index']);

Route::get('/courts/{id}', fn($id) => view('court-detail', ['courtId' => $id]))
    ->where('id', '[0-2]');

Route::get('/book',  [ReservationController::class, 'create'])->name('reservations.create');
Route::post('/book', [ReservationController::class, 'store'])->name('reservations.store');

Route::get('/booking/confirmation/{reference}', [ReservationController::class, 'confirmation'])
    ->name('reservations.confirmation');
Route::get('/booking/{reference}', [ReservationController::class, 'show'])
    ->name('reservations.show');
Route::get('/api/slots', [ReservationController::class, 'bookedSlots'])
    ->name('reservations.slots');

// ── ADMIN AUTH (no middleware) ──
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login',  [AdminAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('login.post');
    Route::post('/logout',[AdminAuthController::class, 'logout'])->name('logout');
});

// ── ADMIN PANEL (protected by AdminMiddleware) ──
Route::prefix('admin')->name('admin.')->middleware('admin')->group(function () {
    Route::get('/',              [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/reservations',  [AdminController::class, 'reservations'])->name('reservations');
    Route::get('/reservations/{reservation}',          [AdminController::class, 'show'])->name('reservations.show');
    Route::patch('/reservations/{reservation}/status', [AdminController::class, 'updateStatus'])->name('reservations.status');
    Route::patch('/reservations/{reservation}/payment',[AdminController::class, 'updatePayment'])->name('reservations.payment');
    Route::delete('/reservations/{reservation}',       [AdminController::class, 'destroy'])->name('reservations.destroy');
});

// ── BOOKING STATUS POLL ──
Route::get('/booking/status/{reference}', function (string $reference) {
    $r = \App\Models\Reservation::where('reference_number', $reference)->firstOrFail();
    return response()->json([
        'status'         => $r->status,
        'payment_status' => $r->payment_status,
        'confirmed'      => $r->status === 'confirmed',
    ]);
})->name('reservations.status.poll');

// ── RECEIPT UPLOAD ──
use App\Http\Controllers\ReceiptController;
Route::post('/booking/{reference}/receipt', [ReceiptController::class, 'upload'])->name('receipt.upload');

// Availability (booked + locked slots)
Route::get('/api/slots', [SlotLockController::class, 'availability'])->name('slots.availability');
 
// Lock a slot when user selects it
Route::post('/api/slots/lock',       [SlotLockController::class, 'lock'])->name('slots.lock');
Route::post('/api/slots/unlock',     [SlotLockController::class, 'unlock'])->name('slots.unlock');
Route::post('/api/slots/unlock-all', [SlotLockController::class, 'unlockAll'])->name('slots.unlock-all');