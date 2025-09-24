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
        Schema::create('supplier_contracts', function (Blueprint $table) {
            $table->id();
            $table->string('contract_number')->unique();
            $table->foreignId('company_id')->constrained()->onDelete('cascade');
            $table->foreignId('buyer_company_id')->constrained('companies')->onDelete('cascade');
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->enum('contract_type', ['standard', 'blanket', 'master_agreement', 'framework'])->default('standard');
            $table->json('sla_details')->nullable(); // Service Level Agreement details
            $table->json('penalties')->nullable(); // Penalty clauses
            $table->json('volume_commitments')->nullable(); // Volume commitments
            $table->json('pricing_rules')->nullable(); // Pricing rules and discounts
            $table->decimal('total_contract_value', 15, 2)->nullable();
            $table->string('currency', 3)->default('USD');
            $table->enum('status', ['draft', 'active', 'expired', 'terminated', 'suspended'])->default('draft');
            $table->text('terms_conditions')->nullable();
            $table->json('renewal_terms')->nullable(); // Auto-renewal terms
            $table->foreignId('contract_manager_id')->nullable()->constrained('users')->onDelete('set null');
            $table->json('signatures')->nullable(); // Digital signatures
            $table->json('attachments')->nullable(); // Contract documents
            $table->json('metadata')->nullable();
            $table->timestamps();
            
            $table->index(['company_id', 'buyer_company_id']);
            $table->index(['start_date', 'end_date']);
            $table->index(['contract_type', 'status']);
            $table->index('contract_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('supplier_contracts');
    }
};