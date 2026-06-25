<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('slot_locks', function (Blueprint $table) {
            $table->id();
            $table->integer('court_id');
            $table->date('booking_date');
            $table->string('time_slot');
            $table->string('session_id');        // who holds the lock
            $table->timestamp('expires_at');     // lock expiry (3 mins from lock)
            $table->timestamps();

            // One lock per slot per court per date
            $table->unique(['court_id', 'booking_date', 'time_slot']);
            $table->index('expires_at');         // fast cleanup queries
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('slot_locks');
    }
};