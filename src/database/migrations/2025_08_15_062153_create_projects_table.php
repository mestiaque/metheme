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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->text('title');
            $table->mediumText('description')->nullable();
            $table->string('project_type', 16)->default('client_project');
            $table->date('start_date')->nullable();
            $table->date('deadline')->nullable();
            $table->foreignId('client_id')->nullable()->constrained('clients');
            $table->string('status', 16)->default('open');
            $table->text('labels')->nullable();
            $table->double('price')->default(0);
            $table->mediumText('starred_by');
            $table->foreignId('estimate_id')->nullable()->constrained('estimates');
            $table->foreignId('order_id')->nullable()->constrained('orders');
            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->foreignId('updated_by')->nullable()->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
