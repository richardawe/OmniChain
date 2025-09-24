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
        Schema::create('delivery_exceptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('delivery_task_id')->constrained('delivery_tasks')->onDelete('cascade');
            $table->foreignId('freight_order_id')->nullable()->constrained('freight_orders')->onDelete('cascade');
            $table->foreignId('driver_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('exception_code'); // DAMAGED, REFUSED, NO_ACCESS, WEATHER, etc.
            $table->string('exception_type'); // delivery_failed, delivery_delayed, customer_issue, operational_issue
            $table->enum('severity', ['low', 'medium', 'high', 'critical'])->default('medium');
            $table->enum('status', ['open', 'in_progress', 'resolved', 'cancelled'])->default('open');
            $table->datetime('exception_timestamp');
            $table->decimal('exception_latitude', 10, 8)->nullable();
            $table->decimal('exception_longitude', 11, 8)->nullable();
            $table->string('exception_location')->nullable();
            $table->text('description');
            $table->text('root_cause')->nullable();
            $table->text('resolution_notes')->nullable();
            $table->text('customer_communication')->nullable();
            $table->json('photos_attached')->nullable(); // Array of photo file paths
            $table->json('required_actions')->nullable(); // Array of actions needed to resolve
            $table->datetime('resolved_at')->nullable();
            $table->foreignId('resolved_by_user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->decimal('estimated_resolution_time_hours', 8, 2)->nullable();
            $table->decimal('actual_resolution_time_hours', 8, 2)->nullable();
            $table->json('exception_metadata')->nullable(); // Additional exception information
            $table->timestamps();
            
            $table->index(['delivery_task_id', 'status']);
            $table->index(['freight_order_id', 'exception_type']);
            $table->index(['driver_id', 'exception_timestamp']);
            $table->index(['exception_code', 'severity']);
            $table->index(['status', 'exception_timestamp']);
            $table->index('exception_timestamp');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('delivery_exceptions');
    }
};