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
        Schema::create('contract_items', function (Blueprint $table) {
            $table->integer('id', true);
            $table->text('title');
            $table->text('description')->nullable();
            $table->double('quantity');
            $table->string('unit_type', 20)->default('');
            $table->double('rate');
            $table->double('total');
            $table->integer('sort')->default(0);
            $table->integer('contract_id');
            $table->integer('item_id')->default(0);
            $table->boolean('deleted')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contract_items');
    }
};
