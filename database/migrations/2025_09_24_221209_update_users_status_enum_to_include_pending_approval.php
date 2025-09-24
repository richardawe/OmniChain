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
        // For SQLite, we need to recreate the table with the new enum values
        // This is a limitation of SQLite - it doesn't support ALTER COLUMN for enums
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('status');
        });
        
        Schema::table('users', function (Blueprint $table) {
            $table->enum('status', ['available', 'busy', 'offline', 'pending_approval'])->default('offline')->after('max_capacity');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('status');
        });
        
        Schema::table('users', function (Blueprint $table) {
            $table->enum('status', ['available', 'busy', 'offline'])->default('offline')->after('max_capacity');
        });
    }
};
