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
        Schema::create('leave_applications', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('leave_type_id')->index('leave_type_id');
            $table->date('start_date');
            $table->date('end_date');
            $table->decimal('total_hours', 7);
            $table->decimal('total_days', 5);
            $table->integer('applicant_id')->index('user_id');
            $table->mediumText('reason');
            $table->enum('status', ['pending', 'approved', 'rejected', 'canceled'])->default('pending');
            $table->dateTime('created_at');
            $table->integer('created_by');
            $table->dateTime('checked_at')->nullable();
            $table->integer('checked_by')->default(0)->index('checked_by');
            $table->text('files');
            $table->integer('deleted')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leave_applications');
    }
};
