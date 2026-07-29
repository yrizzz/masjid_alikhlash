<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('campaigns', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete();
            $table->text('excerpt')->nullable();
            $table->longText('description')->nullable();
            $table->string('cover')->nullable();
            $table->decimal('target', 15, 2)->default(0);
            $table->decimal('collected', 15, 2)->default(0);
            $table->date('start_date')->nullable();
            $table->date('deadline')->nullable();
            $table->string('status')->default('active');      // active|finished|draft
            $table->boolean('is_featured')->default(false);
            $table->unsignedInteger('views')->default(0);
            $table->timestamps();
        });

        Schema::create('campaign_updates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('campaign_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->text('body');
            $table->string('image')->nullable();
            $table->timestamps();
        });

        Schema::create('payment_channels', function (Blueprint $table) {
            $table->id();
            $table->string('name');                           // BSI, QRIS, Dana, ...
            $table->string('type')->default('transfer');      // transfer|qris|ewallet|tunai|gateway
            $table->string('account_number')->nullable();
            $table->string('account_name')->nullable();
            $table->string('logo')->nullable();
            $table->string('qr_image')->nullable();
            $table->text('instruction')->nullable();
            $table->unsignedInteger('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('donations', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->foreignId('campaign_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('payment_channel_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->string('phone', 30)->nullable();
            $table->string('email')->nullable();
            $table->decimal('amount', 15, 2);
            $table->string('type')->default('infaq');         // infaq|zakat|qurban|wakaf|kotak-amal
            $table->text('message')->nullable();
            $table->boolean('is_anonymous')->default(false);
            $table->string('status')->default('pending');     // pending|paid|failed|expired
            $table->string('proof')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });

        Schema::create('finance_accounts', function (Blueprint $table) {
            $table->id();
            $table->string('name');                           // Kas Masjid, Bank BSI, ...
            $table->string('type')->default('kas');           // kas|bank
            $table->string('number')->nullable();
            $table->decimal('opening_balance', 15, 2)->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->foreignId('finance_account_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('donation_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('type');                           // in|out
            $table->decimal('amount', 15, 2);
            $table->date('date')->index();
            $table->string('description');
            $table->string('proof')->nullable();
            $table->string('status')->default('approved');    // draft|pending|approved|rejected
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
        Schema::dropIfExists('finance_accounts');
        Schema::dropIfExists('donations');
        Schema::dropIfExists('payment_channels');
        Schema::dropIfExists('campaign_updates');
        Schema::dropIfExists('campaigns');
    }
};
