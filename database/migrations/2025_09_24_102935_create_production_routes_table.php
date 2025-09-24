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
        Schema::create('production_routes', function (Blueprint $table) {
            $table->id();
            $table->string('route_id')->unique();
            $table->string('route_name');
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->text('description')->nullable();
            $table->decimal('total_standard_time', 10, 2)->nullable(); // Total time in minutes
            $table->decimal('total_setup_time', 10, 2)->nullable(); // Total setup time in minutes
            $table->enum('status', ['draft', 'active', 'obsolete'])->default('draft');
            $table->date('effective_date');
            $table->date('expiry_date')->nullable();
            $table->json('route_metadata')->nullable(); // Additional route information
            $table->timestamps();

            // Indexes
            $table->index('product_id');
            $table->index('status');
            $table->index('effective_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('production_routes');
    }
};