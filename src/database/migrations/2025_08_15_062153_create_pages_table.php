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
        Schema::create('pages', function (Blueprint $table) {
            $table->integer('id', true);
            $table->text('title')->nullable();
            $table->longText('content')->nullable();
            $table->text('slug')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->boolean('internal_use_only')->default(false);
            $table->boolean('visible_to_team_members_only')->default(false);
            $table->boolean('visible_to_clients_only')->default(false);
            $table->boolean('full_width')->default(false);
            $table->boolean('hide_topbar')->default(false);
            $table->integer('deleted')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pages');
    }
};
