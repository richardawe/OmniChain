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
        Schema::create('customer_notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_fulfillment_id')->nullable()->constrained('order_fulfillments')->onDelete('cascade');
            $table->foreignId('delivery_task_id')->nullable()->constrained('delivery_tasks')->onDelete('cascade');
            $table->foreignId('freight_order_id')->nullable()->constrained('freight_orders')->onDelete('cascade');
            $table->foreignId('customer_company_id')->constrained('companies')->onDelete('cascade');
            $table->string('notification_type'); // order_confirmed, shipped, out_for_delivery, delivered, delayed, etc.
            $table->enum('channel', ['email', 'sms', 'push', 'webhook', 'phone_call'])->default('email');
            $table->string('recipient_email')->nullable();
            $table->string('recipient_phone')->nullable();
            $table->string('recipient_name')->nullable();
            $table->string('subject')->nullable();
            $table->text('message_content');
            $table->json('template_variables')->nullable(); // Variables used in message templates
            $table->enum('status', ['pending', 'sent', 'delivered', 'failed', 'bounced', 'opened', 'clicked'])->default('pending');
            $table->datetime('scheduled_at')->nullable();
            $table->datetime('sent_at')->nullable();
            $table->datetime('delivered_at')->nullable();
            $table->datetime('opened_at')->nullable();
            $table->datetime('clicked_at')->nullable();
            $table->text('failure_reason')->nullable();
            $table->string('external_message_id')->nullable(); // ID from email/SMS provider
            $table->integer('retry_count')->default(0);
            $table->datetime('next_retry_at')->nullable();
            $table->json('notification_metadata')->nullable(); // Additional notification information
            $table->timestamps();
            
            $table->index(['customer_company_id', 'status']);
            $table->index(['notification_type', 'channel']);
            $table->index(['status', 'scheduled_at']);
            $table->index(['order_fulfillment_id', 'notification_type']);
            $table->index(['delivery_task_id', 'status']);
            $table->index(['freight_order_id', 'notification_type']);
            $table->index('external_message_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_notifications');
    }
};