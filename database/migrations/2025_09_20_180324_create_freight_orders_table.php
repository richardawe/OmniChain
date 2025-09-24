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
        Schema::create('freight_orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->foreignId('shipper_company_id')->constrained('companies')->onDelete('cascade');
            $table->foreignId('carrier_company_id')->nullable()->constrained('companies')->onDelete('set null');
            $table->foreignId('origin_location_id')->constrained('locations')->onDelete('cascade');
            $table->foreignId('destination_location_id')->constrained('locations')->onDelete('cascade');
            $table->enum('service_type', ['ltl', 'ftl', 'air', 'ocean', 'rail', 'parcel'])->default('ltl');
            $table->enum('priority', ['low', 'normal', 'high', 'urgent'])->default('normal');
            $table->timestamp('pickup_date')->nullable();
            $table->timestamp('delivery_date')->nullable();
            $table->timestamp('requested_pickup_date')->nullable();
            $table->timestamp('requested_delivery_date')->nullable();
            $table->decimal('total_weight', 10, 3)->nullable(); // in kg
            $table->decimal('total_volume', 10, 3)->nullable(); // in cubic meters
            $table->integer('total_pieces')->nullable();
            $table->decimal('declared_value', 12, 2)->nullable();
            $table->string('currency', 3)->default('USD');
            $table->enum('status', ['draft', 'quoted', 'booked', 'picked_up', 'in_transit', 'delivered', 'exception', 'cancelled'])->default('draft');
            $table->text('special_instructions')->nullable();
            $table->json('equipment_requirements')->nullable();
            $table->json('temperature_requirements')->nullable();
            $table->json('hazardous_details')->nullable();
            $table->json('customs_details')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
            
            $table->index(['shipper_company_id', 'status']);
            $table->index(['carrier_company_id', 'status']);
            $table->index(['origin_location_id', 'destination_location_id']);
            $table->index(['pickup_date', 'delivery_date']);
            $table->index('order_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('freight_orders');
    }
};
