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
        Schema::create('carriers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->onDelete('cascade');
            $table->string('scac_code')->unique(); // Standard Carrier Alpha Code
            $table->string('name');
            $table->string('legal_name')->nullable();
            $table->string('mc_number')->nullable(); // Motor Carrier Number
            $table->string('dot_number')->nullable(); // Department of Transportation Number
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('website')->nullable();
            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('country')->nullable();
            $table->string('postal_code')->nullable();
            $table->json('service_types')->nullable(); // ['ltl', 'ftl', 'air', 'ocean', 'rail', 'parcel']
            $table->json('coverage_areas')->nullable();
            $table->json('equipment_types')->nullable(); // ['dry_van', 'refrigerated', 'flatbed', 'container']
            $table->decimal('rating', 3, 2)->nullable(); // 1.00 to 5.00
            $table->enum('status', ['active', 'inactive', 'suspended'])->default('active');
            $table->json('metadata')->nullable();
            $table->timestamps();
            
            $table->index(['company_id', 'status']);
            $table->index('scac_code');
            $table->index(['mc_number', 'dot_number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('carriers');
    }
};
