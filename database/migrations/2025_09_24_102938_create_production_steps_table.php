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
        Schema::create('production_steps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('route_id')->constrained('production_routes')->onDelete('cascade');
            $table->integer('step_number');
            $table->string('operation_code');
            $table->string('operation_name');
            $table->foreignId('machine_id')->nullable()->constrained('machines')->onDelete('set null');
            $table->foreignId('work_center_id')->nullable()->constrained('locations')->onDelete('set null');
            $table->decimal('standard_time_minutes', 10, 2);
            $table->decimal('setup_time_minutes', 10, 2)->default(0);
            $table->decimal('queue_time_minutes', 10, 2)->default(0);
            $table->decimal('move_time_minutes', 10, 2)->default(0);
            $table->boolean('is_bottleneck')->default(false);
            $table->integer('parallel_capacity')->default(1);
            $table->text('operation_description')->nullable();
            $table->json('required_skills')->nullable(); // Array of required skill codes
            $table->json('tool_requirements')->nullable(); // Array of required tools
            $table->json('quality_checkpoints')->nullable(); // Array of quality check requirements
            $table->json('step_metadata')->nullable(); // Additional step information
            $table->timestamps();

            // Indexes
            $table->index(['route_id', 'step_number']);
            $table->index('operation_code');
            $table->index('machine_id');
            $table->unique(['route_id', 'step_number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('production_steps');
    }
};