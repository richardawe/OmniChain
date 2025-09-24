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
        Schema::create('machine_telemetry', function (Blueprint $table) {
            $table->id();
            $table->foreignId('machine_id')->constrained('machines')->onDelete('cascade');
            $table->datetime('timestamp');
            $table->enum('operational_state', ['running', 'idle', 'down', 'maintenance'])->default('idle');
            $table->decimal('availability_percentage', 5, 2)->default(0); // OEE Availability
            $table->decimal('performance_percentage', 5, 2)->default(0); // OEE Performance
            $table->decimal('quality_percentage', 5, 2)->default(100); // OEE Quality
            $table->decimal('overall_oee', 5, 2)->default(0); // Overall OEE
            $table->decimal('cycle_time_seconds', 10, 2)->nullable();
            $table->integer('units_produced')->default(0);
            $table->decimal('temperature_celsius', 8, 2)->nullable();
            $table->decimal('vibration_level', 10, 4)->nullable();
            $table->decimal('pressure_psi', 10, 2)->nullable();
            $table->decimal('power_consumption_kw', 10, 2)->nullable();
            $table->json('error_codes')->nullable(); // Array of error codes
            $table->json('sensor_data')->nullable(); // Additional sensor readings
            $table->text('notes')->nullable();
            $table->timestamps();

            // Indexes for performance
            $table->index(['machine_id', 'timestamp']);
            $table->index('operational_state');
            $table->index('timestamp');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('machine_telemetry');
    }
};