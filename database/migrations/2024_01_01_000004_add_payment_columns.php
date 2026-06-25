<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            $table->string('paymongo_payment_intent_id')->nullable()->after('payment_status');
            $table->string('paymongo_source_id')->nullable()->after('paymongo_payment_intent_id');
            $table->string('payment_url')->nullable()->after('paymongo_source_id');
            $table->timestamp('paid_at')->nullable()->after('payment_url');
        });
    }

    public function down(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            $table->dropColumn([
                'paymongo_payment_intent_id',
                'paymongo_source_id',
                'payment_url',
                'paid_at',
            ]);
        });
    }
};