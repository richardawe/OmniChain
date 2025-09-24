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
        Schema::table('freight_orders', function (Blueprint $table) {
            $table->foreignId('assigned_driver_id')->nullable()->after('carrier_company_id')->constrained('users')->onDelete('set null');
            $table->time('requested_pickup_time')->nullable()->after('requested_delivery_date');
            $table->time('requested_delivery_time')->nullable()->after('requested_pickup_time');
            $table->text('cargo_description')->nullable()->after('declared_value');
            $table->text('pickup_instructions')->nullable()->after('cargo_description');
            $table->text('delivery_instructions')->nullable()->after('pickup_instructions');
            $table->string('delivery_photo_path')->nullable()->after('delivery_instructions');
            $table->string('signature_path')->nullable()->after('delivery_photo_path');
            $table->text('delivery_notes')->nullable()->after('signature_path');
            $table->string('recipient_name')->nullable()->after('delivery_notes');
            $table->string('recipient_phone')->nullable()->after('recipient_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('freight_orders', function (Blueprint $table) {
            $table->dropForeign(['assigned_driver_id']);
            $table->dropColumn([
                'assigned_driver_id',
                'requested_pickup_time',
                'requested_delivery_time',
                'cargo_description',
                'pickup_instructions',
                'delivery_instructions',
                'delivery_photo_path',
                'signature_path',
                'delivery_notes',
                'recipient_name',
                'recipient_phone'
            ]);
        });
    }
};