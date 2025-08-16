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
        Schema::create('orders', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('client_id');
            $table->date('order_date');
            $table->mediumText('note')->nullable();
            $table->integer('status_id');
            $table->integer('tax_id')->default(0);
            $table->integer('tax_id2')->default(0);
            $table->double('discount_amount');
            $table->enum('discount_amount_type', ['percentage', 'fixed_amount']);
            $table->enum('discount_type', ['before_tax', 'after_tax']);
            $table->integer('created_by')->default(0);
            $table->integer('project_id')->default(0);
            $table->longText('files');
            $table->integer('company_id')->default(0);
            $table->boolean('deleted')->default(false);
            $table->text('created_by_hash');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
