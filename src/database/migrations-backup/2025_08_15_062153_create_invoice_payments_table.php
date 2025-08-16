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
        Schema::create('invoice_payments', function (Blueprint $table) {
            $table->integer('id', true)->index('id');
            $table->double('amount');
            $table->date('payment_date');
            $table->integer('payment_method_id');
            $table->text('note')->nullable();
            $table->integer('invoice_id');
            $table->boolean('deleted')->default(false);
            $table->tinyText('transaction_id')->nullable();
            $table->integer('created_by')->nullable()->default(1);
            $table->dateTime('created_at')->nullable();

            $table->index(['id'], 'id_2');
            $table->primary(['id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice_payments');
    }
};
