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
        Schema::create('return_dispositions', function (Blueprint $table) {
            $table->id();
            $table->string('disposition_code')->unique();
            $table->string('disposition_name');
            $table->text('description')->nullable();
            $table->enum('disposition_type', ['refund', 'exchange', 'repair', 'dispose', 'donate', 'resell', 'return_to_vendor', 'destroy'])->default('refund');
            $table->boolean('requires_inspection')->default(true);
            $table->boolean('requires_approval')->default(false);
            $table->decimal('cost_per_item', 10, 2)->default(0.00);
            $table->integer('processing_days')->default(1);
            $table->boolean('generates_credit')->default(true);
            $table->boolean('generates_replacement')->default(false);
            $table->boolean('affects_inventory')->default(true);
            $table->enum('inventory_action', ['add', 'subtract', 'none'])->default('add');
            $table->text('processing_instructions')->nullable();
            $table->text('quality_requirements')->nullable();
            $table->boolean('is_active')->default(true);
            $table->text('notes')->nullable();
            $table->json('disposition_metadata')->nullable();
            $table->timestamps();

            // Indexes
            $table->index('disposition_type');
            $table->index('is_active');
            $table->index('requires_inspection');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('return_dispositions');
    }
};