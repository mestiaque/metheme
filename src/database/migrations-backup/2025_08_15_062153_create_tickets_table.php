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
        Schema::create('tickets', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('client_id');
            $table->integer('project_id')->default(0);
            $table->integer('ticket_type_id');
            $table->text('title');
            $table->integer('created_by');
            $table->integer('requested_by')->default(0);
            $table->dateTime('created_at');
            $table->enum('status', ['new', 'client_replied', 'open', 'closed'])->default('new');
            $table->dateTime('last_activity_at')->nullable();
            $table->integer('assigned_to')->default(0);
            $table->string('creator_name', 100);
            $table->string('creator_email');
            $table->text('labels')->nullable();
            $table->integer('task_id');
            $table->dateTime('closed_at');
            $table->integer('merged_with_ticket_id');
            $table->boolean('deleted')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
