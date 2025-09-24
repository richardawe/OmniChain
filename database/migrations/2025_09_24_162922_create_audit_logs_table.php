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
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('action', 50); // created, updated, deleted, login, logout, etc.
            $table->string('table_name', 100)->nullable(); // Table affected
            $table->unsignedBigInteger('record_id')->nullable(); // ID of affected record
            $table->json('old_values')->nullable(); // Previous values
            $table->json('new_values')->nullable(); // New values
            $table->ipAddress('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            $table->string('request_id', 50)->nullable(); // For request tracking
            $table->string('session_id', 100)->nullable();
            $table->string('url', 500)->nullable(); // Request URL
            $table->string('method', 10)->nullable(); // HTTP method
            $table->text('description')->nullable(); // Human readable description
            $table->json('metadata')->nullable(); // Additional context data
            $table->timestamps();
            
            // Indexes for performance
            $table->index(['user_id', 'created_at']);
            $table->index(['action', 'created_at']);
            $table->index(['table_name', 'record_id']);
            $table->index(['ip_address', 'created_at']);
            $table->index('request_id');
            $table->index('session_id');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};