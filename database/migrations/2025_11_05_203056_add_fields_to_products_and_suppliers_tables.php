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
        // Adicionar campos aos produtos
        Schema::table('products', function (Blueprint $table) {
            $table->integer('stock_kg')->default(0)->after('price_per_kg'); // Stock em kg
            $table->decimal('cost_price', 10, 2)->nullable()->after('price_per_kg'); // Preço de custo (P.B.F.)
            $table->decimal('platform_margin', 5, 2)->default(0)->after('cost_price'); // Margem da plataforma %
            $table->enum('approval_status', ['pending', 'approved', 'rejected'])->default('pending')->after('is_active');
            $table->text('rejection_reason')->nullable()->after('approval_status');
            $table->boolean('is_company_product')->default(false)->after('supplier_id'); // Produto próprio ou de fornecedor
        });

        // Adicionar campos aos fornecedores
        Schema::table('suppliers', function (Blueprint $table) {
            $table->string('document_number')->nullable()->after('phone'); // BI ou NUIT
            $table->string('whatsapp')->nullable()->after('phone');
            $table->text('address')->nullable()->after('whatsapp'); // Morada completa
            $table->string('latitude')->nullable()->after('address'); // Para cálculo de distância
            $table->string('longitude')->nullable()->after('latitude');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['stock_kg', 'cost_price', 'platform_margin', 'approval_status', 'rejection_reason', 'is_company_product']);
        });

        Schema::table('suppliers', function (Blueprint $table) {
            $table->dropColumn(['document_number', 'whatsapp', 'address', 'latitude', 'longitude']);
        });
    }
};
