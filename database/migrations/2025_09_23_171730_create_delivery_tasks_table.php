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
        Schema::create('delivery_tasks', function (Blueprint $table) {
            $table->id();
            $table->string('task_number')->unique();
            $table->foreignId('order_fulfillment_id')->constrained('order_fulfillments')->onDelete('cascade');
            $table->foreignId('freight_order_id')->nullable()->constrained('freight_orders')->onDelete('set null');
            $table->foreignId('route_plan_id')->nullable()->constrained('route_plans')->onDelete('set null');
            $table->foreignId('assigned_driver_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('pickup_location_id')->constrained('locations')->onDelete('cascade');
            $table->foreignId('delivery_location_id')->constrained('locations')->onDelete('cascade');
            $table->enum('task_type', ['pickup', 'delivery', 'return_pickup', 'service_call'])->default('delivery');
            $table->enum('priority', ['low', 'normal', 'high', 'urgent'])->default('normal');
            $table->enum('status', ['pending', 'assigned', 'in_progress', 'completed', 'failed', 'cancelled'])->default('pending');
            $table->datetime('assigned_at')->nullable();
            $table->datetime('started_at')->nullable();
            $table->datetime('completed_at')->nullable();
            $table->datetime('scheduled_start_time')->nullable();
            $table->datetime('scheduled_end_time')->nullable();
            $table->datetime('actual_start_time')->nullable();
            $table->datetime('actual_end_time')->nullable();
            $table->decimal('estimated_duration_minutes', 8, 2)->nullable();
            $table->decimal('actual_duration_minutes', 8, 2)->nullable();
            $table->decimal('distance_km', 10, 3)->nullable();
            $table->text('task_instructions')->nullable();
            $table->text('delivery_instructions')->nullable();
            $table->text('special_requirements')->nullable();
            $table->json('delivery_contact_info')->nullable();
            $table->json('task_metadata')->nullable(); // Additional task information
            $table->timestamps();
            
            $table->index(['assigned_driver_id', 'status']);
            $table->index(['status', 'scheduled_start_time']);
            $table->index(['task_type', 'priority']);
            $table->index(['pickup_location_id', 'delivery_location_id']);
            $table->index('task_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('delivery_tasks');
    }
};