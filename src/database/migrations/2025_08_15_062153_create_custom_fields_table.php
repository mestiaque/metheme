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
        Schema::create('custom_fields', function (Blueprint $table) {
            $table->integer('id', true);
            $table->text('title');
            $table->text('title_language_key');
            $table->text('placeholder_language_key');
            $table->tinyInteger('show_in_embedded_form')->default(0);
            $table->text('placeholder');
            $table->text('example_variable_name')->nullable();
            $table->mediumText('options');
            $table->string('field_type', 50);
            $table->string('related_to', 50)->index('related_to');
            $table->integer('sort');
            $table->boolean('required')->default(false);
            $table->boolean('add_filter')->default(false);
            $table->boolean('show_in_table')->default(false);
            $table->boolean('show_in_invoice')->default(false);
            $table->boolean('show_in_estimate')->default(false);
            $table->boolean('show_in_contract')->default(false);
            $table->boolean('show_in_order')->default(false);
            $table->boolean('show_in_proposal')->default(false);
            $table->boolean('visible_to_admins_only')->default(false);
            $table->boolean('hide_from_clients')->default(false);
            $table->boolean('disable_editing_by_clients')->default(false);
            $table->boolean('show_on_kanban_card')->default(false);
            $table->boolean('deleted')->default(false);
            $table->boolean('show_in_subscription')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('custom_fields');
    }
};
