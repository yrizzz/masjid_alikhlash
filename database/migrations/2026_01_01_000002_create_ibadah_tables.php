<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Jadwal imam & muadzin harian (berulang per hari dalam sepekan)
        Schema::create('imam_schedules', function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('day_of_week');       // 0 = Ahad … 6 = Sabtu
            $table->string('prayer', 20);                     // subuh|dzuhur|ashar|maghrib|isya
            $table->string('imam');
            $table->string('muadzin')->nullable();
            $table->string('backup')->nullable();
            $table->text('note')->nullable();
            $table->timestamps();
            $table->unique(['day_of_week', 'prayer']);
        });

        Schema::create('jumat_schedules', function (Blueprint $table) {
            $table->id();
            $table->date('date')->unique();
            $table->string('theme');
            $table->string('khatib');
            $table->string('imam')->nullable();
            $table->string('muadzin')->nullable();
            $table->string('poster')->nullable();
            $table->string('attachment')->nullable();
            $table->text('summary')->nullable();
            $table->timestamps();
        });

        // Agenda / kalender masjid
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->nullable()->index();
            $table->string('type')->default('agenda');        // kajian|rapat|tpq|kerja-bakti|hari-besar|libur-nasional|agenda
            $table->dateTime('start_at');
            $table->dateTime('end_at')->nullable();
            $table->boolean('all_day')->default(false);
            $table->string('location')->nullable();
            $table->text('description')->nullable();
            $table->string('color', 20)->nullable();
            $table->boolean('is_public')->default(true);
            $table->timestamps();
        });

        Schema::create('kajians', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete();
            $table->string('ustadz');
            $table->string('ustadz_photo')->nullable();
            $table->text('excerpt')->nullable();
            $table->longText('description')->nullable();
            $table->string('poster')->nullable();
            $table->string('media_type')->default('none');    // video|audio|pdf|slide|none
            $table->string('media_url')->nullable();          // youtube / mp3 / pdf
            $table->string('attachment')->nullable();
            $table->dateTime('start_at')->nullable();
            $table->dateTime('end_at')->nullable();
            $table->string('location')->default('Masjid Al-Ikhlash');
            $table->string('recurrence')->nullable();         // mingguan-senin, bulanan, dst
            $table->boolean('is_published')->default(true);
            $table->boolean('open_registration')->default(false);
            $table->unsignedInteger('quota')->nullable();
            $table->unsignedInteger('views')->default(0);
            $table->timestamps();
        });

        Schema::create('kajian_registrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kajian_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->string('phone', 30)->nullable();
            $table->string('code')->unique();                 // untuk QR check-in
            $table->timestamp('checked_in_at')->nullable();
            $table->timestamps();
        });

        Schema::create('bookmarks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->morphs('bookmarkable');
            $table->timestamps();
        });

        Schema::create('livestreams', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('platform')->default('youtube');   // youtube|facebook|instagram|tiktok
            $table->string('url');
            $table->string('embed_id')->nullable();
            $table->dateTime('start_at')->nullable();
            $table->string('status')->default('scheduled');   // scheduled|live|ended
            $table->text('description')->nullable();
            $table->string('thumbnail')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('livestreams');
        Schema::dropIfExists('bookmarks');
        Schema::dropIfExists('kajian_registrations');
        Schema::dropIfExists('kajians');
        Schema::dropIfExists('events');
        Schema::dropIfExists('jumat_schedules');
        Schema::dropIfExists('imam_schedules');
    }
};
