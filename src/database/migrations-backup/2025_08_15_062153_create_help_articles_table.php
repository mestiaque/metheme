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
        Schema::create('help_articles', function (Blueprint $table) {
            $table->integer('id', true);
            $table->text('title');
            $table->longText('description');
            $table->integer('category_id');
            $table->integer('created_by');
            $table->dateTime('created_at')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->text('files');
            $table->integer('total_views')->default(0);
            $table->integer('sort')->default(0);
            $table->boolean('deleted')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('help_articles');
    }
};
