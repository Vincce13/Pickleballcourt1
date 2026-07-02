<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('blocked_slots', function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('court_id');      // 0, 1, 2
            $table->date('blocked_date');
            $table->string('time_slot');                   // e.g. "6:00–7:00 AM"
            $table->string('reason')->nullable();           // e.g. "Tournament Event"
            $table->string('blocked_by')->nullable();       // admin name/email who blocked it
            $table->timestamps();

            $table->unique(['court_id', 'blocked_date', 'time_slot'], 'unique_block_slot');
            $table->index(['court_id', 'blocked_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('blocked_slots');
    }
};