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
        Schema::create('task_status', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('title', 100);
            $table->string('key_name', 100);
            $table->string('color', 7);
            $table->integer('sort');
            $table->boolean('hide_from_kanban')->default(false);
            $table->boolean('deleted')->default(false);
            $table->boolean('hide_from_non_project_related_tasks')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('task_status');
    }
};
