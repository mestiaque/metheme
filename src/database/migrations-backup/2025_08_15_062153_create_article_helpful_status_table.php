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
        Schema::create('article_helpful_status', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('article_id');
            $table->enum('status', ['yes', 'no']);
            $table->integer('created_by')->default(0);
            $table->dateTime('created_at');
            $table->boolean('deleted')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('article_helpful_status');
    }
};
