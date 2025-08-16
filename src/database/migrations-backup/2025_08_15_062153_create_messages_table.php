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
        Schema::create('messages', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('subject')->default('Untitled');
            $table->mediumText('message');
            $table->dateTime('created_at');
            $table->integer('from_user_id')->index('message_from');
            $table->integer('to_user_id')->index('message_to');
            $table->enum('status', ['unread', 'read'])->default('unread');
            $table->integer('message_id')->default(0);
            $table->integer('deleted')->default(0);
            $table->longText('files');
            $table->text('deleted_by_users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
