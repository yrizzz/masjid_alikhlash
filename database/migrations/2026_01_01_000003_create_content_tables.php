<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->text('excerpt')->nullable();
            $table->longText('body')->nullable();
            $table->string('cover')->nullable();
            $table->string('meta_description')->nullable();
            $table->unsignedInteger('reading_time')->default(1);
            $table->unsignedInteger('views')->default(0);
            $table->boolean('is_featured')->default(false);
            $table->timestamp('published_at')->nullable()->index();
            $table->timestamps();
        });

        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->morphs('commentable');
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('parent_id')->nullable()->constrained('comments')->cascadeOnDelete();
            $table->string('name');
            $table->string('email')->nullable();
            $table->text('body');
            $table->boolean('is_approved')->default(true);
            $table->timestamps();
        });

        Schema::create('galleries', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete();
            $table->text('description')->nullable();
            $table->string('cover')->nullable();
            $table->date('taken_at')->nullable();
            $table->boolean('is_published')->default(true);
            $table->timestamps();
        });

        Schema::create('gallery_photos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gallery_id')->constrained()->cascadeOnDelete();
            $table->string('path');
            $table->string('caption')->nullable();
            $table->unsignedInteger('order')->default(0);
            $table->timestamps();
        });

        // Timeline perkembangan masjid
        Schema::create('milestones', function (Blueprint $table) {
            $table->id();
            $table->year('year');
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->string('icon', 40)->default('milestone');
            $table->unsignedInteger('order')->default(0);
            $table->timestamps();
        });

        Schema::create('facilities', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('icon', 40)->default('circle-check');
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->unsignedInteger('order')->default(0);
            $table->timestamps();
        });

        // E-Library
        Schema::create('ebooks', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete();
            $table->string('author')->nullable();
            $table->string('type')->default('pdf');           // pdf|kitab|slide|video|audio
            $table->text('description')->nullable();
            $table->string('cover')->nullable();
            $table->string('file')->nullable();
            $table->string('external_url')->nullable();
            $table->unsignedInteger('pages')->nullable();
            $table->unsignedInteger('downloads')->default(0);
            $table->boolean('is_published')->default(true);
            $table->timestamps();
        });

        // Digital Quran — data pribadi jamaah
        Schema::create('quran_bookmarks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->unsignedSmallInteger('surah');
            $table->unsignedSmallInteger('ayah');
            $table->string('surah_name')->nullable();
            $table->string('type')->default('bookmark');      // bookmark|highlight|last_read
            $table->string('color', 20)->nullable();
            $table->text('note')->nullable();
            $table->timestamps();
            $table->index(['user_id', 'type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quran_bookmarks');
        Schema::dropIfExists('ebooks');
        Schema::dropIfExists('facilities');
        Schema::dropIfExists('milestones');
        Schema::dropIfExists('gallery_photos');
        Schema::dropIfExists('galleries');
        Schema::dropIfExists('comments');
        Schema::dropIfExists('articles');
    }
};
