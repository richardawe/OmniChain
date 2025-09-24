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
        Schema::table('locations', function (Blueprint $table) {
            // Master Data Fields from LocationMaster
            $table->decimal('capacity_volume', 10, 3)->nullable()->after('longitude'); // in cubic meters
            $table->decimal('capacity_weight', 10, 3)->nullable()->after('capacity_volume'); // in kg
            $table->json('hazardous_material_capabilities')->nullable()->after('capabilities');
            $table->json('temperature_control')->nullable()->after('hazardous_material_capabilities'); // min/max temp
            $table->integer('loading_bays_count')->nullable()->after('temperature_control');
            $table->json('dock_types')->nullable()->after('loading_bays_count');
            $table->text('accessibility_notes')->nullable()->after('dock_types');
            
            // Add indexes for performance
            $table->index(['latitude', 'longitude'], 'idx_locations_geo');
            $table->index('capacity_volume', 'idx_locations_capacity_volume');
            $table->index('capacity_weight', 'idx_locations_capacity_weight');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('locations', function (Blueprint $table) {
            $table->dropIndex('idx_locations_geo');
            $table->dropIndex('idx_locations_capacity_volume');
            $table->dropIndex('idx_locations_capacity_weight');
            
            $table->dropColumn([
                'capacity_volume',
                'capacity_weight',
                'hazardous_material_capabilities',
                'temperature_control',
                'loading_bays_count',
                'dock_types',
                'accessibility_notes'
            ]);
        });
    }
};