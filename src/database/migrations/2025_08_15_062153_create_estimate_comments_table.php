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
        Schema::create('estimate_comments', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('created_by');
            $table->dateTime('created_at');
            $table->mediumText('description');
            $table->integer('estimate_id')->default(0);
            $table->longText('files')->nullable();
            $table->boolean('deleted')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('estimate_comments');
    }
};
