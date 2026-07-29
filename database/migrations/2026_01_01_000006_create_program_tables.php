<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('programs', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('type')->default('umum');          // ramadhan|qurban|zakat|tpq|remaja|baksos|umum
            $table->text('excerpt')->nullable();
            $table->longText('description')->nullable();
            $table->string('cover')->nullable();
            $table->string('icon', 40)->default('sparkles');
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->string('pic')->nullable();
            $table->string('status')->default('active');      // active|selesai|draft
            $table->boolean('is_featured')->default(false);
            $table->unsignedInteger('order')->default(0);
            $table->timestamps();
        });

        Schema::create('volunteers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->string('phone', 30)->nullable();
            $table->string('email')->nullable();
            $table->text('address')->nullable();
            $table->text('interests')->nullable();            // json list
            $table->text('skills')->nullable();
            $table->string('availability')->nullable();
            $table->text('motivation')->nullable();
            $table->string('status')->default('pending');     // pending|active|inactive|rejected
            $table->timestamps();
        });

        Schema::create('volunteer_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('volunteer_id')->constrained()->cascadeOnDelete();
            $table->foreignId('program_id')->nullable()->constrained()->nullOnDelete();
            $table->string('role')->nullable();
            $table->dateTime('start_at')->nullable();
            $table->dateTime('end_at')->nullable();
            $table->string('status')->default('assigned');    // assigned|done|cancelled
            $table->text('note')->nullable();
            $table->timestamps();
        });

        Schema::create('qurban_animals', function (Blueprint $table) {
            $table->id();
            $table->year('year');
            $table->string('type');                           // sapi|kambing|domba
            $table->string('code')->nullable();
            $table->unsignedInteger('slots')->default(1);     // sapi = 7
            $table->unsignedInteger('slots_taken')->default(0);
            $table->decimal('price_per_slot', 15, 2)->default(0);
            $table->string('photo')->nullable();
            $table->text('description')->nullable();
            $table->string('status')->default('open');        // open|full|disembelih|distribusi|selesai
            $table->timestamps();
        });

        Schema::create('qurban_participants', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->foreignId('qurban_animal_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->string('on_behalf_of')->nullable();
            $table->string('phone', 30)->nullable();
            $table->unsignedInteger('slots')->default(1);
            $table->decimal('amount', 15, 2)->default(0);
            $table->decimal('paid', 15, 2)->default(0);
            $table->string('status')->default('pending');     // pending|lunas|batal
            $table->timestamps();
        });

        Schema::create('zakat_payments', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('donation_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->string('phone', 30)->nullable();
            $table->string('type');                           // fitrah|maal|profesi|emas|perdagangan
            $table->decimal('base_amount', 15, 2)->default(0);
            $table->decimal('amount', 15, 2);
            $table->unsignedInteger('people')->default(1);
            $table->string('status')->default('pending');
            $table->text('note')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('zakat_payments');
        Schema::dropIfExists('qurban_participants');
        Schema::dropIfExists('qurban_animals');
        Schema::dropIfExists('volunteer_assignments');
        Schema::dropIfExists('volunteers');
        Schema::dropIfExists('programs');
    }
};
