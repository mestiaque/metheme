<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->timestamps();
        });

        // Insert default settings
        DB::table('settings')->insert([
            ['key' => 'shop_name', 'value' => 'My Shop', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'shop_address', 'value' => '123 Main Street', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'shop_email', 'value' => 'info@myshop.com', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'shop_phone', 'value' => '+880 1234 567890', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'shop_logo', 'value' => null, 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'low_stock_threshold', 'value' => '5', 'created_at' => now(), 'updated_at' => now()]
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
