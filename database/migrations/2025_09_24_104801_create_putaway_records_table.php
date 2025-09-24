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
        Schema::create('putaway_records', function (Blueprint $table) {
            $table->id();
            $table->string('putaway_id')->unique();
            $table->foreignId('receiving_id')->constrained('inbound_receiving')->onDelete('cascade');
            $table->foreignId('from_location_id')->constrained('locations')->onDelete('cascade');
            $table->foreignId('to_bin_id')->constrained('warehouse_bins')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->string('lot_bin')->nullable();
            $table->decimal('quantity', 10, 3);
            $table->string('unit_of_measure');
            $table->datetime('putaway_timestamp');
            $table->foreignId('putaway_operator_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('supervisor_id')->nullable()->constrained('users')->onDelete('set null');
            $table->enum('status', ['pending', 'in_progress', 'completed', 'cancelled'])->default('pending');
            $table->text('putaway_notes')->nullable();
            $table->json('putaway_metadata')->nullable();
            $table->timestamps();

            // Indexes
            $table->index(['receiving_id', 'status']);
            $table->index(['to_bin_id', 'putaway_timestamp']);
            $table->index('putaway_operator_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('putaway_records');
    }
};