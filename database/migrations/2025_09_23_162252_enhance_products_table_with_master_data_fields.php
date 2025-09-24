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
        Schema::table('products', function (Blueprint $table) {
            // Master Data Fields from ProductMaster
            $table->string('gtin')->nullable()->after('sku'); // Global Trade Item Number
            $table->string('upc')->nullable()->after('gtin'); // Universal Product Code
            $table->string('ean')->nullable()->after('upc'); // European Article Number
            $table->string('product_family')->nullable()->after('subcategory');
            $table->string('unit_of_measure')->nullable()->after('manufacturer'); // base unit
            $table->decimal('net_weight', 10, 3)->nullable()->after('weight'); // separate from gross weight
            $table->string('hazardous_material_code')->nullable()->after('hazardous_details'); // HS/UN/IMDG class
            $table->json('storage_requirements')->nullable()->after('temperature_requirements'); // temp, humidity
            $table->integer('shelf_life_days')->nullable()->after('storage_requirements');
            $table->boolean('batch_trackable')->default(false)->after('shelf_life_days');
            $table->boolean('serialization_capable')->default(false)->after('batch_trackable');
            $table->json('bom_references')->nullable()->after('serialization_capable'); // Components/BOM references
            $table->string('country_of_origin', 3)->nullable()->after('bom_references');
            $table->json('tariff_codes')->nullable()->after('country_of_origin');
            $table->string('lifecycle_status')->default('active')->after('tariff_codes'); // active/inactive/discontinued/obsolete
            $table->string('manufacturer_part_number')->nullable()->after('lifecycle_status');
            
            // Add indexes for performance
            $table->index('gtin', 'idx_products_gtin');
            $table->index('upc', 'idx_products_upc');
            $table->index('ean', 'idx_products_ean');
            $table->index('batch_trackable', 'idx_products_batch_trackable');
            $table->index('serialization_capable', 'idx_products_serialization_capable');
            $table->index('lifecycle_status', 'idx_products_lifecycle_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex('idx_products_gtin');
            $table->dropIndex('idx_products_upc');
            $table->dropIndex('idx_products_ean');
            $table->dropIndex('idx_products_batch_trackable');
            $table->dropIndex('idx_products_serialization_capable');
            $table->dropIndex('idx_products_lifecycle_status');
            
            $table->dropColumn([
                'gtin',
                'upc',
                'ean',
                'product_family',
                'unit_of_measure',
                'net_weight',
                'hazardous_material_code',
                'storage_requirements',
                'shelf_life_days',
                'batch_trackable',
                'serialization_capable',
                'bom_references',
                'country_of_origin',
                'tariff_codes',
                'lifecycle_status',
                'manufacturer_part_number'
            ]);
        });
    }
};