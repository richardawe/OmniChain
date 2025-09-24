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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->onDelete('cascade');
            $table->string('sku')->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('category')->nullable();
            $table->string('subcategory')->nullable();
            $table->string('brand')->nullable();
            $table->string('manufacturer')->nullable();
            $table->decimal('weight', 10, 3)->nullable(); // in kg
            $table->decimal('length', 10, 2)->nullable(); // in cm
            $table->decimal('width', 10, 2)->nullable(); // in cm
            $table->decimal('height', 10, 2)->nullable(); // in cm
            $table->decimal('volume', 10, 3)->nullable(); // in cubic meters
            $table->enum('hazardous', ['yes', 'no', 'unknown'])->default('no');
            $table->enum('temperature_controlled', ['yes', 'no', 'unknown'])->default('no');
            $table->json('hazardous_details')->nullable();
            $table->json('temperature_requirements')->nullable();
            $table->json('handling_requirements')->nullable();
            $table->enum('status', ['active', 'inactive', 'discontinued'])->default('active');
            $table->json('metadata')->nullable();
            $table->timestamps();
            
            $table->index(['company_id', 'category']);
            $table->index('sku');
            $table->index(['hazardous', 'temperature_controlled']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
