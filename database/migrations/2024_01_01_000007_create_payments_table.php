<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add status field untuk payment/transaction
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rental_id')->constrained()->onDelete('cascade');
            $table->integer('amount'); // Jumlah pembayaran (DP)
            $table->enum('type', ['dp', 'pelunasan'])->default('dp'); // DP atau pelunasan
            $table->enum('status', ['pending', 'success', 'failed', 'expired'])->default('pending');
            $table->string('transaction_id')->nullable(); // ID dari payment gateway
            $table->string('payment_method')->nullable(); // Transfer bank, e-wallet, dll
            $table->timestamp('paid_at')->nullable();
            $table->string('proof_url')->nullable(); // Bukti pembayaran manual
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
