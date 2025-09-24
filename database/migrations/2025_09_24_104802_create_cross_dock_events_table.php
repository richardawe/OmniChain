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
        Schema::create('cross_dock_events', function (Blueprint $table) {
            $table->id();
            $table->string('event_id')->unique();
            $table->foreignId('incoming_shipment_id')->constrained('inbound_receiving')->onDelete('cascade');
            $table->foreignId('outgoing_shipment_id')->constrained('outbound_shipments')->onDelete('cascade');
            $table->foreignId('cross_dock_location_id')->constrained('locations')->onDelete('cascade');
            $table->datetime('transfer_start_time');
            $table->datetime('transfer_complete_time')->nullable();
            $table->decimal('transfer_duration_minutes', 10, 2)->nullable();
            $table->enum('status', ['scheduled', 'in_progress', 'completed', 'delayed', 'cancelled'])->default('scheduled');
            $table->integer('items_transferred')->default(0);
            $table->decimal('weight_transferred', 10, 3)->default(0);
            $table->decimal('volume_transferred', 10, 3)->default(0);
            $table->foreignId('operator_id')->constrained('users')->onDelete('cascade');
            $table->text('transfer_notes')->nullable();
            $table->json('transfer_metadata')->nullable();
            $table->timestamps();

            // Indexes
            $table->index(['cross_dock_location_id', 'status']);
            $table->index('transfer_start_time');
            $table->index(['incoming_shipment_id', 'outgoing_shipment_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cross_dock_events');
    }
};