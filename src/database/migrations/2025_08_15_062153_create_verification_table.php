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
        Schema::create('verification', function (Blueprint $table) {
            $table->integer('id', true);
            $table->timestamp('created_at')->useCurrentOnUpdate()->useCurrent();
            $table->enum('type', ['invoice_payment', 'reset_password', 'verify_email', 'invitation']);
            $table->string('code', 10);
            $table->text('params');
            $table->integer('deleted')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('verification');
    }
};
