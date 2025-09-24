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
        Schema::create('bom_components', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bom_id')->constrained('bill_of_materials')->onDelete('cascade');
            $table->foreignId('component_product_id')->constrained('products')->onDelete('cascade');
            $table->integer('line_number');
            $table->decimal('quantity_required', 10, 4);
            $table->string('unit_of_measure');
            $table->foreignId('sub_bom_id')->nullable()->constrained('bill_of_materials')->onDelete('set null');
            $table->boolean('is_phantom')->default(false); // Phantom BOM for grouping
            $table->decimal('scrap_factor', 5, 4)->default(0); // Scrap percentage
            $table->text('notes')->nullable();
            $table->json('component_metadata')->nullable(); // Additional component information
            $table->timestamps();

            // Indexes
            $table->index(['bom_id', 'line_number']);
            $table->index('component_product_id');
            $table->unique(['bom_id', 'component_product_id', 'line_number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bom_components');
    }
};