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
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->integer('id', true);
            $table->text('title');
            $table->integer('client_id');
            $table->date('bill_date')->nullable();
            $table->date('end_date')->nullable();
            $table->mediumText('note')->nullable();
            $table->text('labels');
            $table->enum('status', ['draft', 'pending', 'active', 'cancelled'])->default('draft');
            $table->enum('payment_status', ['success', 'failed'])->default('success');
            $table->integer('tax_id')->default(0);
            $table->integer('tax_id2')->default(0);
            $table->integer('repeat_every')->default(1);
            $table->enum('repeat_type', ['days', 'weeks', 'months', 'years'])->nullable();
            $table->integer('no_of_cycles')->default(0);
            $table->date('next_recurring_date')->nullable();
            $table->integer('no_of_cycles_completed')->default(0);
            $table->dateTime('cancelled_at')->nullable();
            $table->integer('cancelled_by');
            $table->mediumText('files');
            $table->integer('company_id')->default(0);
            $table->boolean('deleted')->default(false);
            $table->enum('type', ['app', 'stripe'])->default('app');
            $table->text('stripe_subscription_id');
            $table->text('stripe_product_id');
            $table->text('stripe_product_price_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};
