<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            // Replace single time_slot + time_period with JSON array of slots
            $table->json('time_slots')->nullable()->after('booking_date');
            $table->dropColumn(['time_slot', 'time_period']);
        });
    }

    public function down(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            $table->string('time_slot')->nullable()->after('booking_date');
            $table->string('time_period', 2)->nullable()->after('time_slot');
            $table->dropColumn('time_slots');
        });
    }
};