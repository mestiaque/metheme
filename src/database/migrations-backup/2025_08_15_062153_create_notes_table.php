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
        Schema::create('notes', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('created_by');
            $table->dateTime('created_at');
            $table->text('title');
            $table->mediumText('description')->nullable();
            $table->integer('project_id')->default(0);
            $table->integer('client_id')->default(0);
            $table->integer('user_id')->default(0);
            $table->text('labels')->nullable();
            $table->mediumText('files');
            $table->boolean('is_public')->default(false);
            $table->boolean('deleted')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notes');
    }
};
