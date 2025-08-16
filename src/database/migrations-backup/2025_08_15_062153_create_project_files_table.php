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
        Schema::create('project_files', function (Blueprint $table) {
            $table->integer('id', true);
            $table->text('file_name');
            $table->text('file_id')->nullable();
            $table->string('service_type', 20)->nullable();
            $table->mediumText('description')->nullable();
            $table->double('file_size');
            $table->dateTime('created_at');
            $table->integer('project_id');
            $table->integer('uploaded_by');
            $table->integer('category_id')->default(0);
            $table->boolean('deleted')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_files');
    }
};
