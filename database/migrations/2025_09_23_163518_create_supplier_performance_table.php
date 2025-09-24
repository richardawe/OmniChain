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
        Schema::create('supplier_performance', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->onDelete('cascade');
            $table->foreignId('buyer_company_id')->constrained('companies')->onDelete('cascade');
            $table->date('performance_date');
            $table->enum('period_type', ['monthly', 'quarterly', 'annual'])->default('monthly');
            $table->decimal('on_time_delivery_pct', 5, 2)->nullable(); // Percentage
            $table->decimal('quality_reject_rate', 5, 2)->nullable(); // Percentage
            $table->decimal('fill_rate', 5, 2)->nullable(); // Percentage
            $table->integer('lead_time_variance_days')->nullable(); // Variance from promised lead time
            $table->decimal('cost_performance_score', 3, 1)->nullable(); // 1-10 scale
            $table->decimal('communication_score', 3, 1)->nullable(); // 1-10 scale
            $table->decimal('innovation_score', 3, 1)->nullable(); // 1-10 scale
            $table->decimal('overall_score', 3, 1)->nullable(); // Overall performance score
            $table->json('audit_results')->nullable(); // Audit dates and results
            $table->json('corrective_actions')->nullable(); // Corrective actions taken
            $table->text('performance_notes')->nullable();
            $table->json('kpi_metrics')->nullable(); // Additional KPI metrics
            $table->foreignId('evaluated_by_user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->json('metadata')->nullable();
            $table->timestamps();
            
            $table->index(['company_id', 'buyer_company_id', 'performance_date']);
            $table->index(['period_type', 'performance_date']);
            $table->index('overall_score');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('supplier_performance');
    }
};