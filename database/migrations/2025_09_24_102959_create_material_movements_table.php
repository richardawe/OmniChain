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
        Schema::create('material_movements', function (Blueprint $table) {
            $table->id();
            $table->string('movement_id')->unique();
            $table->foreignId('from_location_id')->constrained('locations')->onDelete('cascade');
            $table->foreignId('to_location_id')->constrained('locations')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->string('batch_id')->nullable(); // Link to batch tracking
            $table->decimal('quantity_moved', 10, 3);
            $table->string('unit_of_measure');
            $table->datetime('movement_timestamp');
            $table->foreignId('responsible_employee_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('work_order_id')->nullable()->constrained('work_orders')->onDelete('set null');
            $table->enum('movement_type', ['production', 'transfer', 'consumption', 'return', 'adjustment'])->default('transfer');
            $table->text('movement_reason')->nullable();
            $table->json('movement_metadata')->nullable(); // Additional movement information
            $table->timestamps();

            // Indexes
            $table->index(['from_location_id', 'movement_timestamp']);
            $table->index(['to_location_id', 'movement_timestamp']);
            $table->index(['product_id', 'movement_timestamp']);
            $table->index('work_order_id');
            $table->index('movement_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('material_movements');
    }
};