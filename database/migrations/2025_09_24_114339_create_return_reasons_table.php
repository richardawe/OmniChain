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
        Schema::create('return_reasons', function (Blueprint $table) {
            $table->id();
            $table->string('reason_code')->unique();
            $table->string('reason_name');
            $table->text('description')->nullable();
            $table->enum('category', ['defective', 'wrong_item', 'not_as_described', 'damaged_shipping', 'customer_error', 'warranty', 'recall', 'other'])->default('other');
            $table->boolean('requires_approval')->default(false);
            $table->boolean('auto_approve')->default(false);
            $table->integer('approval_level')->default(1);
            $table->decimal('max_refund_percentage', 5, 2)->default(100.00);
            $table->boolean('charge_restocking_fee')->default(false);
            $table->decimal('restocking_fee_percentage', 5, 2)->default(0.00);
            $table->boolean('requires_return_shipping')->default(true);
            $table->boolean('requires_inspection')->default(true);
            $table->integer('valid_days')->default(30);
            $table->boolean('is_active')->default(true);
            $table->text('notes')->nullable();
            $table->json('reason_metadata')->nullable();
            $table->timestamps();

            // Indexes
            $table->index('category');
            $table->index('is_active');
            $table->index('requires_approval');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('return_reasons');
    }
};