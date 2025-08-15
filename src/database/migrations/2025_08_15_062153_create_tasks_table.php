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
        Schema::create('tasks', function (Blueprint $table) {
            $table->integer('id', true);
            $table->text('title');
            $table->mediumText('description')->nullable();
            $table->integer('project_id');
            $table->integer('milestone_id')->default(0);
            $table->integer('assigned_to');
            $table->dateTime('deadline')->nullable();
            $table->text('labels')->nullable();
            $table->tinyInteger('points')->default(1);
            $table->enum('status', ['to_do', 'in_progress', 'done'])->default('to_do');
            $table->integer('status_id');
            $table->integer('priority_id');
            $table->dateTime('start_date')->nullable();
            $table->text('collaborators');
            $table->integer('sort')->default(0);
            $table->boolean('recurring')->default(false);
            $table->integer('repeat_every')->default(0);
            $table->enum('repeat_type', ['days', 'weeks', 'months', 'years'])->nullable();
            $table->integer('no_of_cycles')->default(0);
            $table->integer('recurring_task_id')->default(0);
            $table->integer('no_of_cycles_completed')->default(0);
            $table->date('created_date')->nullable();
            $table->text('blocking');
            $table->text('blocked_by');
            $table->integer('parent_task_id');
            $table->date('next_recurring_date')->nullable();
            $table->date('reminder_date')->nullable();
            $table->integer('ticket_id');
            $table->dateTime('status_changed_at')->nullable();
            $table->tinyInteger('deleted')->default(0);
            $table->integer('expense_id')->default(0);
            $table->integer('subscription_id')->default(0);
            $table->integer('proposal_id')->default(0);
            $table->integer('contract_id')->default(0);
            $table->integer('order_id')->default(0);
            $table->integer('estimate_id')->default(0);
            $table->integer('invoice_id')->default(0);
            $table->integer('lead_id')->default(0);
            $table->integer('client_id')->default(0);
            $table->enum('context', ['project', 'client', 'lead', 'invoice', 'estimate', 'order', 'contract', 'proposal', 'subscription', 'ticket', 'expense'])->default('project');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
