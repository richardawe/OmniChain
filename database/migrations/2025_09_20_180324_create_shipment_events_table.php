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
        Schema::create('shipment_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('freight_order_id')->constrained()->onDelete('cascade');
            $table->string('event_type'); // pickup, in_transit, delivery, exception, etc.
            $table->string('event_code')->nullable(); // standardized event codes
            $table->timestamp('event_time');
            $table->string('location_name')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('country')->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->text('description')->nullable();
            $table->text('notes')->nullable();
            $table->string('source')->nullable(); // carrier_api, manual, iot_device, etc.
            $table->string('reference_number')->nullable();
            $table->json('raw_data')->nullable(); // original event data
            $table->json('metadata')->nullable();
            $table->timestamps();
            
            $table->index(['freight_order_id', 'event_time']);
            $table->index(['event_type', 'event_time']);
            $table->index(['latitude', 'longitude']);
            $table->index('event_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipment_events');
    }
};
