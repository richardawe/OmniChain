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
        Schema::create('order_fulfillment_line_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_fulfillment_id')->constrained('order_fulfillments')->onDelete('cascade');
            $table->integer('line_number');
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->string('product_sku');
            $table->string('product_name');
            $table->integer('ordered_quantity');
            $table->integer('picked_quantity')->default(0);
            $table->integer('packed_quantity')->default(0);
            $table->integer('shipped_quantity')->default(0);
            $table->integer('delivered_quantity')->default(0);
            $table->string('unit_of_measure')->default('EA');
            $table->decimal('unit_price', 10, 2)->nullable();
            $table->decimal('line_total', 10, 2)->nullable();
            $table->decimal('weight_per_unit_kg', 8, 3)->nullable();
            $table->decimal('volume_per_unit_cubic_meters', 8, 3)->nullable();
            $table->json('product_attributes')->nullable(); // Size, color, etc.
            $table->json('picking_instructions')->nullable();
            $table->json('packing_requirements')->nullable();
            $table->text('notes')->nullable();
            $table->json('line_item_metadata')->nullable(); // Additional line item information
            $table->timestamps();
            
            $table->unique(['order_fulfillment_id', 'line_number']);
            $table->index(['product_id', 'ordered_quantity']);
            $table->index(['product_sku', 'picked_quantity']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_fulfillment_line_items');
    }
};