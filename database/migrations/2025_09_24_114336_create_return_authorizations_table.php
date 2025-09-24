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
        Schema::create('return_authorizations', function (Blueprint $table) {
            $table->id();
            $table->string('authorization_number')->unique();
            $table->foreignId('customer_id')->constrained('companies')->onDelete('cascade');
            $table->foreignId('location_id')->constrained('locations')->onDelete('cascade');
            $table->string('original_order_number')->nullable();
            $table->enum('authorization_type', ['return', 'exchange', 'warranty', 'defective', 'recall'])->default('return');
            $table->enum('status', ['pending', 'approved', 'rejected', 'expired', 'cancelled'])->default('pending');
            $table->datetime('request_date');
            $table->datetime('authorized_date')->nullable();
            $table->datetime('expiry_date')->nullable();
            $table->foreignId('authorized_by')->nullable()->constrained('users')->onDelete('set null');
            $table->decimal('authorized_amount', 10, 2)->nullable();
            $table->enum('refund_method', ['credit_card', 'bank_transfer', 'store_credit', 'check', 'cash'])->nullable();
            $table->integer('valid_days')->default(30);
            $table->text('authorization_notes')->nullable();
            $table->text('terms_conditions')->nullable();
            $table->boolean('requires_approval')->default(true);
            $table->boolean('auto_approve')->default(false);
            $table->json('authorization_metadata')->nullable();
            $table->timestamps();

            // Indexes
            $table->index(['customer_id', 'status']);
            $table->index(['location_id', 'status']);
            $table->index('authorization_type');
            $table->index('expiry_date');
            $table->index('authorized_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('return_authorizations');
    }
};