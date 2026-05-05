<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Rental;
use App\Models\Item;
use App\Models\User;
use Carbon\Carbon;

/**
 * Peta Item (urutan sesuai ItemSeeder):
 *   [0] Kamera Canon   → owner: Budi   (users[0])
 *   [1] Drone DJI      → owner: Budi   (users[0])
 *   [2] Laptop ASUS    → owner: Siti   (users[1])
 *   [3] Speaker JBL    → owner: Siti   (users[1])
 *   [4] Sepeda MTB     → owner: Ahmad  (users[2])
 *   [5] Proyektor      → owner: Ahmad  (users[2])
 *   [6] Tenda Camping  → owner: Dewi   (users[3])
 *   [7] Gimbal DJI     → owner: Dewi   (users[3])
 *   [8] Kalkulator     → owner: Rizky  (users[4])
 *   [9] Mic Condenser  → owner: Rizky  (users[4])
 *
 * Akun demo utama: Budi (users[0]) = user@ipbrental.ac.id
 *
 * BUDI SEBAGAI PENYEWA — semua status ditampilkan:
 *   finished+rated   → Laptop (item 2, owner Siti)
 *   finished+unrated → Speaker (item 3, owner Siti)   ← form rating muncul
 *   active           → Gimbal (item 7, owner Dewi)    ← sedang berlangsung
 *   dp_paid          → Proyektor (item 5, owner Ahmad) ← tombol upload DP muncul
 *   pending          → Kalkulator (item 8, owner Rizky) ← tombol batalkan muncul
 *   cancelled        → Mic Condenser (item 9, owner Rizky)
 *
 * BUDI SEBAGAI PEMILIK — semua status ditampilkan:
 *   pending          → Kamera (item 0) disewa Siti     ← tombol terima/tolak muncul
 *   dp_paid          → Kamera (item 0) disewa Ahmad    ← menunggu penyewa upload DP
 *   active           → Drone (item 1) disewa Dewi      ← tombol tandai selesai muncul
 *   finished+rated   → Drone (item 1) disewa Rizky
 */
class RentalSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::where('role', 'user')->get();
        $items = Item::orderBy('id')->get();

        // ──────────────────────────────────────────────────────────────
        // BAGIAN A: Rental yang melibatkan Budi (akun demo utama)
        // ──────────────────────────────────────────────────────────────

        // A1. Budi MENYEWA — status: finished + ada rating
        $this->make($items[2], $users[0], $users[1], '-30 days', 4, 'finished', 5,
            'Laptop ROG-nya kenceng banget, editing video jadi lancar! Pemiliknya juga responsif dan baik.');

        // A2. Budi MENYEWA — status: finished + BELUM ada rating (form rating akan tampil)
        $this->make($items[3], $users[0], $users[1], '-14 days', 2, 'finished', null, null);

        // A3. Budi MENYEWA — status: active (sedang berlangsung)
        $this->make($items[7], $users[0], $users[3], '-2 days', 5, 'active', null, null);

        // A4. Budi MENYEWA — status: dp_paid (tombol upload bukti DP muncul untuk Budi)
        $this->make($items[5], $users[0], $users[2], 'today', 3, 'dp_paid', null, null);

        // A5. Budi MENYEWA — status: pending (tombol batalkan muncul untuk Budi)
        $this->make($items[8], $users[0], $users[4], '+3 days', 2, 'pending', null, null);

        // A6. Budi MENYEWA — status: cancelled (riwayat dibatalkan)
        $this->make($items[9], $users[0], $users[4], '-10 days', 2, 'cancelled', null, null);

        // A7. Budi SEBAGAI PEMILIK — status: pending (tombol terima/tolak muncul untuk Budi)
        $this->make($items[0], $users[1], $users[0], '+2 days', 3, 'pending', null, null);

        // A8. Budi SEBAGAI PEMILIK — status: dp_paid (menunggu penyewa upload DP)
        $this->make($items[0], $users[2], $users[0], '+5 days', 2, 'dp_paid', null, null);

        // A9. Budi SEBAGAI PEMILIK — status: active (tombol tandai selesai muncul untuk Budi)
        $this->make($items[1], $users[3], $users[0], '-3 days', 6, 'active', null, null);

        // A10. Budi SEBAGAI PEMILIK — status: finished + ada rating
        $this->make($items[1], $users[4], $users[0], '-20 days', 3, 'finished', 5,
            'Drone-nya terbang mulus, foto 4K jernih banget. Highly recommended!');

        // ──────────────────────────────────────────────────────────────
        // BAGIAN B: Rental antar user lain (data tambahan untuk admin)
        // ──────────────────────────────────────────────────────────────

        // B1. Siti menyewa Sepeda dari Ahmad — finished + rated
        $this->make($items[4], $users[1], $users[2], '-25 days', 3, 'finished', 4,
            'Sepeda kondisi baik, ban tidak kempis. Cocok untuk gowes santai di kampus.');

        // B2. Ahmad menyewa Tenda dari Dewi — finished + rated
        $this->make($items[6], $users[2], $users[3], '-18 days', 4, 'finished', 5,
            'Tenda kokoh dan gampang dipasang. Waterproof beneran, hujan deras pun aman!');

        // B3. Dewi menyewa Kalkulator dari Rizky — finished + rated
        $this->make($items[8], $users[3], $users[4], '-12 days', 5, 'finished', 5,
            'Fitur lengkap banget, sangat membantu saat ujian statistika.');

        // B4. Rizky menyewa Laptop dari Siti — finished + rated
        $this->make($items[2], $users[4], $users[1], '-35 days', 2, 'finished', 4,
            'Laptop gaming kencang, oke untuk render video pendek.');

        // B5. Siti menyewa Mic dari Rizky — active
        $this->make($items[9], $users[1], $users[4], '-1 days', 3, 'active', null, null);

        // B6. Ahmad menyewa Speaker dari Siti — dp_paid
        $this->make($items[3], $users[2], $users[1], 'today', 2, 'dp_paid', null, null);

        // B7. Dewi menyewa Proyektor dari Ahmad — pending
        $this->make($items[5], $users[3], $users[2], '+4 days', 3, 'pending', null, null);

        // B8. Rizky menyewa Kamera dari Budi — finished + rated
        $this->make($items[0], $users[4], $users[0], '-40 days', 3, 'finished', 4,
            'Kamera DSLR bagus, gambar tajam. Pemiliknya ramah dan helpful.');

        // B9. Ahmad menyewa Gimbal dari Dewi — cancelled
        $this->make($items[7], $users[2], $users[3], '-8 days', 2, 'cancelled', null, null);

        // B10. Siti menyewa Tenda dari Dewi — finished + unrated
        $this->make($items[6], $users[1], $users[3], '-7 days', 3, 'finished', null, null);
    }

    private function make(
        $item, $penyewa, $pemilik,
        string $startStr, int $dur,
        string $status, ?int $rating, ?string $ulasan
    ): void {
        $mulai   = Carbon::parse($startStr)->startOfDay();
        $selesai = $mulai->copy()->addDays($dur);
        $total   = $item->harga_per_hari * $dur + $item->deposit;

        Rental::create([
            'item_id'         => $item->id,
            'penyewa_id'      => $penyewa->id,
            'pemilik_id'      => $pemilik->id,
            'tanggal_mulai'   => $mulai,
            'tanggal_selesai' => $selesai,
            'durasi_hari'     => $dur,
            'total_harga'     => $total,
            'deposit'         => $item->deposit,
            'status'          => $status,
            'rating'          => $rating,
            'ulasan'          => $ulasan,
        ]);
    }
}
