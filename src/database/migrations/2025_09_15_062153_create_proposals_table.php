<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('proposals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('client_id');
            $table->date('proposal_date');
            $table->date('valid_until');
            $table->mediumText('note')->nullable();
            $table->date('last_email_sent_date')->nullable();
            $table->string('status')->default('draft'); // instead of enum
            $table->unsignedBigInteger('tax_id')->nullable();
            $table->unsignedBigInteger('tax_id2')->nullable();
            $table->string('discount_type')->nullable(); // before_tax, after_tax
            $table->decimal('discount_amount', 15, 2)->default(0);
            $table->string('discount_amount_type')->nullable(); // percentage, fixed_amount
            $table->mediumText('content');
            $table->string('public_key', 50)->unique(); // increased length for safety
            $table->unsignedBigInteger('accepted_by')->nullable();
            $table->json('meta_data')->nullable();
            $table->unsignedBigInteger('company_id')->nullable();

            $table->softDeletes();
            $table->timestamps();

            // Foreign keys (adjust tables if exist)
            // $table->foreign('client_id')->references('id')->on('clients')->cascadeOnDelete();
            // $table->foreign('accepted_by')->references('id')->on('users')->nullOnDelete();
            // $table->foreign('company_id')->references('id')->on('companies')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('proposals');
    }
};
