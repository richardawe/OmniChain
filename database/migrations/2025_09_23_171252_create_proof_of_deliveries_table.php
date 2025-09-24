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
        Schema::create('proof_of_deliveries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('freight_order_id')->constrained('freight_orders')->onDelete('cascade');
            $table->foreignId('route_plan_stop_id')->nullable()->constrained('route_plan_stops')->onDelete('set null');
            $table->foreignId('driver_id')->constrained('users')->onDelete('cascade');
            $table->datetime('delivery_timestamp');
            $table->decimal('delivery_latitude', 10, 8)->nullable();
            $table->decimal('delivery_longitude', 11, 8)->nullable();
            $table->enum('delivery_status', ['delivered', 'partially_delivered', 'failed', 'rescheduled', 'refused'])->default('delivered');
            $table->string('recipient_name')->nullable();
            $table->string('recipient_phone')->nullable();
            $table->string('recipient_email')->nullable();
            $table->string('recipient_signature')->nullable(); // Base64 encoded signature
            $table->string('delivery_photo_path')->nullable();
            $table->text('delivery_notes')->nullable();
            $table->integer('delivered_quantity')->nullable();
            $table->integer('expected_quantity')->nullable();
            $table->decimal('delivered_weight_kg', 10, 3)->nullable();
            $table->decimal('delivered_volume_cubic_meters', 10, 3)->nullable();
            $table->json('damage_report')->nullable(); // Details of any damage found
            $table->json('delivery_conditions')->nullable(); // Weather, temperature, etc.
            $table->text('failure_reason')->nullable(); // If delivery failed
            $table->datetime('rescheduled_date')->nullable(); // If rescheduled
            $table->text('rescheduled_reason')->nullable();
            $table->boolean('customer_satisfaction_rating')->nullable(); // true/false for basic satisfaction
            $table->text('customer_feedback')->nullable();
            $table->json('pod_metadata')->nullable(); // Additional delivery information
            $table->timestamps();
            
            $table->index(['freight_order_id', 'delivery_timestamp']);
            $table->index(['driver_id', 'delivery_timestamp']);
            $table->index(['delivery_status', 'delivery_timestamp']);
            $table->index('route_plan_stop_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proof_of_deliveries');
    }
};