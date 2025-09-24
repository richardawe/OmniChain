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
        Schema::table('companies', function (Blueprint $table) {
            // Master Data Fields from CompanyMaster
            $table->string('duns_number')->nullable()->after('tax_id');
            $table->string('lei_number')->nullable()->after('duns_number');
            $table->string('vat_number')->nullable()->after('lei_number');
            $table->string('trade_name')->nullable()->after('name');
            $table->string('business_type')->nullable()->after('type'); // Manufacturer/Distributor/3PL/Retailer
            $table->string('currency', 3)->default('USD')->after('country');
            $table->string('timezone')->nullable()->after('currency');
            $table->string('industry_classification')->nullable()->after('timezone'); // NAICS/SIC
            $table->json('certifications')->nullable()->after('industry_classification'); // ISO, GMP, HACCP
            $table->string('payment_terms_code')->nullable()->after('certifications');
            $table->decimal('credit_limit', 15, 2)->nullable()->after('payment_terms_code');
            $table->uuid('primary_contact_id')->nullable()->after('credit_limit');
            $table->json('addresses')->nullable()->after('primary_contact_id'); // List of address objects
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn([
                'duns_number',
                'lei_number',
                'vat_number',
                'trade_name',
                'business_type',
                'currency',
                'timezone',
                'industry_classification',
                'certifications',
                'payment_terms_code',
                'credit_limit',
                'primary_contact_id',
                'addresses'
            ]);
        });
    }
};