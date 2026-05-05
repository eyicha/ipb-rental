<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reporter_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('terlapor_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('item_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('rental_id')->nullable()->constrained()->onDelete('set null');
            $table->enum('kategori', ['penipuan','barang_rusak','tidak_sesuai','keterlambatan','lainnya'])->default('lainnya');
            $table->string('judul')->nullable();
            $table->text('deskripsi');
            $table->json('bukti')->nullable();
            $table->enum('status', ['pending','diproses','selesai'])->default('pending');
            $table->text('balasan_admin')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
