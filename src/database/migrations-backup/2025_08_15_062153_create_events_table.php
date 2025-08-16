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
        Schema::create('events', function (Blueprint $table) {
            $table->integer('id', true);
            $table->text('title');
            $table->mediumText('description');
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->integer('created_by')->index('created_by');
            $table->mediumText('location')->nullable();
            $table->integer('client_id')->default(0);
            $table->text('labels');
            $table->mediumText('share_with')->nullable();
            $table->boolean('editable_google_event')->default(false);
            $table->text('google_event_id');
            $table->integer('deleted')->default(0);
            $table->integer('lead_id')->default(0);
            $table->integer('ticket_id')->default(0);
            $table->integer('project_id')->default(0);
            $table->integer('task_id')->default(0);
            $table->integer('proposal_id')->default(0);
            $table->integer('contract_id')->default(0);
            $table->integer('subscription_id')->default(0);
            $table->integer('invoice_id')->default(0);
            $table->integer('order_id')->default(0);
            $table->integer('estimate_id')->default(0);
            $table->dateTime('next_recurring_time')->nullable();
            $table->integer('no_of_cycles_completed')->default(0);
            $table->dateTime('snoozing_time')->nullable();
            $table->enum('reminder_status', ['new', 'shown', 'done'])->default('new');
            $table->enum('type', ['event', 'reminder'])->default('event');
            $table->string('color', 15);
            $table->integer('recurring')->default(0);
            $table->integer('repeat_every')->default(0);
            $table->enum('repeat_type', ['days', 'weeks', 'months', 'years'])->nullable();
            $table->integer('no_of_cycles')->default(0);
            $table->date('last_start_date')->nullable();
            $table->longText('recurring_dates');
            $table->text('confirmed_by');
            $table->text('rejected_by');
            $table->text('files');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
