<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();

            // ── CLIENT INFORMATION ──
            $table->string('full_name');
            $table->string('mobile_number', 11);
            $table->string('email');

            // ── BOOKING DETAILS ──
            $table->unsignedTinyInteger('court_id');         // 0 = Court A, 1 = Court B, 2 = Court C
            $table->string('court_name');
            $table->date('booking_date');
            $table->string('time_slot');                     // e.g. "9:00–10:00 AM"
            $table->string('time_period', 2);               // "AM" or "PM"
            $table->unsignedSmallInteger('amount');          // price in PHP

            // ── PAYMENT ──
            $table->enum('payment_method', [
                'GCash',
                'PayMaya',
                'Bank Transfer',
                'Pay at the counter',
            ])->default('Pay at the counter');

            $table->enum('payment_status', [
                'pending',
                'paid',
                'cancelled',
            ])->default('pending');

            // ── STATUS & REFERENCE ──
            $table->string('reference_number', 12)->unique(); // e.g. WPX-123456
            $table->enum('status', [
                'pending',
                'confirmed',
                'cancelled',
                'completed',
            ])->default('pending');

            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};