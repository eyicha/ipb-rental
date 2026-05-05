<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Verification;
use App\Models\User;

/**
 * Tiga entri verifikasi mencakup semua status yang ada:
 *   pending  → Siti baru saja mengirim KTM, belum diproses admin    (demo: admin bisa Verifikasi / Tolak)
 *   verified → Ahmad sudah lolos verifikasi                          (demo: riwayat verified)
 *   rejected → Dewi pernah ditolak (file buram)                     (demo: riwayat rejected)
 *
 * File placeholder diset ke path 'verifications/sample_*.jpg' —
 * file fisiknya tidak harus ada; seeder hanya menyimpan path-string di DB.
 */
class VerificationSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::where('role', 'user')->orderBy('id')->get();

        // users[0] = Budi  → tidak memiliki verifikasi (umum: penyewa baru)
        $siti  = $users[1]; // users[1]
        $ahmad = $users[2]; // users[2]
        $dewi  = $users[3]; // users[3]

        // ── Verifikasi 1: Siti — status: PENDING (belum diproses admin)
        // Cocok untuk demo: tombol "Verifikasi" dan "Tolak" masih aktif di halaman admin
        Verification::create([
            'user_id' => $siti->id,
            'tipe'    => 'ktm',
            'file'    => 'verifications/sample_ktm_siti.jpg',
            'status'  => 'pending',
        ]);

        // ── Verifikasi 2: Ahmad — status: VERIFIED (sudah disetujui)
        // Cocok untuk demo: memperlihatkan entri yang sudah diproses positif
        Verification::create([
            'user_id' => $ahmad->id,
            'tipe'    => 'ktm',
            'file'    => 'verifications/sample_ktm_ahmad.jpg',
            'status'  => 'verified',
        ]);

        // ── Verifikasi 3: Dewi — status: REJECTED (ditolak, file tidak jelas)
        // Cocok untuk demo: memperlihatkan riwayat yang ditolak
        Verification::create([
            'user_id' => $dewi->id,
            'tipe'    => 'ktp',
            'file'    => 'verifications/sample_ktp_dewi.jpg',
            'status'  => 'rejected',
        ]);
    }
}
