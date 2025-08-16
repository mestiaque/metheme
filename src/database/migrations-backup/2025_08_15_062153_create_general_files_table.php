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
        Schema::create('general_files', function (Blueprint $table) {
            $table->integer('id', true);
            $table->text('file_name');
            $table->text('file_id')->nullable();
            $table->string('service_type', 20)->nullable();
            $table->mediumText('description')->nullable();
            $table->double('file_size');
            $table->dateTime('created_at');
            $table->integer('client_id')->default(0);
            $table->integer('user_id')->default(0);
            $table->integer('uploaded_by');
            $table->boolean('deleted')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('general_files');
    }
};
