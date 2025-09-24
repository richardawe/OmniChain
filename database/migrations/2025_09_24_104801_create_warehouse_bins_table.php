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
        Schema::create('warehouse_bins', function (Blueprint $table) {
            $table->id();
            $table->string('bin_id')->unique();
            $table->foreignId('location_id')->constrained('locations')->onDelete('cascade');
            $table->string('bin_name');
            $table->enum('bin_type', ['storage', 'picking', 'receiving', 'shipping', 'cross_dock', 'quarantine'])->default('storage');
            $table->string('zone')->nullable();
            $table->string('aisle')->nullable();
            $table->string('rack')->nullable();
            $table->string('level')->nullable();
            $table->string('position')->nullable();
            $table->decimal('capacity_volume', 10, 3)->nullable(); // in cubic meters
            $table->decimal('capacity_weight', 10, 3)->nullable(); // in kg
            $table->decimal('current_volume', 10, 3)->default(0);
            $table->decimal('current_weight', 10, 3)->default(0);
            $table->decimal('utilization_percentage', 5, 2)->default(0);
            $table->enum('status', ['active', 'inactive', 'maintenance', 'reserved'])->default('active');
            $table->boolean('requires_temperature_control')->default(false);
            $table->decimal('min_temperature', 5, 2)->nullable();
            $table->decimal('max_temperature', 5, 2)->nullable();
            $table->boolean('hazardous_material_allowed')->default(false);
            $table->json('bin_metadata')->nullable();
            $table->timestamps();

            // Indexes
            $table->index(['location_id', 'bin_type']);
            $table->index(['zone', 'aisle', 'rack']);
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('warehouse_bins');
    }
};