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
        Schema::create('cycle_counts', function (Blueprint $table) {
            $table->id();
            $table->string('count_id')->unique();
            $table->foreignId('location_id')->constrained('locations')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->string('lot_bin')->nullable();
            $table->decimal('system_quantity', 10, 3)->default(0);
            $table->decimal('counted_quantity', 10, 3)->nullable();
            $table->decimal('discrepancy', 10, 3)->nullable();
            $table->date('count_date');
            $table->foreignId('counter_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('supervisor_id')->nullable()->constrained('users')->onDelete('set null');
            $table->enum('count_status', ['scheduled', 'in_progress', 'completed', 'disputed', 'approved'])->default('scheduled');
            $table->enum('count_type', ['cycle', 'full', 'spot', 'random'])->default('cycle');
            $table->text('audit_comments')->nullable();
            $table->text('discrepancy_reason')->nullable();
            $table->json('count_metadata')->nullable();
            $table->timestamps();

            // Indexes
            $table->index(['location_id', 'count_date']);
            $table->index('count_status');
            $table->index('count_type');
            $table->index('counter_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cycle_counts');
    }
};