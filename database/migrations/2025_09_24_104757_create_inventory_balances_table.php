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
        Schema::create('inventory_balances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('location_id')->constrained('locations')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->string('lot_bin')->nullable();
            $table->decimal('quantity_on_hand', 10, 3)->default(0);
            $table->decimal('quantity_available', 10, 3)->default(0);
            $table->decimal('quantity_allocated', 10, 3)->default(0);
            $table->decimal('quantity_on_order', 10, 3)->default(0);
            $table->decimal('reorder_point', 10, 3)->nullable();
            $table->decimal('safety_stock', 10, 3)->nullable();
            $table->decimal('max_stock_level', 10, 3)->nullable();
            $table->date('last_count_date')->nullable();
            $table->decimal('last_count_quantity', 10, 3)->nullable();
            $table->decimal('average_cost', 10, 2)->nullable();
            $table->decimal('total_value', 15, 2)->nullable();
            $table->enum('status', ['active', 'inactive', 'quarantined', 'reserved'])->default('active');
            $table->json('inventory_metadata')->nullable();
            $table->timestamps();

            // Indexes
            $table->index(['location_id', 'product_id']);
            $table->index('lot_bin');
            $table->index('status');
            $table->unique(['location_id', 'product_id', 'lot_bin']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_balances');
    }
};