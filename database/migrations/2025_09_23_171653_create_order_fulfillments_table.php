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
        Schema::create('order_fulfillments', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->foreignId('customer_company_id')->constrained('companies')->onDelete('cascade');
            $table->foreignId('fulfillment_center_id')->constrained('locations')->onDelete('cascade');
            $table->foreignId('assigned_warehouse_user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->enum('order_type', ['standard', 'express', 'same_day', 'scheduled'])->default('standard');
            $table->enum('priority', ['low', 'normal', 'high', 'urgent'])->default('normal');
            $table->enum('status', ['received', 'processing', 'picked', 'packed', 'shipped', 'delivered', 'cancelled', 'returned'])->default('received');
            $table->datetime('order_date');
            $table->datetime('requested_delivery_date')->nullable();
            $table->datetime('promised_delivery_date')->nullable();
            $table->datetime('actual_delivery_date')->nullable();
            $table->datetime('picking_started_at')->nullable();
            $table->datetime('picking_completed_at')->nullable();
            $table->datetime('packing_started_at')->nullable();
            $table->datetime('packing_completed_at')->nullable();
            $table->datetime('shipping_date')->nullable();
            $table->integer('total_line_items')->default(0);
            $table->integer('total_quantity')->default(0);
            $table->decimal('total_weight_kg', 10, 3)->nullable();
            $table->decimal('total_volume_cubic_meters', 10, 3)->nullable();
            $table->decimal('order_value', 15, 2)->nullable();
            $table->string('currency_code', 3)->default('USD');
            $table->json('delivery_instructions')->nullable();
            $table->json('special_handling_requirements')->nullable();
            $table->text('customer_notes')->nullable();
            $table->text('internal_notes')->nullable();
            $table->json('fulfillment_metadata')->nullable(); // Additional fulfillment information
            $table->timestamps();
            
            $table->index(['customer_company_id', 'order_date']);
            $table->index(['fulfillment_center_id', 'status']);
            $table->index(['status', 'requested_delivery_date']);
            $table->index(['order_type', 'priority']);
            $table->index('order_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_fulfillments');
    }
};