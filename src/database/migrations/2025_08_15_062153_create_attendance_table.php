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
        Schema::create('attendance', function (Blueprint $table) {
            $table->integer('id', true);
            $table->enum('status', ['incomplete', 'pending', 'approved', 'rejected', 'deleted'])->default('incomplete');
            $table->integer('user_id')->index('user_id');
            $table->dateTime('in_time');
            $table->dateTime('out_time')->nullable();
            $table->integer('checked_by')->nullable()->index('checked_by');
            $table->text('note')->nullable();
            $table->dateTime('checked_at')->nullable();
            $table->text('reject_reason')->nullable();
            $table->integer('deleted')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendance');
    }
};
