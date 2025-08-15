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
        Schema::create('expenses', function (Blueprint $table) {
            $table->integer('id', true);
            $table->date('expense_date');
            $table->integer('category_id');
            $table->mediumText('description')->nullable();
            $table->double('amount');
            $table->mediumText('files');
            $table->text('title');
            $table->integer('project_id')->default(0);
            $table->integer('user_id')->default(0);
            $table->integer('tax_id')->default(0);
            $table->integer('tax_id2')->default(0);
            $table->integer('client_id')->default(0);
            $table->tinyInteger('recurring')->default(0);
            $table->tinyInteger('recurring_expense_id')->default(0);
            $table->integer('repeat_every')->default(0);
            $table->enum('repeat_type', ['days', 'weeks', 'months', 'years'])->nullable();
            $table->integer('no_of_cycles')->default(0);
            $table->date('next_recurring_date')->nullable();
            $table->integer('no_of_cycles_completed')->default(0);
            $table->boolean('deleted')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
