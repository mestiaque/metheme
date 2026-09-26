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
        Schema::create('sms_accounts', function (Blueprint $table) {
            $table->id();
            $table->decimal('admin_recharge_amount', 10, 2)->default(0);
            $table->decimal('sms_rate', 10, 2)->default(0);
            $table->integer('sms_used')->default(0);
            $table->decimal('balance', 10, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sms_accounts');
    }
};
