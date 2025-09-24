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
        Schema::create('vehicle_telematics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_id')->constrained('vehicles')->onDelete('cascade');
            $table->foreignId('route_plan_id')->nullable()->constrained('route_plans')->onDelete('set null');
            $table->foreignId('driver_id')->nullable()->constrained('users')->onDelete('set null');
            $table->datetime('timestamp');
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->decimal('speed_kmh', 5, 2)->nullable();
            $table->decimal('heading_degrees', 5, 2)->nullable();
            $table->decimal('altitude_meters', 8, 2)->nullable();
            $table->decimal('odometer_km', 10, 3)->nullable();
            $table->decimal('fuel_level_percentage', 5, 2)->nullable();
            $table->decimal('fuel_consumed_liters', 8, 3)->nullable();
            $table->integer('engine_rpm')->nullable();
            $table->decimal('engine_load_percentage', 5, 2)->nullable();
            $table->decimal('coolant_temperature_celsius', 5, 2)->nullable();
            $table->decimal('battery_voltage', 5, 2)->nullable();
            $table->boolean('engine_on')->default(false);
            $table->boolean('driver_seatbelt')->default(false);
            $table->boolean('door_open')->default(false);
            $table->json('diagnostic_codes')->nullable(); // DTC codes
            $table->json('telematics_metadata')->nullable(); // Additional telematics data
            $table->timestamps();
            
            $table->index(['vehicle_id', 'timestamp']);
            $table->index(['route_plan_id', 'timestamp']);
            $table->index(['driver_id', 'timestamp']);
            $table->index('timestamp');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicle_telematics');
    }
};