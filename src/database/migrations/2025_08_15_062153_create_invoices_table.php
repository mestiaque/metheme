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
        Schema::create('invoices', function (Blueprint $table) {
            $table->integer('id', true);
            $table->enum('type', ['invoice', 'credit_note'])->default('invoice');
            $table->integer('client_id');
            $table->integer('project_id')->default(0);
            $table->date('bill_date');
            $table->date('due_date')->index('due_date');
            $table->mediumText('note')->nullable();
            $table->text('labels')->nullable();
            $table->date('last_email_sent_date')->nullable();
            $table->enum('status', ['draft', 'not_paid', 'cancelled', 'credited'])->default('draft')->index('status');
            $table->integer('tax_id')->default(0);
            $table->integer('tax_id2')->default(0);
            $table->integer('tax_id3')->default(0);
            $table->tinyInteger('recurring')->default(0);
            $table->integer('recurring_invoice_id')->default(0);
            $table->integer('repeat_every')->default(0);
            $table->enum('repeat_type', ['days', 'weeks', 'months', 'years'])->nullable();
            $table->integer('no_of_cycles')->default(0);
            $table->date('next_recurring_date')->nullable();
            $table->integer('no_of_cycles_completed')->default(0);
            $table->date('due_reminder_date')->nullable();
            $table->date('recurring_reminder_date')->nullable();
            $table->double('discount_amount');
            $table->enum('discount_amount_type', ['percentage', 'fixed_amount']);
            $table->enum('discount_type', ['before_tax', 'after_tax']);
            $table->dateTime('cancelled_at')->nullable();
            $table->integer('cancelled_by');
            $table->mediumText('files');
            $table->integer('company_id')->default(0);
            $table->integer('estimate_id')->default(0);
            $table->integer('main_invoice_id')->default(0);
            $table->integer('subscription_id')->default(0);
            $table->double('invoice_total');
            $table->double('invoice_subtotal');
            $table->double('discount_total');
            $table->double('tax');
            $table->double('tax2');
            $table->double('tax3');
            $table->boolean('deleted')->default(false);
            $table->integer('order_id')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
