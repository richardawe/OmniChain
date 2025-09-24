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
        Schema::create('return_line_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('return_id')->constrained('returns')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->string('original_line_item_id')->nullable();
            $table->integer('quantity_returned');
            $table->integer('quantity_received')->default(0);
            $table->integer('quantity_damaged')->default(0);
            $table->decimal('unit_price', 10, 2);
            $table->decimal('total_value', 10, 2);
            $table->decimal('refund_amount', 10, 2)->nullable();
            $table->enum('condition', ['new', 'like_new', 'good', 'fair', 'poor', 'damaged'])->default('good');
            $table->enum('disposition', ['refund', 'exchange', 'repair', 'dispose', 'donate', 'resell'])->nullable();
            $table->text('condition_notes')->nullable();
            $table->text('inspection_notes')->nullable();
            $table->boolean('requires_approval')->default(false);
            $table->boolean('approved')->default(false);
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->datetime('approved_at')->nullable();
            $table->json('line_item_metadata')->nullable();
            $table->timestamps();

            // Indexes
            $table->index(['return_id', 'product_id']);
            $table->index('condition');
            $table->index('disposition');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('return_line_items');
    }
};