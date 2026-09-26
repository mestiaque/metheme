<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mail_logs', function (Blueprint $table) {
            $table->id();
            $table->string('to', 500);
            $table->string('cc', 500)->nullable();
            $table->string('subject')->nullable();
            $table->string('mailer', 50)->nullable();
            // sending = handed to the mailer; sent = mailer confirmed. A row left at "sending" means the send failed.
            $table->string('status', 20)->default('sending')->index();
            $table->timestamp('sent_at')->nullable();
            $table->timestamps();
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mail_logs');
    }
};
