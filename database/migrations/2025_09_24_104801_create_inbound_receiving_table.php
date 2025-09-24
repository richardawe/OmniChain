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
        Schema::create('inbound_receiving', function (Blueprint $table) {
            $table->id();
            $table->string('receiving_id')->unique();
            $table->foreignId('carrier_id')->nullable()->constrained('companies')->onDelete('set null');
            $table->foreignId('location_id')->constrained('locations')->onDelete('cascade');
            $table->string('asn_number')->nullable(); // Advanced Shipping Notice
            $table->string('po_number')->nullable(); // Purchase Order
            $table->string('bill_of_lading')->nullable();
            $table->string('container_number')->nullable();
            $table->datetime('expected_arrival_time');
            $table->datetime('actual_arrival_time')->nullable();
            $table->datetime('unload_start_time')->nullable();
            $table->datetime('unload_complete_time')->nullable();
            $table->enum('status', ['expected', 'arrived', 'unloading', 'unloaded', 'qc_pending', 'qc_completed', 'putaway_pending', 'completed'])->default('expected');
            $table->decimal('total_weight', 10, 3)->nullable();
            $table->decimal('total_volume', 10, 3)->nullable();
            $table->integer('total_packages')->nullable();
            $table->text('carrier_notes')->nullable();
            $table->text('receiving_notes')->nullable();
            $table->json('expected_items')->nullable(); // Array of expected items
            $table->json('received_items')->nullable(); // Array of received items with serials/lots
            $table->json('qc_results')->nullable(); // Quality control results
            $table->json('receiving_metadata')->nullable();
            $table->timestamps();

            // Indexes
            $table->index(['location_id', 'status']);
            $table->index('expected_arrival_time');
            $table->index('asn_number');
            $table->index('po_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inbound_receiving');
    }
};