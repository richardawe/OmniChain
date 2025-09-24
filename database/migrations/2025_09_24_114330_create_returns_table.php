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
        Schema::create('returns', function (Blueprint $table) {
            $table->id();
            $table->string('return_number')->unique();
            $table->string('original_order_number')->nullable();
            $table->foreignId('customer_id')->constrained('companies')->onDelete('cascade');
            $table->foreignId('location_id')->constrained('locations')->onDelete('cascade');
            $table->foreignId('return_authorization_id')->nullable()->constrained('return_authorizations')->onDelete('set null');
            $table->enum('return_type', ['customer_return', 'vendor_return', 'damage_return', 'recall_return', 'exchange'])->default('customer_return');
            $table->enum('status', ['requested', 'authorized', 'received', 'processing', 'completed', 'rejected', 'cancelled'])->default('requested');
            $table->enum('priority', ['low', 'medium', 'high', 'urgent'])->default('medium');
            $table->datetime('request_date');
            $table->datetime('authorized_date')->nullable();
            $table->datetime('received_date')->nullable();
            $table->datetime('completed_date')->nullable();
            $table->decimal('total_value', 10, 2)->nullable();
            $table->decimal('refund_amount', 10, 2)->nullable();
            $table->enum('refund_method', ['credit_card', 'bank_transfer', 'store_credit', 'check', 'cash'])->nullable();
            $table->string('tracking_number')->nullable();
            $table->string('carrier')->nullable();
            $table->text('return_reason')->nullable();
            $table->text('customer_notes')->nullable();
            $table->text('internal_notes')->nullable();
            $table->boolean('requires_inspection')->default(true);
            $table->boolean('customer_notified')->default(false);
            $table->json('return_metadata')->nullable();
            $table->timestamps();

            // Indexes
            $table->index(['customer_id', 'status']);
            $table->index(['location_id', 'status']);
            $table->index('request_date');
            $table->index('return_type');
            $table->index('priority');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('returns');
    }
};