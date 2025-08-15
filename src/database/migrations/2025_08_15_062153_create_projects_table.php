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
        Schema::create('projects', function (Blueprint $table) {
            $table->integer('id', true);
            $table->text('title');
            $table->mediumText('description')->nullable();
            $table->enum('project_type', ['client_project', 'internal_project'])->default('client_project');
            $table->date('start_date')->nullable();
            $table->date('deadline')->nullable();
            $table->integer('client_id');
            $table->date('created_date')->nullable();
            $table->integer('created_by')->default(0);
            $table->enum('status', ['open', 'completed', 'hold', 'canceled'])->default('open');
            $table->integer('status_id')->default(1);
            $table->text('labels')->nullable();
            $table->double('price')->default(0);
            $table->mediumText('starred_by');
            $table->integer('estimate_id');
            $table->integer('order_id');
            $table->boolean('deleted')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
