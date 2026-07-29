<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('jamaah')->after('email');
            $table->string('member_no')->nullable()->unique()->after('role');
            $table->string('phone', 30)->nullable()->after('member_no');
            $table->string('avatar')->nullable()->after('phone');
            $table->date('birth_date')->nullable()->after('avatar');
            $table->string('gender', 10)->nullable()->after('birth_date');
            $table->text('address')->nullable()->after('gender');
            $table->string('occupation')->nullable()->after('address');
            $table->text('skills')->nullable()->after('occupation');
            $table->text('bio')->nullable()->after('skills');
            $table->boolean('is_active')->default(true)->after('bio');
            $table->timestamp('last_seen_at')->nullable()->after('is_active');
        });

        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->longText('value')->nullable();
            $table->string('group')->default('general');
            $table->timestamps();
        });

        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('type')->index();   // artikel | kajian | galeri | inventaris | keuangan | program | umkm | ebook
            $table->string('name');
            $table->string('slug')->index();
            $table->string('color', 20)->nullable();
            $table->string('icon', 50)->nullable();
            $table->text('description')->nullable();
            $table->unsignedInteger('order')->default(0);
            $table->timestamps();
        });

        Schema::create('tags', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->timestamps();
        });

        Schema::create('taggables', function (Blueprint $table) {
            $table->foreignId('tag_id')->constrained()->cascadeOnDelete();
            $table->morphs('taggable');
            $table->primary(['tag_id', 'taggable_id', 'taggable_type']);
        });

        Schema::create('pengurus', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->string('position');                       // Ketua Takmir, Sekretaris, ...
            $table->string('division')->nullable();           // Takmir, Remaja, TPQ, ...
            $table->unsignedInteger('level')->default(2);     // 1 = pimpinan inti
            $table->string('photo')->nullable();
            $table->string('phone', 30)->nullable();
            $table->string('email')->nullable();
            $table->year('period_start')->nullable();
            $table->year('period_end')->nullable();
            $table->text('bio')->nullable();
            $table->unsignedInteger('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('media', function (Blueprint $table) {
            $table->id();
            $table->string('folder')->default('/')->index();
            $table->string('name');
            $table->string('path');
            $table->string('disk')->default('public');
            $table->string('mime', 100)->nullable();
            $table->unsignedBigInteger('size')->default(0);
            $table->string('alt')->nullable();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->longText('body')->nullable();
            $table->string('cover')->nullable();
            $table->string('template')->default('default');
            $table->boolean('is_published')->default(true);
            $table->string('meta_description')->nullable();
            $table->timestamps();
        });

        Schema::create('banners', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('subtitle')->nullable();
            $table->string('image')->nullable();
            $table->string('link')->nullable();
            $table->string('link_text')->nullable();
            $table->string('position')->default('hero');   // hero | sidebar | popup
            $table->unsignedInteger('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('faqs', function (Blueprint $table) {
            $table->id();
            $table->string('question');
            $table->text('answer');
            $table->string('group')->default('umum');
            $table->unsignedInteger('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('running_texts', function (Blueprint $table) {
            $table->id();
            $table->string('text');
            $table->string('icon', 40)->nullable();
            $table->unsignedInteger('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('contact_messages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('phone', 30)->nullable();
            $table->string('subject')->nullable();
            $table->text('message');
            $table->boolean('is_read')->default(false);
            $table->timestamps();
        });

        Schema::create('page_views', function (Blueprint $table) {
            $table->id();
            $table->string('path')->index();
            $table->string('referer')->nullable();
            $table->string('ip', 45)->nullable();
            $table->string('agent')->nullable();
            $table->date('date')->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('page_views');
        Schema::dropIfExists('contact_messages');
        Schema::dropIfExists('running_texts');
        Schema::dropIfExists('faqs');
        Schema::dropIfExists('banners');
        Schema::dropIfExists('pages');
        Schema::dropIfExists('media');
        Schema::dropIfExists('pengurus');
        Schema::dropIfExists('taggables');
        Schema::dropIfExists('tags');
        Schema::dropIfExists('categories');
        Schema::dropIfExists('settings');

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'member_no', 'phone', 'avatar', 'birth_date', 'gender', 'address', 'occupation', 'skills', 'bio', 'is_active', 'last_seen_at']);
        });
    }
};
