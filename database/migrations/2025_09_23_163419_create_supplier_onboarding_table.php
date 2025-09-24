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
        Schema::create('supplier_onboarding', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->onDelete('cascade');
            $table->foreignId('buyer_company_id')->constrained('companies')->onDelete('cascade');
            $table->date('onboarding_date');
            $table->enum('status', ['pending', 'in_progress', 'approved', 'rejected', 'suspended'])->default('pending');
            $table->json('kyc_documents')->nullable(); // Links to KYC documents
            $table->json('compliance_certs')->nullable(); // Compliance certifications
            $table->json('approved_items')->nullable(); // List of approved ProductIDs
            $table->integer('lead_times_days')->nullable(); // Standard lead time
            $table->decimal('minimum_order_quantity', 10, 2)->nullable();
            $table->json('supplier_performance_scores')->nullable(); // Performance metrics
            $table->text('onboarding_notes')->nullable();
            $table->foreignId('assigned_to_user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('rejected_at')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
            
            $table->index(['buyer_company_id', 'status']);
            $table->index(['company_id', 'buyer_company_id']);
            $table->index('onboarding_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('supplier_onboarding');
    }
};