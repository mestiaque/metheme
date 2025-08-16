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
        Schema::create('labels', function (Blueprint $table) {
            $table->integer('id', true);
            $table->text('title');
            $table->string('color', 15);
            $table->enum('context', ['event', 'invoice', 'note', 'project', 'task', 'ticket', 'to_do', 'subscription', 'client'])->nullable();
            $table->integer('user_id')->default(0);
            $table->integer('deleted')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('labels');
    }
};
