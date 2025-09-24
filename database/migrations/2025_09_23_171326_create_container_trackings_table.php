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
        Schema::create('container_trackings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('freight_order_id')->constrained('freight_orders')->onDelete('cascade');
            $table->string('container_number')->unique();
            $table->string('seal_number')->nullable();
            $table->enum('container_type', ['dry', 'reefer', 'tank', 'flat_rack', 'open_top', 'high_cube'])->default('dry');
            $table->enum('container_size', ['20ft', '40ft', '45ft', '48ft'])->default('40ft');
            $table->string('container_owner')->nullable();
            $table->string('container_operator')->nullable();
            $table->decimal('max_gross_weight_kg', 10, 3)->nullable();
            $table->decimal('tare_weight_kg', 10, 3)->nullable();
            $table->decimal('payload_weight_kg', 10, 3)->nullable();
            $table->decimal('max_volume_cubic_meters', 10, 3)->nullable();
            $table->json('temperature_settings')->nullable(); // For reefer containers
            $table->string('vessel_name')->nullable();
            $table->string('voyage_number')->nullable();
            $table->string('booking_reference')->nullable();
            $table->enum('status', ['empty', 'loaded', 'in_transit', 'at_port', 'customs_hold', 'delivered', 'returned'])->default('empty');
            $table->datetime('loaded_date')->nullable();
            $table->datetime('departure_date')->nullable();
            $table->datetime('arrival_date')->nullable();
            $table->datetime('discharge_date')->nullable();
            $table->datetime('delivery_date')->nullable();
            $table->datetime('return_date')->nullable();
            $table->string('current_location')->nullable();
            $table->decimal('current_latitude', 10, 8)->nullable();
            $table->decimal('current_longitude', 11, 8)->nullable();
            $table->datetime('last_update_timestamp')->nullable();
            $table->json('container_metadata')->nullable(); // Additional container information
            $table->timestamps();
            
            $table->index(['freight_order_id', 'status']);
            $table->index(['container_number', 'status']);
            $table->index(['vessel_name', 'voyage_number']);
            $table->index('booking_reference');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('container_trackings');
    }
};