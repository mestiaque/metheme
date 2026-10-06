<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/*
| One table for every uploaded file / image of every package (metheme, ecom, efront …).
| A file belongs to any model through a polymorphic relation (mediable_type / mediable_id)
| and sits in a named "collection" of that model (avatar, gallery, logo, banner, image …).
| Models opt in with the ME\Traits\HasMedia trait.
*/
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('me_media', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->nullableMorphs('mediable');                       // owner; null = uploaded, not attached yet
            $table->string('collection', 50)->default('default');
            $table->string('disk', 20)->default('public');
            $table->string('path');
            $table->string('original_name')->nullable();
            $table->string('mime_type', 120)->nullable();
            $table->string('extension', 20)->nullable();
            $table->unsignedBigInteger('size')->default(0);           // bytes
            $table->unsignedInteger('width')->nullable();
            $table->unsignedInteger('height')->nullable();
            $table->json('conversions')->nullable();                   // {"thumb": "media/…/thumb.webp"}
            $table->string('visibility', 10)->default('public');      // public | private (signed URL)
            $table->string('alt')->nullable();
            $table->string('title')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->char('hash', 40)->nullable()->index();            // sha1 of the file, finds duplicates
            $table->json('custom_properties')->nullable();
            $table->nullableMorphs('uploaded_by');                     // admin user or shop customer
            $table->timestamps();
            $table->softDeletes();                                     // trash; metheme:media-cleanup empties it

            $table->index(['mediable_type', 'mediable_id', 'collection']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('me_media');
    }
};
