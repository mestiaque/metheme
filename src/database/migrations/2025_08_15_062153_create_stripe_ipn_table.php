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
        Schema::create('stripe_ipn', function (Blueprint $table) {
            $table->integer('id', true);
            $table->text('session_id');
            $table->text('verification_code');
            $table->text('payment_verification_code');
            $table->integer('invoice_id');
            $table->integer('contact_user_id');
            $table->integer('client_id');
            $table->integer('payment_method_id');
            $table->boolean('deleted')->default(false);
            $table->text('setup_intent');
            $table->integer('subscription_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stripe_ipn');
    }
};
