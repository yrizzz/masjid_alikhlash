<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inventories', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete();
            $table->unsignedInteger('quantity')->default(1);
            $table->string('unit', 30)->default('unit');
            $table->string('condition')->default('baik');     // baik|rusak-ringan|rusak-berat|hilang
            $table->date('purchase_date')->nullable();
            $table->decimal('price', 15, 2)->default(0);
            $table->string('location')->nullable();
            $table->string('photo')->nullable();
            $table->text('note')->nullable();
            $table->boolean('is_lendable')->default(false);
            $table->timestamps();
        });

        Schema::create('inventory_maintenances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inventory_id')->constrained()->cascadeOnDelete();
            $table->date('date');
            $table->string('type')->default('perawatan');     // perawatan|perbaikan|penggantian
            $table->text('description')->nullable();
            $table->decimal('cost', 15, 2)->default(0);
            $table->string('vendor')->nullable();
            $table->date('next_due')->nullable();
            $table->timestamps();
        });

        Schema::create('inventory_loans', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->foreignId('inventory_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('borrower');
            $table->string('phone', 30)->nullable();
            $table->unsignedInteger('quantity')->default(1);
            $table->date('borrow_date');
            $table->date('due_date');
            $table->date('returned_at')->nullable();
            $table->text('purpose')->nullable();
            $table->string('status')->default('pending');     // pending|approved|rejected|returned
            $table->text('admin_note')->nullable();
            $table->timestamps();
        });

        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->unsignedInteger('capacity')->default(0);
            $table->text('description')->nullable();
            $table->string('photo')->nullable();
            $table->text('facilities')->nullable();
            $table->decimal('fee', 15, 2)->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('room_bookings', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->foreignId('room_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->string('phone', 30)->nullable();
            $table->string('purpose');                        // Nikah, Rapat, Kajian, TPQ
            $table->date('date');
            $table->time('start_time');
            $table->time('end_time');
            $table->unsignedInteger('participants')->default(0);
            $table->text('note')->nullable();
            $table->string('status')->default('pending');     // pending|approved|rejected|done
            $table->text('admin_note')->nullable();
            $table->timestamps();
        });

        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name')->nullable();
            $table->string('context')->default('kajian');     // kajian|volunteer|tpq|jumat
            $table->nullableMorphs('attendable');
            $table->timestamp('checked_in_at');
            $table->string('method', 20)->default('qr');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendances');
        Schema::dropIfExists('room_bookings');
        Schema::dropIfExists('rooms');
        Schema::dropIfExists('inventory_loans');
        Schema::dropIfExists('inventory_maintenances');
        Schema::dropIfExists('inventories');
    }
};
