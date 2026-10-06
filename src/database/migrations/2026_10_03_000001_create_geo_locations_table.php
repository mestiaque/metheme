<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Country > division > district > upazila in one self-referencing table.
     * Fill it with "php artisan metheme:sync-geo-locations" (reads src/public/geo-data.json).
     */
    public function up(): void
    {
        Schema::create('geo_locations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_id')->nullable()->constrained('geo_locations')->cascadeOnDelete();
            $table->string('type', 20);
            $table->unsignedInteger('source_id')->nullable();
            $table->string('name', 150);
            $table->string('bn_name', 150)->nullable();
            $table->decimal('lat', 10, 7)->nullable();
            $table->decimal('long', 10, 7)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['type', 'source_id']);
            $table->index(['parent_id', 'type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('geo_locations');
    }
};
