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
        Schema::create('locations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('code')->unique();
            $table->enum('type', ['warehouse', 'distribution_center', 'retail_store', 'manufacturing_plant', 'cross_dock', 'customer_location', 'port', 'airport'])->default('warehouse');
            $table->text('address');
            $table->string('city');
            $table->string('state');
            $table->string('country');
            $table->string('postal_code');
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->string('timezone')->nullable();
            $table->json('operating_hours')->nullable();
            $table->json('capabilities')->nullable(); // loading docks, equipment, etc.
            $table->enum('status', ['active', 'inactive', 'maintenance'])->default('active');
            $table->json('metadata')->nullable();
            $table->timestamps();
            
            $table->index(['company_id', 'type']);
            $table->index(['latitude', 'longitude']);
            $table->index('code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('locations');
    }
};
