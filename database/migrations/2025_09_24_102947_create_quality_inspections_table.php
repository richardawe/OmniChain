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
        Schema::create('quality_inspections', function (Blueprint $table) {
            $table->id();
            $table->string('inspection_id')->unique();
            $table->foreignId('work_order_id')->nullable()->constrained('work_orders')->onDelete('cascade');
            $table->string('batch_id')->nullable(); // For batch inspections
            $table->foreignId('inspector_id')->constrained('users')->onDelete('cascade');
            $table->datetime('inspection_timestamp');
            $table->enum('inspection_type', ['incoming', 'in_process', 'final', 'random', 'customer_return'])->default('final');
            $table->enum('inspection_result', ['pass', 'fail', 'conditional_pass'])->default('pass');
            $table->integer('sample_size')->default(1);
            $table->integer('defects_found')->default(0);
            $table->decimal('defect_rate_percentage', 5, 2)->default(0);
            $table->text('inspection_notes')->nullable();
            $table->json('measured_attributes')->nullable(); // Array of measured attributes with values
            $table->json('quality_metadata')->nullable(); // Additional quality information
            $table->timestamps();

            // Indexes
            $table->index(['work_order_id', 'inspection_type']);
            $table->index(['batch_id', 'inspection_type']);
            $table->index('inspector_id');
            $table->index('inspection_timestamp');
            $table->index('inspection_result');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quality_inspections');
    }
};