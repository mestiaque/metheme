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
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->integer('id', true);
            $table->dateTime('created_at');
            $table->integer('created_by');
            $table->enum('action', ['created', 'updated', 'deleted', 'bitbucket_notification_received', 'github_notification_received']);
            $table->string('log_type', 30);
            $table->mediumText('log_type_title');
            $table->integer('log_type_id')->default(0);
            $table->mediumText('changes')->nullable();
            $table->string('log_for', 30)->default('0');
            $table->integer('log_for_id')->default(0);
            $table->string('log_for2', 30)->nullable();
            $table->integer('log_for_id2')->nullable();
            $table->boolean('deleted')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
