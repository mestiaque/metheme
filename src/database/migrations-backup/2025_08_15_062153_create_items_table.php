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
        Schema::create('items', function (Blueprint $table) {
            $table->integer('id', true);
            $table->text('title');
            $table->text('description')->nullable();
            $table->string('unit_type', 20)->default('');
            $table->double('rate');
            $table->mediumText('files');
            $table->boolean('show_in_client_portal')->default(false);
            $table->integer('category_id');
            $table->boolean('taxable')->default(false);
            $table->integer('sort')->default(0);
            $table->boolean('deleted')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
