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
        Schema::create('machines', function (Blueprint $table) {
            $table->id();
            $table->string('machine_id')->unique();
            $table->string('machine_name');
            $table->string('machine_type');
            $table->string('manufacturer');
            $table->string('model');
            $table->string('serial_number')->nullable();
            $table->foreignId('location_id')->constrained('locations')->onDelete('cascade');
            $table->enum('status', ['active', 'maintenance', 'down', 'retired'])->default('active');
            $table->date('installation_date');
            $table->date('last_maintenance_date')->nullable();
            $table->date('next_maintenance_date')->nullable();
            $table->decimal('capacity_per_hour', 10, 2)->nullable();
            $table->decimal('efficiency_rating', 5, 2)->default(100.00); // Percentage
            $table->json('operational_parameters')->nullable(); // Machine-specific parameters
            $table->json('machine_metadata')->nullable(); // Additional machine information
            $table->timestamps();

            // Indexes
            $table->index('location_id');
            $table->index('machine_type');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('machines');
    }
};