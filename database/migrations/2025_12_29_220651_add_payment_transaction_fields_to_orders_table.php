<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Payment gateway fields
            $table->string('transaction_id')->nullable()->after('payment_proof');
            $table->string('payment_reference')->nullable()->after('transaction_id');
            $table->string('payment_gateway')->nullable()->after('payment_reference');
            $table->json('payment_response')->nullable()->after('payment_gateway');
            $table->timestamp('paid_at')->nullable()->after('payment_response');
        });

        // Update payment_method enum to include new options
        Schema::table('orders', function (Blueprint $table) {
            $table->string('payment_method')->default('mpesa')->change();
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'transaction_id',
                'payment_reference',
                'payment_gateway',
                'payment_response',
                'paid_at'
            ]);
        });
    }
};
