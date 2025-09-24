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
        Schema::create('geofence_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('driver_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('delivery_task_id')->nullable()->constrained('delivery_tasks')->onDelete('set null');
            $table->foreignId('freight_order_id')->nullable()->constrained('freight_orders')->onDelete('set null');
            $table->foreignId('location_id')->nullable()->constrained('locations')->onDelete('set null');
            $table->string('geofence_name');
            $table->enum('event_type', ['enter', 'exit', 'dwell'])->default('enter');
            $table->datetime('event_timestamp');
            $table->decimal('latitude', 10, 8);
            $table->decimal('longitude', 11, 8);
            $table->decimal('accuracy_meters', 8, 2)->nullable();
            $table->decimal('speed_kmh', 5, 2)->nullable();
            $table->decimal('heading_degrees', 5, 2)->nullable();
            $table->decimal('distance_from_center_meters', 10, 3)->nullable();
            $table->integer('dwell_time_minutes')->nullable(); // For dwell events
            $table->boolean('is_expected')->default(true); // Whether this event was expected
            $table->enum('trigger_action', ['none', 'notification', 'alert', 'auto_status_update'])->default('notification');
            $table->text('action_taken')->nullable();
            $table->json('geofence_metadata')->nullable(); // Additional geofence information
            $table->timestamps();
            
            $table->index(['driver_id', 'event_timestamp']);
            $table->index(['delivery_task_id', 'event_type']);
            $table->index(['freight_order_id', 'event_timestamp']);
            $table->index(['location_id', 'event_type']);
            $table->index(['event_type', 'is_expected']);
            $table->index('event_timestamp');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('geofence_events');
    }
};