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
        Schema::create('users', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('first_name', 50);
            $table->string('last_name', 50);
            $table->enum('user_type', ['staff', 'client', 'lead'])->default('client')->index('user_type');
            $table->boolean('is_admin')->default(false);
            $table->integer('role_id')->default(0);
            $table->string('email')->index('email');
            $table->string('password')->nullable();
            $table->text('image')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->dateTime('message_checked_at')->nullable();
            $table->integer('client_id')->default(0)->index('client_id');
            $table->dateTime('notification_checked_at')->nullable();
            $table->boolean('is_primary_contact')->default(false);
            $table->string('job_title', 100)->default('Untitled');
            $table->boolean('disable_login')->default(false);
            $table->mediumText('note')->nullable();
            $table->text('address')->nullable();
            $table->text('alternative_address')->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('alternative_phone', 20)->nullable();
            $table->date('dob')->nullable();
            $table->string('ssn', 20)->nullable();
            $table->enum('gender', ['male', 'female', 'other'])->nullable();
            $table->mediumText('sticky_note')->nullable();
            $table->text('skype')->nullable();
            $table->string('language', 50);
            $table->boolean('enable_web_notification')->default(true);
            $table->boolean('enable_email_notification')->default(true);
            $table->dateTime('created_at')->nullable();
            $table->dateTime('last_online')->nullable();
            $table->boolean('requested_account_removal')->default(false);
            $table->integer('deleted')->default(0)->index('deleted');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
