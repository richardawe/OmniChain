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
        Schema::create('shifts', function (Blueprint $table) {
            $table->id();
            $table->string('shift_name');
            $table->time('start_time');
            $table->time('end_time');
            $table->integer('duration_hours');
            $table->enum('shift_type', ['day', 'night', 'swing', 'overtime'])->default('day');
            $table->boolean('is_active')->default(true);
            $table->json('shift_metadata')->nullable(); // Additional shift information
            $table->timestamps();

            // Indexes
            $table->index(['start_time', 'end_time']);
            $table->index('shift_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shifts');
    }
};