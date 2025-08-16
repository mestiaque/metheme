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
        Schema::create('project_time', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('project_id');
            $table->integer('user_id');
            $table->dateTime('start_time');
            $table->dateTime('end_time')->nullable();
            $table->float('hours');
            $table->enum('status', ['open', 'logged', 'approved'])->default('logged');
            $table->text('note')->nullable();
            $table->integer('task_id')->default(0);
            $table->boolean('deleted')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_time');
    }
};
