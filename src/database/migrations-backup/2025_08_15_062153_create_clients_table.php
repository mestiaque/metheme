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
        Schema::create('clients', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('company_name', 150);
            $table->enum('type', ['organization', 'person'])->default('organization');
            $table->text('address')->nullable();
            $table->string('city', 50)->nullable();
            $table->string('state', 50)->nullable();
            $table->string('zip', 50)->nullable();
            $table->string('country', 50)->nullable();
            $table->date('created_date');
            $table->text('website')->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('currency_symbol', 20)->nullable();
            $table->mediumText('starred_by');
            $table->text('group_ids');
            $table->boolean('deleted')->default(false);
            $table->boolean('is_lead')->default(false);
            $table->integer('lead_status_id');
            $table->integer('owner_id');
            $table->integer('created_by');
            $table->integer('sort')->default(0);
            $table->integer('lead_source_id');
            $table->text('last_lead_status');
            $table->date('client_migration_date');
            $table->text('vat_number')->nullable();
            $table->text('gst_number')->nullable();
            $table->text('stripe_customer_id');
            $table->integer('stripe_card_ending_digit');
            $table->string('currency', 3)->nullable();
            $table->boolean('disable_online_payment')->default(false);
            $table->text('labels')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
