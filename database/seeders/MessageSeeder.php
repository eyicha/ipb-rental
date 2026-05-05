<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Message;
use App\Models\User;
use Carbon\Carbon;

/**
 * Percakapan yang dibuat berpusat pada Budi (users[0]) agar
 * halaman Chat terasa hidup saat demo dengan akun utama.
 *
 * Pesan terakhir dari Siti ke Budi sengaja di-set is_read=false
 * sehingga badge notifikasi merah muncul di navbar Chat.
 */
class MessageSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::where('role', 'user')->orderBy('id')->get();

        $budi  = $users[0]; // user@ipbrental.ac.id
        $siti  = $users[1];
        $ahmad = $users[2];
        $dewi  = $users[3];
        $rizky = $users[4];

        // ──────────────────────────────────────────────────────────────
        // Percakapan 1: Budi ↔ Siti — negosiasi sewa Laptop ROG
        // Pesan terakhir (dari Siti) sengaja belum dibaca → badge merah
        // ──────────────────────────────────────────────────────────────
        $this->msg($budi,  $siti,  'Halo Kak Siti, laptop ROG-nya masih tersedia tidak minggu ini?',             Carbon::now()->subDays(5)->subMinutes(30));
        $this->msg($siti,  $budi,  'Halo Mas Budi! Masih tersedia kok. Mau dipakai buat apa?',                   Carbon::now()->subDays(5)->subMinutes(25));
        $this->msg($budi,  $siti,  'Mau buat editing video tugas akhir, kira-kira 4 hari boleh?',               Carbon::now()->subDays(5)->subMinutes(20));
        $this->msg($siti,  $budi,  'Boleh banget! Silakan ajukan sewa lewat aplikasi ya, nanti saya konfirmasi.',Carbon::now()->subDays(5)->subMinutes(15));
        $this->msg($budi,  $siti,  'Siap, sudah saya ajukan. Terima kasih banyak Kak!',                          Carbon::now()->subDays(5)->subMinutes(10));
        $this->msg($siti,  $budi,  'Oke, sudah saya terima. Silakan transfer DP sesuai nominal di aplikasi ya.',  Carbon::now()->subDays(5)->subMinutes(5));
        $this->msg($budi,  $siti,  'Siap Kak! Nanti langsung saya kirim buktinya.',                               Carbon::now()->subDays(5)->subMinutes(2));
        // Pesan terakhir dari Siti → belum dibaca (memunculkan badge merah)
        $this->msg($siti,  $budi,  'Oke ditunggu ya Mas. Kalau ada pertanyaan feel free tanya sini 😊',           Carbon::now()->subDays(5)->subMinute(), false);

        // ──────────────────────────────────────────────────────────────
        // Percakapan 2: Budi ↔ Ahmad — tanya stok & konfirmasi Proyektor
        // ──────────────────────────────────────────────────────────────
        $this->msg($budi,  $ahmad, 'Mas Ahmad, proyektornya bisa dilihat langsung tidak sebelum sewa?',           Carbon::now()->subDays(3)->subMinutes(20));
        $this->msg($ahmad, $budi,  'Bisa dong! Saya di asrama B3 kamar 204. Bisa datang kapan saja jam 08–20.',   Carbon::now()->subDays(3)->subMinutes(18));
        $this->msg($budi,  $ahmad, 'Sip, nanti sore saya mampir ya. Kecerahan proyektornya cukup untuk ruangan ukuran sedang?', Carbon::now()->subDays(3)->subMinutes(10));
        $this->msg($ahmad, $budi,  '3500 lumens, cukup bahkan di ruangan dengan sedikit cahaya. Pasti memuaskan!', Carbon::now()->subDays(3)->subMinutes(8));
        $this->msg($budi,  $ahmad, 'Mantap, oke saya ajukan sekarang. Terima kasih infonya Mas!',                 Carbon::now()->subDays(3)->subMinutes(3));

        // ──────────────────────────────────────────────────────────────
        // Percakapan 3: Budi ↔ Dewi — tanya Gimbal yang sedang aktif disewa
        // ──────────────────────────────────────────────────────────────
        $this->msg($budi,  $dewi,  'Kak Dewi, gimbal DJI OM5-nya masih tersedia minggu depan?',                  Carbon::now()->subDays(7)->subMinutes(15));
        $this->msg($dewi,  $budi,  'Hai! Masih tersedia. Untuk keperluan apa?',                                   Carbon::now()->subDays(7)->subMinutes(12));
        $this->msg($budi,  $dewi,  'Mau bikin konten Instagram, butuh footage yang smooth.',                      Carbon::now()->subDays(7)->subMinutes(10));
        $this->msg($dewi,  $budi,  'Cocok banget! OM5 hasilnya super smooth. Mau berapa hari?',                   Carbon::now()->subDays(7)->subMinutes(8));
        $this->msg($budi,  $dewi,  'Sekitar 5 hari. Boleh ya Kak?',                                              Carbon::now()->subDays(7)->subMinutes(5));
        $this->msg($dewi,  $budi,  'Boleh, langsung ajukan saja. Nanti saya proses segera!',                      Carbon::now()->subDays(7)->subMinutes(2));
        $this->msg($budi,  $dewi,  'Sudah diajukan Kak, terima kasih! 🙏',                                       Carbon::now()->subDays(7)->subMinute());

        // ──────────────────────────────────────────────────────────────
        // Percakapan 4: Siti ↔ Ahmad — tanya kondisi Sepeda
        // (tidak melibatkan Budi, memperkaya data admin)
        // ──────────────────────────────────────────────────────────────
        $this->msg($siti,  $ahmad, 'Mas Ahmad, sepedanya ukuran berapa? Tinggi badan saya 158cm.',                Carbon::now()->subDays(10)->subMinutes(20));
        $this->msg($ahmad, $siti,  'Frame 15 inch, cocok untuk tinggi 150–165cm. Posisi sadel bisa disesuaikan.', Carbon::now()->subDays(10)->subMinutes(17));
        $this->msg($siti,  $ahmad, 'Perfect! Oke saya ajukan sekarang ya.',                                       Carbon::now()->subDays(10)->subMinutes(15));
        $this->msg($ahmad, $siti,  'Siap, ditunggu ya konfirmasinya.',                                            Carbon::now()->subDays(10)->subMinutes(13));

        // ──────────────────────────────────────────────────────────────
        // Percakapan 5: Dewi ↔ Rizky — tanya Mic Condenser
        // ──────────────────────────────────────────────────────────────
        $this->msg($dewi,  $rizky, 'Kak, Rode NT1-A ini butuh phantom power ya?',                                 Carbon::now()->subDays(4)->subMinutes(10));
        $this->msg($rizky, $dewi,  'Betul, butuh phantom power 48V. Sudah punya audio interface?',                 Carbon::now()->subDays(4)->subMinutes(8));
        $this->msg($dewi,  $rizky, 'Ada Focusrite Scarlett Solo, kompatibel?',                                    Carbon::now()->subDays(4)->subMinutes(5));
        $this->msg($rizky, $dewi,  'Kompatibel 100%! Langsung order saja, kualitas rekaman dijamin jernih.',       Carbon::now()->subDays(4)->subMinutes(2));
    }

    private function msg(User $sender, User $receiver, string $pesan, Carbon $time, bool $isRead = true): void
    {
        Message::create([
            'sender_id'   => $sender->id,
            'receiver_id' => $receiver->id,
            'pesan'       => $pesan,
            'is_read'     => $isRead,
            'created_at'  => $time,
            'updated_at'  => $time,
        ]);
    }
}
