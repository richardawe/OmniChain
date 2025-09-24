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
        Schema::create('outbound_shipments', function (Blueprint $table) {
            $table->id();
            $table->string('shipment_id')->unique();
            $table->string('pick_list_id')->nullable();
            $table->foreignId('ship_from_location_id')->constrained('locations')->onDelete('cascade');
            $table->foreignId('ship_to_location_id')->constrained('locations')->onDelete('cascade');
            $table->foreignId('carrier_id')->nullable()->constrained('companies')->onDelete('set null');
            $table->string('tracking_number')->nullable();
            $table->enum('order_type', ['customer_order', 'transfer', 'return', 'sample', 'repair'])->default('customer_order');
            $table->enum('status', ['pending', 'picking', 'picked', 'packing', 'packed', 'shipped', 'delivered', 'exception'])->default('pending');
            $table->datetime('scheduled_ship_date')->nullable();
            $table->datetime('actual_ship_date')->nullable();
            $table->datetime('estimated_delivery_date')->nullable();
            $table->datetime('actual_delivery_date')->nullable();
            $table->decimal('total_weight', 10, 3)->nullable();
            $table->decimal('total_volume', 10, 3)->nullable();
            $table->json('dimensions')->nullable(); // Length, width, height
            $table->decimal('shipping_cost', 10, 2)->nullable();
            $table->string('service_level')->nullable();
            $table->boolean('signature_required')->default(false);
            $table->boolean('insurance_required')->default(false);
            $table->decimal('declared_value', 10, 2)->nullable();
            $table->text('special_instructions')->nullable();
            $table->json('packed_items')->nullable(); // Array of packed items with serials
            $table->json('shipping_metadata')->nullable();
            $table->timestamps();

            // Indexes
            $table->index(['ship_from_location_id', 'status']);
            $table->index(['ship_to_location_id', 'status']);
            $table->index('tracking_number');
            $table->index('scheduled_ship_date');
            $table->index('carrier_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('outbound_shipments');
    }
};