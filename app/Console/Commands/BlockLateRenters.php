<?php

namespace App\Console\Commands;

use App\Models\Message;
use App\Models\Rental;
use App\Models\Report;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;

class BlockLateRenters extends Command
{
    protected $signature   = 'rentals:block-late {--dry-run : Tampilkan tanpa eksekusi}';
    protected $description = 'Blokir penyewa yang melewati tanggal selesai sewa';

    public function handle(): void
    {
        $overdueRentals = Rental::where('status', 'active')
            ->where('tanggal_selesai', '<', Carbon::today())
            ->with(['penyewa', 'item'])
            ->get();

        if ($overdueRentals->isEmpty()) {
            $this->info('Tidak ada rental yang terlambat.');
            return;
        }

        $admin = User::where('role', 'admin')->first();

        foreach ($overdueRentals as $rental) {
            $penyewa = $rental->penyewa;

            $this->line("Rental #{$rental->id} — {$penyewa->name} — lewat: {$rental->tanggal_selesai}");

            if ($this->option('dry-run')) continue;

            // Blokir penyewa
            $penyewa->update([
                'is_blocked'     => true,
                'blocked_reason' => 'Telat mengembalikan barang rental #' . $rental->id,
            ]);

            // Update status rental
            $rental->update(['status' => 'overdue']);

            // Pesan otomatis dari admin ke penyewa
            if ($admin) {
                Message::create([
                    'sender_id'   => $admin->id,
                    'receiver_id' => $penyewa->id,
                    'rental_id'   => $rental->id,
                    'pesan'       => '[SISTEM] Akun kamu diblokir sementara karena belum mengembalikan "' .
                                     $rental->item->nama . '" (Rental #' . $rental->id . ') yang jatuh tempo ' .
                                     Carbon::parse($rental->tanggal_selesai)->format('d M Y') . '. ' .
                                     'Buat laporan di menu Report → "Pengembalian Terlambat" untuk klarifikasi dengan admin.',
                ]);
            }

            // Buat Report otomatis
            Report::firstOrCreate(
                ['rental_id' => $rental->id, 'kategori' => 'keterlambatan'],
                [
                    'reporter_id' => $rental->pemilik_id,
                    'terlapor_id' => $penyewa->id,
                    'item_id'     => $rental->item_id,
                    'deskripsi'   => 'Penyewa ' . $penyewa->name . ' belum mengembalikan "' .
                                     $rental->item->nama . '" setelah jatuh tempo ' .
                                     Carbon::parse($rental->tanggal_selesai)->format('d M Y') . '.',
                    'bukti'       => [],
                    'status'      => 'diproses',
                ]
            );

            $this->info("✓ {$penyewa->name} diblokir.");
        }

        $this->info('Selesai.');
    }
}