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
        Schema::create('purchase_orders', function (Blueprint $table) {
            $table->id();
            $table->string('po_number')->unique();
            $table->enum('po_type', ['standard', 'blanket', 'consignment'])->default('standard');
            $table->foreignId('created_by_user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('buyer_company_id')->constrained('companies')->onDelete('cascade');
            $table->foreignId('supplier_company_id')->constrained('companies')->onDelete('cascade');
            $table->date('order_date');
            $table->date('required_delivery_date')->nullable();
            $table->string('currency', 3)->default('USD');
            $table->decimal('total_amount', 15, 2)->nullable();
            $table->decimal('tax_amount', 15, 2)->nullable();
            $table->decimal('discount_amount', 15, 2)->nullable();
            $table->string('incoterms')->nullable();
            $table->string('payment_terms')->nullable();
            $table->enum('status', ['draft', 'open', 'confirmed', 'partially_received', 'closed', 'cancelled'])->default('draft');
            $table->text('special_instructions')->nullable();
            $table->json('delivery_requirements')->nullable();
            $table->json('attachments')->nullable(); // PO attachments
            $table->foreignId('approved_by_user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamp('closed_at')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
            
            $table->index(['buyer_company_id', 'supplier_company_id']);
            $table->index(['order_date', 'required_delivery_date']);
            $table->index(['status', 'po_type']);
            $table->index('po_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_orders');
    }
};