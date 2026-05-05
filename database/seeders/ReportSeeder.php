<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Report;
use App\Models\Rental;
use App\Models\User;

/**
 * Tiga laporan mencakup semua status yang ada:
 *   pending   → Budi melaporkan, belum ditangani admin        (demo: admin bisa langsung proses)
 *   diproses  → Siti melaporkan, admin sudah balas            (demo: admin sudah merespons)
 *   selesai   → Ahmad melaporkan, sudah tuntas                (demo: riwayat selesai)
 */
class ReportSeeder extends Seeder
{
    public function run(): void
    {
        $users   = User::where('role', 'user')->orderBy('id')->get();
        $budi    = $users[0];
        $siti    = $users[1];
        $ahmad   = $users[2];
        $dewi    = $users[3];
        $rizky   = $users[4];

        // Rental finished yang bisa dikaitkan ke laporan
        $rentalBudi  = Rental::where('penyewa_id', $budi->id)->where('status', 'finished')->first();
        $rentalSiti  = Rental::where('penyewa_id', $siti->id)->where('status', 'finished')->first();
        $rentalAhmad = Rental::where('penyewa_id', $ahmad->id)->where('status', 'finished')->first();

        // ── Laporan 1: Budi melaporkan — status: PENDING (belum ditangani)
        // Cocok untuk demo admin: tombol "Proses" masih tersedia
        Report::create([
            'reporter_id'   => $budi->id,
            'terlapor_id'   => $siti->id,
            'rental_id'     => $rentalBudi?->id,
            'kategori'      => 'tidak_sesuai',
            'deskripsi'     => 'Speaker yang saya terima kondisinya berbeda dengan foto di listing. Salah satu driver tweeter berbunyi sember dan tidak disebutkan sebelumnya. Saya sudah mencoba menghubungi pemilik namun belum ada respons.',
            'status'        => 'pending',
            'balasan_admin' => null,
        ]);

        // ── Laporan 2: Siti melaporkan — status: DIPROSES (admin sudah merespons)
        // Cocok untuk demo: memperlihatkan balasan admin tersimpan
        Report::create([
            'reporter_id'   => $siti->id,
            'terlapor_id'   => $ahmad->id,
            'rental_id'     => $rentalSiti?->id,
            'kategori'      => 'keterlambatan',
            'deskripsi'     => 'Pemilik item sangat lambat merespons konfirmasi sewa. Saya menunggu konfirmasi lebih dari 3 hari padahal sudah menghubungi via chat. Ini menyebabkan rencana acara saya terganggu.',
            'status'        => 'diproses',
            'balasan_admin' => 'Terima kasih atas laporannya. Kami telah menghubungi pemilik item dan mengingatkan kewajiban untuk merespons permintaan sewa dalam 24 jam. Jika masalah berlanjut, mohon informasikan kembali.',
        ]);

        // ── Laporan 3: Ahmad melaporkan — status: SELESAI (sudah tuntas)
        // Cocok untuk demo: memperlihatkan riwayat laporan yang resolved
        Report::create([
            'reporter_id'   => $ahmad->id,
            'terlapor_id'   => null,
            'rental_id'     => null,
            'kategori'      => 'penipuan',
            'deskripsi'     => 'Saya mendapati ada pengguna yang menawarkan sewa di luar platform melalui DM Instagram dengan harga lebih murah, mengatasnamakan salah satu pemilik item di aplikasi ini. Diduga akun palsu/penipu.',
            'status'        => 'selesai',
            'balasan_admin' => 'Laporan telah kami investigasi. Akun yang dimaksud telah kami nonaktifkan dan dilaporkan ke pihak terkait. Kami menghimbau seluruh pengguna untuk hanya bertransaksi melalui platform resmi IPB Rental. Terima kasih atas kepeduliannya.',
        ]);
    }
}
