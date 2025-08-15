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
        Schema::create('notification_settings', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('event', 250)->index('event');
            $table->string('category', 50);
            $table->integer('enable_email')->default(0);
            $table->integer('enable_web')->default(0);
            $table->integer('enable_slack')->default(0);
            $table->text('notify_to_team');
            $table->text('notify_to_team_members');
            $table->text('notify_to_terms');
            $table->integer('sort');
            $table->integer('deleted')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notification_settings');
    }
};
