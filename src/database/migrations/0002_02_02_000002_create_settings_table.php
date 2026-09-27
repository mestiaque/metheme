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
            ['key' => 'app_name', 'value' => 'My app', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'app_address', 'value' => '123 Main Street', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'app_email', 'value' => 'info@myapp.com', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'app_phone', 'value' => '+880 1234 567890', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'app_logo', 'value' => null, 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'low_stock_threshold', 'value' => '5', 'created_at' => now(), 'updated_at' => now()]
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
