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
            $table->id();
            $table->text('title');
            $table->mediumText('description')->nullable();
            $table->foreignId('project_id')->nullable();
            $table->foreignId('milestone_id')->nullable();
            $table->foreignId('assigned_to')->nullable();
            $table->dateTime('deadline')->nullable();
            $table->text('labels')->nullable();
            $table->tinyInteger('points')->default(1);
            $table->string('status', 32)->default('to_do');
            $table->foreignId('status_id')->nullable();
            $table->foreignId('priority_id')->nullable();
            $table->dateTime('start_date')->nullable();
            $table->text('collaborators');
            $table->integer('sort')->default(0);
            $table->boolean('recurring')->default(false);
            $table->integer('repeat_every')->default(0);
            $table->string('repeat_type', 32)->nullable();
            $table->integer('no_of_cycles')->default(0);
            $table->foreignId('recurring_task_id')->default(0);
            $table->integer('no_of_cycles_completed')->default(0);
            $table->text('blocking');
            $table->text('blocked_by');
            $table->foreignId('parent_task_id')->nullable();
            $table->date('next_recurring_date')->nullable();
            $table->date('reminder_date')->nullable();
            $table->foreignId('ticket_id')->nullable();
            $table->dateTime('status_changed_at')->nullable();
            $table->tinyInteger('deleted')->default(0);
            $table->foreignId('expense_id')->nullable();
            $table->foreignId('subscription_id')->nullable();
            $table->foreignId('proposal_id')->nullable();
            $table->foreignId('contract_id')->nullable();
            $table->foreignId('order_id')->nullable();
            $table->foreignId('estimate_id')->nullable();
            $table->foreignId('invoice_id')->nullable();
            $table->foreignId('lead_id')->nullable();
            $table->foreignId('client_id')->nullable();
            $table->string('context', 16);
            $table->timestamps();
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
