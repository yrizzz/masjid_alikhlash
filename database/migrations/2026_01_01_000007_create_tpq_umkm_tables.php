<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tpq_classes', function (Blueprint $table) {
            $table->id();
            $table->string('name');                           // Iqra 1, Al-Quran A, ...
            $table->string('level')->nullable();
            $table->string('teacher');
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('schedule')->nullable();           // "Senin & Rabu 16.00"
            $table->string('room')->nullable();
            $table->decimal('fee', 15, 2)->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('tpq_students', function (Blueprint $table) {
            $table->id();
            $table->string('nis')->unique();
            $table->foreignId('tpq_class_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->string('gender', 10)->nullable();
            $table->date('birth_date')->nullable();
            $table->string('parent_name')->nullable();
            $table->string('phone', 30)->nullable();
            $table->text('address')->nullable();
            $table->string('photo')->nullable();
            $table->date('joined_at')->nullable();
            $table->string('status')->default('aktif');       // aktif|lulus|keluar
            $table->timestamps();
        });

        Schema::create('tpq_attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tpq_student_id')->constrained()->cascadeOnDelete();
            $table->date('date');
            $table->string('status', 20)->default('hadir');   // hadir|izin|sakit|alpa
            $table->string('note')->nullable();
            $table->timestamps();
            $table->unique(['tpq_student_id', 'date']);
        });

        Schema::create('tpq_grades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tpq_student_id')->constrained()->cascadeOnDelete();
            $table->string('term')->default('Ganjil');
            $table->string('subject');                        // Tahsin, Tahfidz, Fiqih, Akhlak
            $table->unsignedTinyInteger('score')->default(0);
            $table->string('predicate', 5)->nullable();
            $table->text('note')->nullable();
            $table->timestamps();
        });

        Schema::create('tpq_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tpq_student_id')->constrained()->cascadeOnDelete();
            $table->string('period');                         // 2026-07
            $table->decimal('amount', 15, 2);
            $table->string('status')->default('belum');       // belum|lunas
            $table->date('paid_at')->nullable();
            $table->timestamps();
            $table->unique(['tpq_student_id', 'period']);
        });

        Schema::create('umkm_businesses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete();
            $table->string('owner');
            $table->text('description')->nullable();
            $table->string('logo')->nullable();
            $table->string('cover')->nullable();
            $table->string('phone', 30)->nullable();
            $table->string('whatsapp', 30)->nullable();
            $table->string('instagram')->nullable();
            $table->text('address')->nullable();
            $table->decimal('lat', 10, 7)->nullable();
            $table->decimal('lng', 10, 7)->nullable();
            $table->string('status')->default('pending');     // pending|approved|rejected
            $table->boolean('is_featured')->default(false);
            $table->unsignedInteger('views')->default(0);
            $table->timestamps();
        });

        Schema::create('umkm_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('umkm_business_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->decimal('price', 15, 2)->default(0);
            $table->string('unit', 30)->nullable();
            $table->text('description')->nullable();
            $table->string('photo')->nullable();
            $table->boolean('is_available')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('umkm_products');
        Schema::dropIfExists('umkm_businesses');
        Schema::dropIfExists('tpq_payments');
        Schema::dropIfExists('tpq_grades');
        Schema::dropIfExists('tpq_attendances');
        Schema::dropIfExists('tpq_students');
        Schema::dropIfExists('tpq_classes');
    }
};
