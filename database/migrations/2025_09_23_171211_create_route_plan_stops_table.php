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
        Schema::create('route_plan_stops', function (Blueprint $table) {
            $table->id();
            $table->foreignId('route_plan_id')->constrained('route_plans')->onDelete('cascade');
            $table->integer('sequence_number');
            $table->foreignId('location_id')->constrained('locations')->onDelete('cascade');
            $table->foreignId('freight_order_id')->nullable()->constrained('freight_orders')->onDelete('set null');
            $table->enum('stop_type', ['pickup', 'delivery', 'service', 'fuel', 'rest'])->default('delivery');
            $table->enum('status', ['pending', 'arrived', 'in_progress', 'completed', 'skipped', 'failed'])->default('pending');
            $table->datetime('planned_arrival_time');
            $table->datetime('planned_departure_time')->nullable();
            $table->datetime('actual_arrival_time')->nullable();
            $table->datetime('actual_departure_time')->nullable();
            $table->integer('planned_duration_minutes')->default(30);
            $table->integer('actual_duration_minutes')->nullable();
            $table->decimal('distance_from_previous_km', 10, 3)->nullable();
            $table->text('special_instructions')->nullable();
            $table->text('notes')->nullable();
            $table->json('stop_metadata')->nullable(); // Additional stop information
            $table->timestamps();
            
            $table->unique(['route_plan_id', 'sequence_number']);
            $table->index(['route_plan_id', 'status']);
            $table->index(['location_id', 'planned_arrival_time']);
            $table->index('freight_order_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('route_plan_stops');
    }
};