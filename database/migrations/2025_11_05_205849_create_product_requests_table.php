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
        Schema::create('product_requests', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nome do cliente
            $table->string('email');
            $table->string('phone');
            $table->string('product_name'); // Nome do produto procurado
            $table->text('description'); // Descrição detalhada
            $table->integer('quantity_kg')->nullable(); // Quantidade em kg
            $table->enum('status', ['pending', 'processing', 'quoted', 'completed', 'cancelled'])->default('pending');
            $table->text('admin_notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_requests');
    }
};
