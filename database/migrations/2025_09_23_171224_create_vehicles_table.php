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
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies')->onDelete('cascade');
            $table->string('vehicle_number')->unique();
            $table->string('license_plate')->unique();
            $table->string('make');
            $table->string('model');
            $table->integer('year')->nullable();
            $table->string('vehicle_type'); // truck, van, trailer, container
            $table->enum('vehicle_class', ['light_duty', 'medium_duty', 'heavy_duty'])->default('medium_duty');
            $table->decimal('max_weight_kg', 10, 3)->nullable();
            $table->decimal('max_volume_cubic_meters', 10, 3)->nullable();
            $table->integer('max_pallets')->nullable();
            $table->json('equipment_features')->nullable(); // refrigeration, liftgate, etc.
            $table->string('fuel_type')->default('diesel'); // diesel, electric, hybrid, gas
            $table->decimal('fuel_capacity_liters', 8, 2)->nullable();
            $table->decimal('average_fuel_consumption_km_per_liter', 5, 2)->nullable();
            $table->string('insurance_policy_number')->nullable();
            $table->date('insurance_expiry_date')->nullable();
            $table->string('registration_number')->nullable();
            $table->date('registration_expiry_date')->nullable();
            $table->string('vin_number')->nullable();
            $table->enum('status', ['active', 'inactive', 'maintenance', 'retired'])->default('active');
            $table->foreignId('assigned_driver_id')->nullable()->constrained('users')->onDelete('set null');
            $table->json('vehicle_metadata')->nullable(); // Additional vehicle information
            $table->timestamps();
            
            $table->index(['company_id', 'status']);
            $table->index(['vehicle_type', 'vehicle_class']);
            $table->index('assigned_driver_id');
            $table->index('license_plate');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};