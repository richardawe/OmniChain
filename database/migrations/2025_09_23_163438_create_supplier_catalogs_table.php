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
        Schema::create('supplier_catalogs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->onDelete('cascade');
            $table->foreignId('buyer_company_id')->constrained('companies')->onDelete('cascade');
            $table->string('supplier_part_number');
            $table->string('buyer_sku')->nullable(); // Maps to buyer's SKU
            $table->string('product_name');
            $table->text('description')->nullable();
            $table->string('category')->nullable();
            $table->string('unit_of_measure')->default('EA');
            $table->decimal('unit_price', 10, 4)->nullable();
            $table->string('currency', 3)->default('USD');
            $table->integer('lead_time_days')->nullable();
            $table->decimal('minimum_order_quantity', 10, 2)->nullable();
            $table->json('pricing_tiers')->nullable(); // Volume-based pricing
            $table->json('availability_indicators')->nullable(); // Stock levels, etc.
            $table->date('effective_date');
            $table->date('expiry_date')->nullable();
            $table->enum('status', ['active', 'inactive', 'discontinued'])->default('active');
            $table->json('specifications')->nullable(); // Technical specifications
            $table->json('metadata')->nullable();
            $table->timestamps();
            
            $table->index(['company_id', 'buyer_company_id']);
            $table->index(['supplier_part_number', 'buyer_sku']);
            $table->index(['effective_date', 'expiry_date']);
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('supplier_catalogs');
    }
};