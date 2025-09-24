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
        Schema::create('customs_documentations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('freight_order_id')->constrained('freight_orders')->onDelete('cascade');
            $table->string('customs_declaration_number')->nullable();
            $table->string('bill_of_lading_number')->nullable();
            $table->string('commercial_invoice_number')->nullable();
            $table->string('packing_list_number')->nullable();
            $table->string('certificate_of_origin_number')->nullable();
            $table->string('export_license_number')->nullable();
            $table->string('import_license_number')->nullable();
            $table->string('exporting_country')->nullable();
            $table->string('importing_country')->nullable();
            $table->string('export_port')->nullable();
            $table->string('import_port')->nullable();
            $table->enum('shipment_type', ['export', 'import', 'transit', 'transshipment'])->default('import');
            $table->decimal('total_customs_value', 15, 2)->nullable();
            $table->string('currency_code', 3)->default('USD');
            $table->json('hs_codes')->nullable(); // Harmonized System codes
            $table->json('duty_rates')->nullable(); // Applicable duty rates
            $table->decimal('total_duty_amount', 15, 2)->nullable();
            $table->decimal('total_tax_amount', 15, 2)->nullable();
            $table->json('restricted_items')->nullable(); // List of restricted/prohibited items
            $table->json('required_certifications')->nullable(); // Required certifications
            $table->enum('customs_status', ['pending', 'submitted', 'under_review', 'approved', 'rejected', 'held', 'released'])->default('pending');
            $table->datetime('submission_date')->nullable();
            $table->datetime('approval_date')->nullable();
            $table->datetime('release_date')->nullable();
            $table->text('customs_notes')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->string('customs_broker')->nullable();
            $table->string('customs_officer')->nullable();
            $table->json('customs_metadata')->nullable(); // Additional customs information
            $table->timestamps();
            
            $table->index(['freight_order_id', 'customs_status']);
            $table->index(['exporting_country', 'importing_country']);
            $table->index('customs_declaration_number');
            $table->index('bill_of_lading_number');
            $table->index('customs_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customs_documentations');
    }
};