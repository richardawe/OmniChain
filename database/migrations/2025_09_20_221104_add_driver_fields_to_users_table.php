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
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone')->nullable()->after('email');
            $table->string('driver_license')->nullable()->after('phone');
            $table->enum('vehicle_type', ['van', 'truck', 'car', 'motorcycle'])->nullable()->after('driver_license');
            $table->decimal('max_capacity', 8, 2)->nullable()->after('vehicle_type');
            $table->enum('status', ['available', 'busy', 'offline', 'pending_approval'])->default('offline')->after('max_capacity');
            $table->json('last_location')->nullable()->after('status');
            $table->timestamp('last_location_updated_at')->nullable()->after('last_location');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'phone',
                'driver_license',
                'vehicle_type',
                'max_capacity',
                'status',
                'last_location',
                'last_location_updated_at'
            ]);
        });
    }
};