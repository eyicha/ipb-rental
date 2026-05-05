<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rentals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id')->constrained()->onDelete('cascade');
            $table->foreignId('penyewa_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('pemilik_id')->constrained('users')->onDelete('cascade');
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->integer('durasi_hari');
            $table->integer('total_harga');
            $table->integer('deposit')->default(0);
            $table->enum('status', ['pending','dp_paid','active','finished','cancelled'])->default('pending');
            $table->text('catatan')->nullable();
            $table->string('bukti_dp')->nullable();
            $table->integer('rating')->nullable();
            $table->text('ulasan')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rentals');
    }
};
