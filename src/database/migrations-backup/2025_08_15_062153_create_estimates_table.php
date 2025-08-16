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
        Schema::create('estimates', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('client_id');
            $table->integer('estimate_request_id')->default(0);
            $table->date('estimate_date');
            $table->date('valid_until');
            $table->mediumText('note')->nullable();
            $table->date('last_email_sent_date')->nullable();
            $table->enum('status', ['draft', 'sent', 'accepted', 'declined'])->default('draft');
            $table->integer('tax_id')->default(0);
            $table->integer('tax_id2')->default(0);
            $table->enum('discount_type', ['before_tax', 'after_tax']);
            $table->double('discount_amount');
            $table->enum('discount_amount_type', ['percentage', 'fixed_amount']);
            $table->integer('project_id')->default(0);
            $table->integer('accepted_by')->default(0);
            $table->text('meta_data');
            $table->integer('created_by');
            $table->text('signature');
            $table->text('public_key');
            $table->integer('company_id')->default(0);
            $table->boolean('deleted')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('estimates');
    }
};
