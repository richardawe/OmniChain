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
        Schema::create('route_plans', function (Blueprint $table) {
            $table->id();
            $table->string('route_number')->unique();
            $table->foreignId('carrier_company_id')->constrained('companies')->onDelete('cascade');
            $table->foreignId('assigned_driver_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('vehicle_id')->nullable()->constrained('vehicles')->onDelete('set null');
            $table->string('route_type')->default('delivery'); // delivery, pickup, mixed
            $table->enum('status', ['planned', 'assigned', 'in_progress', 'completed', 'cancelled'])->default('planned');
            $table->date('planned_date');
            $table->time('planned_start_time');
            $table->time('planned_end_time')->nullable();
            $table->date('actual_start_date')->nullable();
            $table->time('actual_start_time')->nullable();
            $table->date('actual_end_date')->nullable();
            $table->time('actual_end_time')->nullable();
            $table->integer('total_stops')->default(0);
            $table->integer('completed_stops')->default(0);
            $table->decimal('total_distance_km', 10, 3)->nullable();
            $table->integer('estimated_duration_minutes')->nullable();
            $table->decimal('fuel_cost', 10, 2)->nullable();
            $table->decimal('toll_cost', 10, 2)->nullable();
            $table->json('route_waypoints')->nullable(); // GPS coordinates for route optimization
            $table->json('route_metadata')->nullable(); // Additional route information
            $table->text('special_instructions')->nullable();
            $table->foreignId('created_by_user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            
            $table->index(['carrier_company_id', 'planned_date']);
            $table->index(['assigned_driver_id', 'planned_date']);
            $table->index('status');
            $table->index('route_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('route_plans');
    }
};