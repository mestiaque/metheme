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
        Schema::create('email_templates', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('template_name', 50);
            $table->text('email_subject');
            $table->mediumText('default_message');
            $table->mediumText('custom_message')->nullable();
            $table->enum('template_type', ['default', 'custom'])->default('default');
            $table->string('language', 50);
            $table->boolean('deleted')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('email_templates');
    }
};
