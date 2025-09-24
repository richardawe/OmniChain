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
        Schema::create('return_processing', function (Blueprint $table) {
            $table->id();
            $table->foreignId('return_id')->constrained('returns')->onDelete('cascade');
            $table->foreignId('return_line_item_id')->constrained('return_line_items')->onDelete('cascade');
            $table->foreignId('processor_id')->constrained('users')->onDelete('cascade');
            $table->enum('processing_step', ['received', 'inspected', 'approved', 'disposed', 'refunded', 'exchanged', 'repaired'])->default('received');
            $table->enum('status', ['pending', 'in_progress', 'completed', 'failed', 'cancelled'])->default('pending');
            $table->datetime('started_at');
            $table->datetime('completed_at')->nullable();
            $table->integer('processing_time_minutes')->nullable();
            $table->text('inspection_notes')->nullable();
            $table->text('processing_notes')->nullable();
            $table->json('quality_check')->nullable();
            $table->json('photos')->nullable();
            $table->decimal('refund_amount', 10, 2)->nullable();
            $table->enum('refund_method', ['credit_card', 'bank_transfer', 'store_credit', 'check', 'cash'])->nullable();
            $table->string('replacement_order_number')->nullable();
            $table->string('repair_ticket_number')->nullable();
            $table->text('disposition_notes')->nullable();
            $table->json('processing_metadata')->nullable();
            $table->timestamps();

            // Indexes
            $table->index(['return_id', 'processing_step']);
            $table->index(['processor_id', 'status']);
            $table->index('started_at');
            $table->index('processing_step');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('return_processing');
    }
};