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
        Schema::create('announcements', function (Blueprint $table) {
            $table->integer('id', true);
            $table->text('title');
            $table->mediumText('description');
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('created_by')->index('created_by');
            $table->mediumText('share_with')->nullable();
            $table->dateTime('created_at');
            $table->text('files');
            $table->mediumText('read_by')->nullable();
            $table->integer('deleted')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('announcements');
    }
};
