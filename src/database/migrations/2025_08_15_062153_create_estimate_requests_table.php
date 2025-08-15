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
        Schema::create('estimate_requests', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('estimate_form_id');
            $table->integer('created_by');
            $table->dateTime('created_at');
            $table->integer('client_id');
            $table->integer('lead_id');
            $table->integer('assigned_to');
            $table->enum('status', ['new', 'processing', 'hold', 'canceled', 'estimated'])->default('new');
            $table->mediumText('files');
            $table->boolean('deleted')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('estimate_requests');
    }
};
