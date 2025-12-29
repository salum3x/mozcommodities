<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->timestamps();
        });

        // Insert default values
        DB::table('settings')->insert([
            ['key' => 'company_name', 'value' => 'MozCommodities', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'company_email', 'value' => 'info@mozcommodities.co.mz', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'company_phone', 'value' => '+258 84 000 0000', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'company_whatsapp', 'value' => '258840000000', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'company_address', 'value' => 'Maputo, Moçambique', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'bank_name', 'value' => 'Standard Bank', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'bank_nib', 'value' => '0000 0000 0000 0000 0000', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'bank_account_holder', 'value' => 'MozCommodities Lda', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'mpesa_number', 'value' => '+258 84 000 0000', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
