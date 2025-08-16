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
        Schema::create('social_links', function (Blueprint $table) {
            $table->integer('id')->primary();
            $table->integer('user_id');
            $table->text('facebook')->nullable();
            $table->text('twitter')->nullable();
            $table->text('linkedin')->nullable();
            $table->text('googleplus')->nullable();
            $table->text('digg')->nullable();
            $table->text('youtube')->nullable();
            $table->text('pinterest')->nullable();
            $table->text('instagram')->nullable();
            $table->text('github')->nullable();
            $table->text('tumblr')->nullable();
            $table->text('vine')->nullable();
            $table->text('whatsapp')->nullable();
            $table->boolean('deleted')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('social_links');
    }
};
