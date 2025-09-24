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
        Schema::create('carrier_rates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('carrier_company_id')->constrained('companies')->onDelete('cascade');
            $table->string('origin_city');
            $table->string('origin_state');
            $table->string('origin_country');
            $table->string('destination_city');
            $table->string('destination_state');
            $table->string('destination_country');
            $table->enum('service_type', ['ltl', 'ftl', 'air', 'ocean', 'rail', 'parcel'])->default('ltl');
            $table->decimal('rate', 10, 2);
            $table->string('currency', 3)->default('USD');
            $table->string('rate_type')->default('per_pound'); // per_pound, per_cubic_foot, per_pallet, flat_rate
            $table->decimal('minimum_charge', 10, 2)->nullable();
            $table->decimal('weight_min', 10, 3)->nullable();
            $table->decimal('weight_max', 10, 3)->nullable();
            $table->integer('transit_days')->nullable();
            $table->timestamp('effective_date');
            $table->timestamp('expiry_date')->nullable();
            $table->enum('status', ['active', 'inactive', 'expired'])->default('active');
            $table->json('accessorials')->nullable(); // fuel surcharge, residential, etc.
            $table->json('restrictions')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
            
            $table->index(['carrier_company_id', 'service_type']);
            $table->index(['origin_city', 'origin_state', 'origin_country']);
            $table->index(['destination_city', 'destination_state', 'destination_country']);
            $table->index(['effective_date', 'expiry_date']);
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('carrier_rates');
    }
};
