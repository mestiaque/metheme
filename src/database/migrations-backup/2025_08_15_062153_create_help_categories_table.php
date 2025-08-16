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
        Schema::create('help_categories', function (Blueprint $table) {
            $table->integer('id', true);
            $table->text('title');
            $table->text('description');
            $table->enum('type', ['help', 'knowledge_base']);
            $table->integer('sort');
            $table->string('articles_order', 3)->default('');
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->boolean('deleted')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('help_categories');
    }
};
