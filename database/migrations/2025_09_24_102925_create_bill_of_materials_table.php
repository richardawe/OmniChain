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
        Schema::create('bill_of_materials', function (Blueprint $table) {
            $table->id();
            $table->string('bom_id')->unique(); // BOM identifier
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->string('version')->default('1.0');
            $table->date('effective_date');
            $table->date('expiry_date')->nullable();
            $table->text('description')->nullable();
            $table->enum('status', ['draft', 'active', 'obsolete'])->default('draft');
            $table->json('bom_metadata')->nullable(); // Additional BOM information
            $table->timestamps();

            // Indexes
            $table->index(['product_id', 'version']);
            $table->index('effective_date');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bill_of_materials');
    }
};