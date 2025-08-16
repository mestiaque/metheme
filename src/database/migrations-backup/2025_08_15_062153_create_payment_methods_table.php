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
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->integer('id', true);
            $table->text('title');
            $table->string('type', 100)->default('custom');
            $table->text('description');
            $table->boolean('online_payable')->default(false);
            $table->boolean('available_on_invoice')->default(false);
            $table->double('minimum_payment_amount')->default(0);
            $table->longText('settings');
            $table->integer('sort')->default(0);
            $table->boolean('deleted')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_methods');
    }
};
