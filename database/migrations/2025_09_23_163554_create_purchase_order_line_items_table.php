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
        Schema::create('purchase_order_line_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_order_id')->constrained()->onDelete('cascade');
            $table->integer('line_number');
            $table->string('sku')->nullable(); // Buyer's SKU
            $table->string('supplier_part_number')->nullable();
            $table->string('product_name');
            $table->text('description')->nullable();
            $table->decimal('quantity_ordered', 10, 3);
            $table->string('unit_of_measure')->default('EA');
            $table->decimal('unit_price', 10, 4);
            $table->decimal('line_total', 15, 2)->nullable();
            $table->date('promised_date')->nullable();
            $table->foreignId('delivery_location_id')->nullable()->constrained('locations')->onDelete('set null');
            $table->json('schedule_lines')->nullable(); // Multiple delivery schedules
            $table->decimal('quantity_received', 10, 3)->default(0);
            $table->decimal('quantity_rejected', 10, 3)->default(0);
            $table->decimal('quantity_pending', 10, 3)->nullable();
            $table->enum('status', ['open', 'partially_received', 'fully_received', 'rejected', 'cancelled'])->default('open');
            $table->text('line_notes')->nullable();
            $table->json('quality_requirements')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
            
            $table->index(['purchase_order_id', 'line_number']);
            $table->index(['sku', 'supplier_part_number']);
            $table->index('promised_date');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_order_line_items');
    }
};