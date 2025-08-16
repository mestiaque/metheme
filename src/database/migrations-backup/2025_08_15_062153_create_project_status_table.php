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
        Schema::create('project_status', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('title', 100);
            $table->text('title_language_key');
            $table->string('key_name', 100);
            $table->string('icon', 50);
            $table->boolean('deleted')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_status');
    }
};
