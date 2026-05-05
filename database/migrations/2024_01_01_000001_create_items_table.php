<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('nama');
            $table->text('deskripsi')->nullable();
            $table->enum('kategori', ['elektronik','fotografi','audio','drone','akademik','olahraga','perabot','kendaraan','lainnya'])->default('lainnya');
            $table->integer('harga_per_hari');
            $table->integer('deposit')->default(0);
            $table->integer('stok')->default(1);
            $table->enum('status', ['aktif','nonaktif','habis'])->default('aktif');
            $table->json('foto')->nullable();
            $table->decimal('rating_avg', 3, 1)->default(0);
            $table->integer('total_sewa')->default(0);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
