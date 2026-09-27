<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Badge color per role (hex, e.g. #0d6efd). Null = default color.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('roles', 'color')) {
            return;
        }

        Schema::table('roles', function (Blueprint $table) {
            $table->string('color', 7)->nullable()->after('description');
        });
    }

    public function down(): void
    {
        if (Schema::hasColumn('roles', 'color')) {
            Schema::table('roles', function (Blueprint $table) {
                $table->dropColumn('color');
            });
        }
    }
};
