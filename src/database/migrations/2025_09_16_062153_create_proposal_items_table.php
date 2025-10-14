<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('proposal_items', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->decimal('quantity', 15, 2)->default(1);
            $table->string('unit_type', 20)->default('');
            $table->decimal('rate', 15, 2)->default(0);
            $table->decimal('total', 15, 2)->default(0);
            $table->integer('sort')->default(0);

            $table->unsignedBigInteger('proposal_id');
            $table->unsignedBigInteger('item_id')->nullable();

            $table->softDeletes();
            $table->timestamps();

            $table->foreign('proposal_id')->references('id')->on('proposals')->cascadeOnDelete();
            // $table->foreign('item_id')->references('id')->on('items')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('proposal_items');
    }
};
