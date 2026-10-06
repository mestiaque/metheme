<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Data change log inside the activity log: one activity row can hold the readable
 * before/after changes of a whole action (e.g. an order and its items).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('user_activities', function (Blueprint $table) {
            if (! Schema::hasColumn('user_activities', 'subject_type')) {
                $table->string('subject_type')->nullable()->after('url');
                $table->string('subject_id', 64)->nullable()->after('subject_type');
                $table->json('changes')->nullable()->after('subject_id');
                $table->unsignedInteger('change_count')->nullable()->after('changes');
                $table->index(['subject_type', 'subject_id']);
            }
        });
    }

    public function down(): void
    {
        Schema::table('user_activities', function (Blueprint $table) {
            if (Schema::hasColumn('user_activities', 'subject_type')) {
                $table->dropIndex(['subject_type', 'subject_id']);
                $table->dropColumn(['subject_type', 'subject_id', 'changes', 'change_count']);
            }
        });
    }
};
