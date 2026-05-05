<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('email_verifications', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique(); // email @apps.ipb.ac.id yang diizinkan
            $table->string('nim')->nullable(); // NIM kampus
            $table->string('name')->nullable(); // Nama lengkap
            $table->boolean('is_verified')->default(false); // Sudah verified atau belum
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('email_verifications');
    }
};
