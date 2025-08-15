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
        Schema::create('project_comments', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('created_by');
            $table->dateTime('created_at');
            $table->mediumText('description');
            $table->integer('project_id')->default(0);
            $table->integer('comment_id')->default(0);
            $table->integer('task_id')->default(0);
            $table->integer('file_id')->default(0);
            $table->integer('customer_feedback_id')->default(0);
            $table->longText('files')->nullable();
            $table->boolean('deleted')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_comments');
    }
};
