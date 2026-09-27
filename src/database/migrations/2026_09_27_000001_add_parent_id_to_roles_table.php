<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Role hierarchy: every role may have a parent role. A role without a parent is a top
 * (root) role. Users can only manage roles below their own role(s).
 */
return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('roles', 'parent_id')) {
            return;
        }

        Schema::table('roles', function (Blueprint $table) {
            $table->foreignId('parent_id')->nullable()->after('id')
                ->constrained('roles')->nullOnDelete();
        });
    }

    public function down(): void
    {
        if (! Schema::hasColumn('roles', 'parent_id')) {
            return;
        }

        Schema::table('roles', function (Blueprint $table) {
            $table->dropConstrainedForeignId('parent_id');
        });
    }
};
