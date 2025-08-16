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
        Schema::create('estimate_forms', function (Blueprint $table) {
            $table->integer('id', true);
            $table->text('title');
            $table->longText('description');
            $table->enum('status', ['active', 'inactive']);
            $table->integer('assigned_to');
            $table->boolean('public')->default(false);
            $table->tinyInteger('enable_attachment')->default(0);
            $table->boolean('deleted')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('estimate_forms');
    }
};
