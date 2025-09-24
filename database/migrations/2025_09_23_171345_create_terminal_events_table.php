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
        Schema::create('terminal_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('container_tracking_id')->constrained('container_trackings')->onDelete('cascade');
            $table->foreignId('freight_order_id')->constrained('freight_orders')->onDelete('cascade');
            $table->string('terminal_name');
            $table->string('terminal_code')->nullable();
            $table->string('terminal_location')->nullable();
            $table->decimal('terminal_latitude', 10, 8)->nullable();
            $table->decimal('terminal_longitude', 11, 8)->nullable();
            $table->enum('event_type', [
                'gate_in', 'gate_out', 'vessel_arrival', 'vessel_departure', 
                'crane_lift_on', 'crane_lift_off', 'yard_move', 'customs_inspection',
                'security_inspection', 'maintenance', 'damage_assessment', 'temperature_check'
            ]);
            $table->datetime('event_timestamp');
            $table->datetime('planned_timestamp')->nullable();
            $table->string('equipment_id')->nullable(); // Crane, truck, etc.
            $table->string('operator_id')->nullable();
            $table->enum('event_status', ['scheduled', 'in_progress', 'completed', 'delayed', 'cancelled'])->default('scheduled');
            $table->text('event_description')->nullable();
            $table->json('event_metadata')->nullable(); // Additional event information
            $table->decimal('duration_minutes', 8, 2)->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->index(['container_tracking_id', 'event_timestamp']);
            $table->index(['freight_order_id', 'event_timestamp']);
            $table->index(['terminal_name', 'event_timestamp']);
            $table->index(['event_type', 'event_status']);
            $table->index('event_timestamp');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('terminal_events');
    }
};