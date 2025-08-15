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
        Schema::create('company', function (Blueprint $table) {
            $table->integer('id', true);
            $table->text('name');
            $table->text('address');
            $table->text('phone');
            $table->text('email');
            $table->text('website');
            $table->text('vat_number');
            $table->boolean('is_default')->default(false);
            $table->mediumText('logo');
            $table->boolean('deleted')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company');
    }
};
