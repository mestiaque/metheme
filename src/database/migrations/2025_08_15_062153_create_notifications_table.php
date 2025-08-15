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
        Schema::create('notifications', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('user_id')->index('user_id');
            $table->longText('description');
            $table->dateTime('created_at');
            $table->mediumText('notify_to');
            $table->mediumText('read_by');
            $table->string('event', 250);
            $table->integer('project_id');
            $table->integer('task_id');
            $table->integer('project_comment_id');
            $table->integer('ticket_id');
            $table->integer('ticket_comment_id');
            $table->integer('project_file_id');
            $table->integer('leave_id');
            $table->integer('post_id');
            $table->integer('to_user_id');
            $table->integer('activity_log_id');
            $table->integer('client_id');
            $table->integer('lead_id');
            $table->integer('invoice_payment_id');
            $table->integer('invoice_id');
            $table->integer('estimate_id');
            $table->integer('contract_id');
            $table->integer('order_id');
            $table->integer('estimate_request_id');
            $table->integer('actual_message_id');
            $table->integer('parent_message_id');
            $table->integer('event_id');
            $table->integer('announcement_id');
            $table->integer('proposal_id');
            $table->integer('estimate_comment_id');
            $table->integer('subscription_id');
            $table->integer('expense_id');
            $table->integer('deleted')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
