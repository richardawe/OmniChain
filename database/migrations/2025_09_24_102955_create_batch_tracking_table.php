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
        Schema::create('batch_tracking', function (Blueprint $table) {
            $table->id();
            $table->string('batch_id')->unique();
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->foreignId('work_order_id')->nullable()->constrained('work_orders')->onDelete('set null');
            $table->decimal('batch_quantity', 10, 3);
            $table->date('production_date');
            $table->date('expiry_date')->nullable();
            $table->enum('quality_status', ['pending', 'passed', 'failed', 'quarantined'])->default('pending');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->json('component_batches')->nullable(); // Array of component batch IDs used
            $table->json('batch_metadata')->nullable(); // Additional batch information
            $table->timestamps();

            // Indexes
            $table->index(['product_id', 'production_date']);
            $table->index('work_order_id');
            $table->index('quality_status');
            $table->index('expiry_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('batch_tracking');
    }
};