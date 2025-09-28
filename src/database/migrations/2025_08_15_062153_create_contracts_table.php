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
        Schema::create('contracts', function (Blueprint $table) {
            $table->id();
            $table->text('title');
            $table->integer('client_id');
            $table->integer('project_id');
            $table->date('contract_date');
            $table->date('valid_until');
            $table->mediumText('note')->nullable();
            $table->date('last_email_sent_date')->nullable();
            $table->string('status')->default('draft');
            $table->integer('tax_id')->default(0);
            $table->integer('tax_id2')->default(0);
            $table->string('discount_type')->default('before_tax');
            $table->double('discount_amount');
            $table->string('discount_amount_type')->default('percentage');
            $table->mediumText('content');
            $table->string('public_key', 10);
            $table->integer('accepted_by')->default(0);
            $table->integer('staff_signed_by')->default(0);
            $table->text('meta_data');
            $table->mediumText('files');
            $table->integer('company_id')->default(0);
            $table->boolean('deleted')->default(false);
            $table->foreignId('created_by')->nullable();
            $table->foreignId('updated_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contracts');
    }
};
