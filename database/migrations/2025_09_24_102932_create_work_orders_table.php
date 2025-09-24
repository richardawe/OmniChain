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
        Schema::create('work_orders', function (Blueprint $table) {
            $table->id();
            $table->string('work_order_id')->unique();
            $table->foreignId('production_line_id')->constrained('locations')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->foreignId('bom_id')->nullable()->constrained('bill_of_materials')->onDelete('set null');
            $table->foreignId('route_id')->nullable()->constrained('production_routes')->onDelete('set null');
            $table->integer('quantity_planned');
            $table->integer('quantity_produced')->default(0);
            $table->integer('quantity_scrapped')->default(0);
            $table->datetime('planned_start_time');
            $table->datetime('planned_end_time');
            $table->datetime('actual_start_time')->nullable();
            $table->datetime('actual_end_time')->nullable();
            $table->foreignId('shift_id')->nullable()->constrained('shifts')->onDelete('set null');
            $table->enum('status', ['planned', 'released', 'in_progress', 'completed', 'cancelled', 'on_hold'])->default('planned');
            $table->integer('priority')->default(1); // 1 = highest priority
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->foreignId('assigned_supervisor')->nullable()->constrained('users')->onDelete('set null');
            $table->json('operator_ids')->nullable(); // Array of operator user IDs
            $table->json('associated_batch_numbers')->nullable(); // Array of batch numbers
            $table->text('work_instructions')->nullable();
            $table->text('special_requirements')->nullable();
            $table->json('work_order_metadata')->nullable(); // Additional work order information
            $table->timestamps();

            // Indexes
            $table->index(['production_line_id', 'status']);
            $table->index(['product_id', 'status']);
            $table->index('planned_start_time');
            $table->index('status');
            $table->index('priority');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('work_orders');
    }
};