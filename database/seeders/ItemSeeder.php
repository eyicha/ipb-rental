<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Item;
use App\Models\User;

/**
 * 50 items — setiap item memiliki foto unik dari public/images/items/.
 *
 * Urutan 10 item pertama (0–9) TIDAK BOLEH diubah karena RentalSeeder
 * mereferensikannya dengan index ($items[0] … $items[9]).
 *
 * Index  Nama                         Kategori    Owner
 * ─────────────────────────────────────────────────────────
 *   0    Kamera Canon EOS 200D        fotografi   users[0] Budi
 *   1    Drone DJI Mini 2             drone       users[0] Budi
 *   2    Laptop ASUS ROG Gaming       elektronik  users[1] Siti
 *   3    Speaker JBL Charge 5         audio       users[1] Siti
 *   4    Sepeda MTB Trek Marlin 5     olahraga    users[2] Ahmad
 *   5    Proyektor Mini Portable      elektronik  users[2] Ahmad
 *   6    Tenda Camping 4 Orang        olahraga    users[3] Dewi
 *   7    Stabilizer Gimbal DJI OM 5   fotografi   users[3] Dewi
 *   8    Kalkulator Casio fx-991EX    akademik    users[4] Rizky
 *   9    Mic Condenser Rode NT1-A     audio       users[4] Rizky
 */
class ItemSeeder extends Seeder
{
    public function run(): void
    {
        $u = User::where('role', 'user')->get(); // u[0]=Budi u[1]=Siti u[2]=Ahmad u[3]=Dewi u[4]=Rizky

        $items = [

            /* ── 0 ── */
            [
                'user_id' => $u[0]->id, 'nama' => 'Kamera Canon EOS 200D',
                'deskripsi' => 'Kamera DSLR entry-level Canon EOS 200D, kondisi baik. Termasuk lensa kit 18-55mm. Cocok untuk fotografi landscape, portrait, dan vlog.',
                'kategori' => 'fotografi', 'harga_per_hari' => 85000, 'deposit' => 200000,
                'stok' => 1, 'status' => 'aktif', 'rating_avg' => 4.8, 'total_sewa' => 12,
                'foto' => ['images/items/foto-kamera-dslr.jpg'],
            ],

            /* ── 1 ── */
            [
                'user_id' => $u[0]->id, 'nama' => 'Drone DJI Mini 2',
                'deskripsi' => 'Drone DJI Mini 2 dengan kamera 4K. Ringan dan mudah dibawa. Baterai 3 unit included. Sempurna untuk dokumentasi acara dan konten media sosial.',
                'kategori' => 'drone', 'harga_per_hari' => 200000, 'deposit' => 500000,
                'stok' => 1, 'status' => 'aktif', 'rating_avg' => 4.9, 'total_sewa' => 8,
                'foto' => ['images/items/drone-dji-mini.jpg'],
            ],

            /* ── 2 ── */
            [
                'user_id' => $u[1]->id, 'nama' => 'Laptop ASUS ROG Gaming',
                'deskripsi' => 'Laptop gaming ASUS ROG Strix G15, AMD Ryzen 7, RAM 16GB, SSD 512GB, GPU RTX 3060. Cocok untuk editing video, gaming, dan rendering.',
                'kategori' => 'elektronik', 'harga_per_hari' => 120000, 'deposit' => 300000,
                'stok' => 1, 'status' => 'aktif', 'rating_avg' => 4.7, 'total_sewa' => 15,
                'foto' => ['images/items/elektronik-laptop-gaming.jpg'],
            ],

            /* ── 3 ── */
            [
                'user_id' => $u[1]->id, 'nama' => 'Speaker Bluetooth JBL Charge 5',
                'deskripsi' => 'Speaker portable JBL Charge 5, waterproof IPX7, bass kencang. Baterai tahan 20 jam. Ideal untuk outdoor, acara, atau studio mini.',
                'kategori' => 'audio', 'harga_per_hari' => 35000, 'deposit' => 100000,
                'stok' => 2, 'status' => 'aktif', 'rating_avg' => 4.6, 'total_sewa' => 20,
                'foto' => ['images/items/audio-speaker-bluetooth.jpg'],
            ],

            /* ── 4 ── */
            [
                'user_id' => $u[2]->id, 'nama' => 'Sepeda MTB Trek Marlin 5',
                'deskripsi' => 'Sepeda gunung Trek Marlin 5 ukuran 29 inch. Kondisi terawat, ban baru. Cocok untuk bersepeda santai di area kampus atau trail ringan.',
                'kategori' => 'olahraga', 'harga_per_hari' => 50000, 'deposit' => 150000,
                'stok' => 1, 'status' => 'aktif', 'rating_avg' => 4.5, 'total_sewa' => 9,
                'foto' => ['images/items/olahraga-sepeda-road.jpg'],
            ],

            /* ── 5 ── */
            [
                'user_id' => $u[2]->id, 'nama' => 'Proyektor Mini Portable',
                'deskripsi' => 'Proyektor portable 1080p, brightness 3500 lumens. Mendukung HDMI dan wireless screen mirroring. Ideal untuk presentasi, nonton bareng, atau seminar.',
                'kategori' => 'elektronik', 'harga_per_hari' => 75000, 'deposit' => 200000,
                'stok' => 1, 'status' => 'aktif', 'rating_avg' => 4.4, 'total_sewa' => 7,
                'foto' => ['images/items/elektronik-proyektor-mini.jpg'],
            ],

            /* ── 6 ── */
            [
                'user_id' => $u[3]->id, 'nama' => 'Tenda Camping 4 Orang',
                'deskripsi' => 'Tenda dome kapasitas 4 orang, waterproof, mudah dipasang. Termasuk groundsheet dan pasak. Cocok untuk camping, hiking, atau keperluan outdoor.',
                'kategori' => 'olahraga', 'harga_per_hari' => 45000, 'deposit' => 100000,
                'stok' => 2, 'status' => 'aktif', 'rating_avg' => 4.3, 'total_sewa' => 6,
                'foto' => ['images/items/tenda-camping.jpg'],
            ],

            /* ── 7 ── */
            [
                'user_id' => $u[3]->id, 'nama' => 'Stabilizer Gimbal DJI OM 5',
                'deskripsi' => 'Gimbal smartphone DJI OM 5, 3-axis stabilization, magnetic attach. Baterai tahan 6.4 jam. Perfect untuk konten creator dan vlogging.',
                'kategori' => 'fotografi', 'harga_per_hari' => 55000, 'deposit' => 150000,
                'stok' => 1, 'status' => 'aktif', 'rating_avg' => 4.7, 'total_sewa' => 11,
                'foto' => ['images/items/gimbal-dji.jpg'],
            ],

            /* ── 8 ── */
            [
                'user_id' => $u[4]->id, 'nama' => 'Kalkulator Casio fx-991EX',
                'deskripsi' => 'Kalkulator scientific Casio ClassWiz fx-991EX. 552 fungsi, spreadsheet mode. Wajib untuk praktikum sains, statistika, dan matematika tingkat lanjut.',
                'kategori' => 'akademik', 'harga_per_hari' => 10000, 'deposit' => 30000,
                'stok' => 3, 'status' => 'aktif', 'rating_avg' => 4.2, 'total_sewa' => 25,
                'foto' => ['images/items/akademik-kalkulator-scientific.jpg'],
            ],

            /* ── 9 ── */
            [
                'user_id' => $u[4]->id, 'nama' => 'Mic Condenser Rode NT1-A',
                'deskripsi' => 'Microphone condenser Rode NT1-A, cardioid polar pattern, self-noise 4.5dB. Cocok untuk rekaman podcast, voiceover, instrumen akustik.',
                'kategori' => 'audio', 'harga_per_hari' => 65000, 'deposit' => 200000,
                'stok' => 1, 'status' => 'aktif', 'rating_avg' => 4.8, 'total_sewa' => 10,
                'foto' => ['images/items/audio-microphone-condenser.jpg'],
            ],

            /* ── 10 ── Fotografi */
            [
                'user_id' => $u[0]->id, 'nama' => 'Kamera Sony A6400 Mirrorless',
                'deskripsi' => 'Kamera mirrorless Sony A6400, sensor APS-C 24.2MP, autofokus real-time eye tracking. Cocok untuk street photography, portrait, dan konten kreator cepat.',
                'kategori' => 'fotografi', 'harga_per_hari' => 100000, 'deposit' => 250000,
                'stok' => 1, 'status' => 'aktif', 'rating_avg' => 4.7, 'total_sewa' => 8,
                'foto' => ['images/items/foto-kamera-mirrorless.jpg'],
            ],

            /* ── 11 ── Fotografi */
            [
                'user_id' => $u[1]->id, 'nama' => 'Lensa Tele Canon EF 70-200mm f/2.8',
                'deskripsi' => 'Lensa tele zoom Canon EF 70-200mm f/2.8L IS III USM. Cocok untuk fotografi olahraga, wildlife, dan portrait dengan bokeh creamy yang indah.',
                'kategori' => 'fotografi', 'harga_per_hari' => 90000, 'deposit' => 300000,
                'stok' => 1, 'status' => 'aktif', 'rating_avg' => 4.9, 'total_sewa' => 5,
                'foto' => ['images/items/foto-lensa-tele.jpg'],
            ],

            /* ── 12 ── Fotografi */
            [
                'user_id' => $u[2]->id, 'nama' => 'Tripod Carbon Fiber Benro',
                'deskripsi' => 'Tripod carbon fiber Benro Series 2, tinggi max 165cm, load capacity 8kg. Lightweight dan kokoh. Cocok untuk foto landscape, long exposure, dan video stabil.',
                'kategori' => 'fotografi', 'harga_per_hari' => 25000, 'deposit' => 80000,
                'stok' => 2, 'status' => 'aktif', 'rating_avg' => 4.4, 'total_sewa' => 13,
                'foto' => ['images/items/foto-tripod.jpg'],
            ],

            /* ── 13 ── Fotografi */
            [
                'user_id' => $u[3]->id, 'nama' => 'GoPro Hero 11 Black',
                'deskripsi' => 'Action camera GoPro Hero 11 Black, video 5.3K/60fps, waterproof hingga 10m. Termasuk mounting kit lengkap. Ideal untuk petualangan outdoor dan vlog ekstrem.',
                'kategori' => 'fotografi', 'harga_per_hari' => 75000, 'deposit' => 200000,
                'stok' => 1, 'status' => 'aktif', 'rating_avg' => 4.8, 'total_sewa' => 7,
                'foto' => ['images/items/foto-gopro.jpg'],
            ],

            /* ── 14 ── Fotografi */
            [
                'user_id' => $u[4]->id, 'nama' => 'Lighting Kit Studio 3 Titik',
                'deskripsi' => 'Set lighting studio 3 titik: key light 150W, fill light 75W, backlight 75W — semuanya LED softbox. Cocok untuk foto produk, portrait studio, dan live streaming.',
                'kategori' => 'fotografi', 'harga_per_hari' => 60000, 'deposit' => 150000,
                'stok' => 1, 'status' => 'aktif', 'rating_avg' => 4.5, 'total_sewa' => 4,
                'foto' => ['images/items/foto-lighting-kit.jpg'],
            ],

            /* ── 15 ── Drone */
            [
                'user_id' => $u[0]->id, 'nama' => 'Drone DJI Air 2S',
                'deskripsi' => 'Drone DJI Air 2S, kamera 1-inch CMOS 20MP, video 5.4K. Obstacle sensing 4 arah, OcuSync 3.0 jangkauan 12km. Ideal untuk videografi semi-profesional.',
                'kategori' => 'drone', 'harga_per_hari' => 250000, 'deposit' => 600000,
                'stok' => 1, 'status' => 'aktif', 'rating_avg' => 4.8, 'total_sewa' => 6,
                'foto' => ['images/items/drone-dji-air.jpg'],
            ],

            /* ── 16 ── Drone */
            [
                'user_id' => $u[1]->id, 'nama' => 'Drone DJI Mavic 3',
                'deskripsi' => 'Drone flagship DJI Mavic 3, dual kamera Hasselblad, video 5.1K/50fps, terbang 46 menit. Obstacle sensing omnidirectional. Kualitas sinematik terbaik di kelasnya.',
                'kategori' => 'drone', 'harga_per_hari' => 350000, 'deposit' => 800000,
                'stok' => 1, 'status' => 'aktif', 'rating_avg' => 5.0, 'total_sewa' => 3,
                'foto' => ['images/items/drone-dji-mavic.jpg'],
            ],

            /* ── 17 ── Drone */
            [
                'user_id' => $u[2]->id, 'nama' => 'Drone FPV Racing Kit DJI',
                'deskripsi' => 'Drone FPV racing DJI dengan goggles V2 dan remote motion controller. Kecepatan max 140km/h, manual mode. Cocok untuk pengguna berpengalaman dan konten adrenalin.',
                'kategori' => 'drone', 'harga_per_hari' => 300000, 'deposit' => 700000,
                'stok' => 1, 'status' => 'aktif', 'rating_avg' => 4.6, 'total_sewa' => 2,
                'foto' => ['images/items/drone-fpv.jpg'],
            ],

            /* ── 18 ── Elektronik */
            [
                'user_id' => $u[3]->id, 'nama' => 'MacBook Pro M2 14"',
                'deskripsi' => 'MacBook Pro 14 inch chip Apple M2 Pro, RAM 16GB, SSD 512GB, layar Liquid Retina XDR. Performa tinggi untuk editing video 4K, desain grafis, dan coding intensif.',
                'kategori' => 'elektronik', 'harga_per_hari' => 150000, 'deposit' => 500000,
                'stok' => 1, 'status' => 'aktif', 'rating_avg' => 4.9, 'total_sewa' => 10,
                'foto' => ['images/items/elektronik-laptop-macbook.jpg'],
            ],

            /* ── 19 ── Elektronik */
            [
                'user_id' => $u[4]->id, 'nama' => 'iPad Pro 12.9" M2',
                'deskripsi' => 'iPad Pro 12.9 inch chip M2, layar Liquid Retina XDR ProMotion 120Hz. Cocok untuk digital art, presentasi interaktif, video call resolusi tinggi, dan produktivitas mobile.',
                'kategori' => 'elektronik', 'harga_per_hari' => 80000, 'deposit' => 300000,
                'stok' => 1, 'status' => 'aktif', 'rating_avg' => 4.7, 'total_sewa' => 9,
                'foto' => ['images/items/elektronik-ipad.jpg'],
            ],

            /* ── 20 ── Elektronik */
            [
                'user_id' => $u[0]->id, 'nama' => 'Monitor Portable 15.6" 4K',
                'deskripsi' => 'Monitor portable 15.6 inch resolusi 4K UHD, koneksi USB-C dan Mini HDMI. Tipis dan ringan. Ideal untuk dual-screen presentation, workstation mobile, dan gaming on-the-go.',
                'kategori' => 'elektronik', 'harga_per_hari' => 45000, 'deposit' => 150000,
                'stok' => 2, 'status' => 'aktif', 'rating_avg' => 4.5, 'total_sewa' => 11,
                'foto' => ['images/items/elektronik-monitor-portable.jpg'],
            ],

            /* ── 21 ── Elektronik */
            [
                'user_id' => $u[1]->id, 'nama' => 'Hard Disk External 2TB WD',
                'deskripsi' => 'Hard disk external WD My Passport 2TB, USB 3.0, enkripsi hardware. Portable dan andal. Cocok untuk backup skripsi, transfer footage video, dan penyimpanan data riset.',
                'kategori' => 'elektronik', 'harga_per_hari' => 15000, 'deposit' => 50000,
                'stok' => 3, 'status' => 'aktif', 'rating_avg' => 4.3, 'total_sewa' => 18,
                'foto' => ['images/items/elektronik-harddisk.jpg'],
            ],

            /* ── 22 ── Elektronik */
            [
                'user_id' => $u[2]->id, 'nama' => 'Proyektor Epson EB-X41',
                'deskripsi' => 'Proyektor Epson EB-X41 XGA 3600 lumen. Termasuk layar portable dan kabel HDMI. Cocok untuk seminar kelas besar, rapat himpunan, dan presentasi kampus resmi.',
                'kategori' => 'elektronik', 'harga_per_hari' => 70000, 'deposit' => 200000,
                'stok' => 2, 'status' => 'aktif', 'rating_avg' => 4.6, 'total_sewa' => 14,
                'foto' => ['images/items/proyektor.jpg'],
            ],

            /* ── 23 ── Audio */
            [
                'user_id' => $u[3]->id, 'nama' => 'Headphone Bose QuietComfort 45',
                'deskripsi' => 'Headphone over-ear Bose QC45, active noise-cancelling terdepan. Baterai 24 jam. Audio jernih untuk studio mixing, sesi belajar fokus, dan perjalanan jauh.',
                'kategori' => 'audio', 'harga_per_hari' => 40000, 'deposit' => 120000,
                'stok' => 1, 'status' => 'aktif', 'rating_avg' => 4.8, 'total_sewa' => 12,
                'foto' => ['images/items/audio-headphone-bose.jpg'],
            ],

            /* ── 24 ── Audio */
            [
                'user_id' => $u[4]->id, 'nama' => 'Headphone Sony WH-1000XM5',
                'deskripsi' => 'Headphone Sony WH-1000XM5, ANC industri terbaik, LDAC hi-res audio. Baterai 30 jam. Nyaman untuk sesi belajar panjang, rekaman studio, dan perjalanan jauh.',
                'kategori' => 'audio', 'harga_per_hari' => 45000, 'deposit' => 130000,
                'stok' => 1, 'status' => 'aktif', 'rating_avg' => 4.9, 'total_sewa' => 7,
                'foto' => ['images/items/audio-headphone-sony.jpg'],
            ],

            /* ── 25 ── Audio */
            [
                'user_id' => $u[0]->id, 'nama' => 'Speaker Marshall Emberton II',
                'deskripsi' => 'Speaker bluetooth Marshall Emberton II, 360° stereo sound, IPX7 waterproof. Baterai 30 jam. Suara khas Marshall yang hangat dan bertenaga — sempurna untuk gathering.',
                'kategori' => 'audio', 'harga_per_hari' => 40000, 'deposit' => 120000,
                'stok' => 1, 'status' => 'aktif', 'rating_avg' => 4.7, 'total_sewa' => 9,
                'foto' => ['images/items/audio-speaker-marshall.jpg'],
            ],

            /* ── 26 ── Audio */
            [
                'user_id' => $u[1]->id, 'nama' => 'Audio Interface Focusrite Scarlett 2i2',
                'deskripsi' => 'Audio interface Focusrite Scarlett 2i2 Gen 3, 2 input XLR/TRS, 24-bit/192kHz. Ideal untuk rekaman vokal, podcast profesional, instrumen, dan home studio berkualitas.',
                'kategori' => 'audio', 'harga_per_hari' => 35000, 'deposit' => 100000,
                'stok' => 1, 'status' => 'aktif', 'rating_avg' => 4.6, 'total_sewa' => 6,
                'foto' => ['images/items/audio-audio-interface.jpg'],
            ],

            /* ── 27 ── Audio */
            [
                'user_id' => $u[2]->id, 'nama' => 'Speaker JBL Boombox 3',
                'deskripsi' => 'Speaker portabel JBL Boombox 3, output 80W, IP67 waterproof & dustproof. Baterai 24 jam. Suara menggelegar untuk pesta outdoor, pentas seni, dan gathering besar.',
                'kategori' => 'audio', 'harga_per_hari' => 55000, 'deposit' => 150000,
                'stok' => 1, 'status' => 'aktif', 'rating_avg' => 4.5, 'total_sewa' => 8,
                'foto' => ['images/items/speaker-jbl.jpg'],
            ],

            /* ── 28 ── Audio */
            [
                'user_id' => $u[3]->id, 'nama' => 'Microphone Condenser Blue Yeti',
                'deskripsi' => 'Mikrofon kondenser USB Blue Yeti, 4 polar pattern. Plug-and-play tanpa driver. Suara kristal jernih untuk podcast, live streaming, Zoom, dan rekaman vokal indie.',
                'kategori' => 'audio', 'harga_per_hari' => 40000, 'deposit' => 120000,
                'stok' => 1, 'status' => 'aktif', 'rating_avg' => 4.7, 'total_sewa' => 11,
                'foto' => ['images/items/mic-condenser.jpg'],
            ],

            /* ── 29 ── Olahraga */
            [
                'user_id' => $u[4]->id, 'nama' => 'Backpack Hiking 60L Deuter Aircontact',
                'deskripsi' => 'Carrier Deuter Aircontact 60+10L, frame aluminium, hipbelt ergonomis, waterproof cover included. Ideal untuk camping gunung, hiking multi-hari, dan perjalanan lapangan.',
                'kategori' => 'olahraga', 'harga_per_hari' => 35000, 'deposit' => 100000,
                'stok' => 2, 'status' => 'aktif', 'rating_avg' => 4.6, 'total_sewa' => 8,
                'foto' => ['images/items/olahraga-camping-backpack.jpg'],
            ],

            /* ── 30 ── Olahraga */
            [
                'user_id' => $u[0]->id, 'nama' => 'Raket Badminton Yonex Astrox 88D',
                'deskripsi' => 'Raket Yonex Astrox 88D Pro, head-heavy balance, nanofiber carbon. Cocok untuk smash powereful. Kondisi prima, sudah di-restring dengan Yonex BG65 tegangan 28lbs.',
                'kategori' => 'olahraga', 'harga_per_hari' => 20000, 'deposit' => 60000,
                'stok' => 2, 'status' => 'aktif', 'rating_avg' => 4.5, 'total_sewa' => 15,
                'foto' => ['images/items/olahraga-raket-badminton.jpg'],
            ],

            /* ── 31 ── Olahraga */
            [
                'user_id' => $u[1]->id, 'nama' => 'Sepeda MTB Polygon Siskiu T8',
                'deskripsi' => 'Sepeda MTB full-suspension Polygon Siskiu T8 29", travel 130mm, rem hydraulic Shimano SLX. Cocok untuk trail menantang, cross-country, dan eksplorasi hutan Dramaga.',
                'kategori' => 'olahraga', 'harga_per_hari' => 70000, 'deposit' => 200000,
                'stok' => 1, 'status' => 'aktif', 'rating_avg' => 4.7, 'total_sewa' => 5,
                'foto' => ['images/items/olahraga-sepeda-mtb.jpg'],
            ],

            /* ── 32 ── Olahraga */
            [
                'user_id' => $u[2]->id, 'nama' => 'Skateboard Longboard 40"',
                'deskripsi' => 'Longboard 40 inch deck maple, trucks aluminium, roda 70mm 78A. Cocok untuk cruising santai di kampus, downhill ringan, dan belajar skateboarding bagi pemula.',
                'kategori' => 'olahraga', 'harga_per_hari' => 25000, 'deposit' => 75000,
                'stok' => 2, 'status' => 'aktif', 'rating_avg' => 4.3, 'total_sewa' => 7,
                'foto' => ['images/items/olahraga-skateboard.jpg'],
            ],

            /* ── 33 ── Olahraga */
            [
                'user_id' => $u[3]->id, 'nama' => 'Raket Tenis Wilson Ultra 100',
                'deskripsi' => 'Raket tenis Wilson Ultra 100 v3.2, 300g, head 100sq.in. Cocok untuk pemain intermediate hingga advanced. Termasuk tas raket dan 1 kaleng bola baru.',
                'kategori' => 'olahraga', 'harga_per_hari' => 25000, 'deposit' => 75000,
                'stok' => 2, 'status' => 'aktif', 'rating_avg' => 4.4, 'total_sewa' => 9,
                'foto' => ['images/items/olahraga-tenis-raket.jpg'],
            ],

            /* ── 34 ── Perabot */
            [
                'user_id' => $u[4]->id, 'nama' => 'Tenda Dome Event 6×6m',
                'deskripsi' => 'Tenda frame 6×6m, terpal 500gsm waterproof, rangka pipa galvanis. Kapasitas 30–40 orang. Ideal untuk bazaar, pameran UKM, dan acara outdoor kampus besar.',
                'kategori' => 'perabot', 'harga_per_hari' => 200000, 'deposit' => 500000,
                'stok' => 1, 'status' => 'aktif', 'rating_avg' => 4.4, 'total_sewa' => 4,
                'foto' => ['images/items/perabot-tenda-dome.jpg.jpeg'],
            ],

            /* ── 35 ── Perabot */
            [
                'user_id' => $u[0]->id, 'nama' => 'Genset Portable 1000W Honda EU10i',
                'deskripsi' => 'Generator portable Honda EU10i 1000W inverter, konsumsi bahan bakar rendah, kebisingan 59dB. Tangki 2.1L tahan 8 jam. Ideal untuk acara outdoor dan power backup.',
                'kategori' => 'perabot', 'harga_per_hari' => 120000, 'deposit' => 350000,
                'stok' => 1, 'status' => 'aktif', 'rating_avg' => 4.5, 'total_sewa' => 3,
                'foto' => ['images/items/perabot-genset.jpg'],
            ],

            /* ── 36 ── Perabot */
            [
                'user_id' => $u[1]->id, 'nama' => 'Set Kursi Lipat Chitose 10pcs',
                'deskripsi' => 'Paket 10 kursi lipat Chitose polypropylene, rangka besi powder-coated, tahan 120kg/kursi. Ringan dan mudah disusun. Cocok untuk seminar, rapat, dan acara wisuda.',
                'kategori' => 'perabot', 'harga_per_hari' => 50000, 'deposit' => 100000,
                'stok' => 3, 'status' => 'aktif', 'rating_avg' => 4.2, 'total_sewa' => 10,
                'foto' => ['images/items/perabot-kursi-lipat.jpg'],
            ],

            /* ── 37 ── Perabot */
            [
                'user_id' => $u[2]->id, 'nama' => 'Meja Lipat Serbaguna 180cm',
                'deskripsi' => 'Meja lipat plastik HDPE 180×60cm, rangka baja, kapasitas 150kg. Mudah dilipat dalam hitungan detik. Cocok untuk bazaar, pameran, meja registrasi, dan outdoor event.',
                'kategori' => 'perabot', 'harga_per_hari' => 30000, 'deposit' => 80000,
                'stok' => 5, 'status' => 'aktif', 'rating_avg' => 4.1, 'total_sewa' => 12,
                'foto' => ['images/items/perabot-meja-lipat.jpg'],
            ],

            /* ── 38 ── Perabot */
            [
                'user_id' => $u[3]->id, 'nama' => 'Sound System Portable 200W',
                'deskripsi' => 'Paket sound system: 2 speaker aktif 100W, mixer 6 channel, 2 mic wireless, stand speaker. Cocok untuk seminar, pentas seni, workshop, dan gathering komunitas kampus.',
                'kategori' => 'perabot', 'harga_per_hari' => 150000, 'deposit' => 400000,
                'stok' => 1, 'status' => 'aktif', 'rating_avg' => 4.6, 'total_sewa' => 7,
                'foto' => ['images/items/perabot-sound-system.jpg'],
            ],

            /* ── 39 ── Kendaraan */
            [
                'user_id' => $u[4]->id, 'nama' => 'Sepeda Lipat Dahon Speed Uno',
                'deskripsi' => 'Sepeda lipat Dahon Speed Uno 20 inch, single speed, frame aluminium 10kg. Lipat dalam 10 detik. Ideal untuk komuting antar fakultas dan dibawa ke dalam kelas/lab.',
                'kategori' => 'kendaraan', 'harga_per_hari' => 40000, 'deposit' => 100000,
                'stok' => 2, 'status' => 'aktif', 'rating_avg' => 4.4, 'total_sewa' => 14,
                'foto' => ['images/items/kendaraan-sepeda-lipat.jpg'],
            ],

            /* ── 40 ── Kendaraan */
            [
                'user_id' => $u[0]->id, 'nama' => 'Sepeda MTB XC Trail 29"',
                'deskripsi' => 'Sepeda MTB hardtail 29 inch, fork suspensi 100mm, 21 speed Shimano Altus. Cocok untuk trail cross-country, bersepeda lintas kampus, dan eksplorasi Babakan Raya.',
                'kategori' => 'kendaraan', 'harga_per_hari' => 55000, 'deposit' => 150000,
                'stok' => 1, 'status' => 'aktif', 'rating_avg' => 4.3, 'total_sewa' => 6,
                'foto' => ['images/items/kendaraan-sepeda-mtb.jpg'],
            ],

            /* ── 41 ── Kendaraan */
            [
                'user_id' => $u[1]->id, 'nama' => 'Skuter Listrik Ninebot G30',
                'deskripsi' => 'Skuter listrik Segway Ninebot KickScooter G30, kecepatan max 30km/h, jangkauan 65km. Ideal untuk mobilitas kampus cepat dan kunjungan antar gedung/fakultas.',
                'kategori' => 'kendaraan', 'harga_per_hari' => 80000, 'deposit' => 250000,
                'stok' => 2, 'status' => 'aktif', 'rating_avg' => 4.7, 'total_sewa' => 11,
                'foto' => ['images/items/kendaraan-skuter-listrik.jpg'],
            ],

            /* ── 42 ── Akademik */
            [
                'user_id' => $u[2]->id, 'nama' => 'Buku Textbook Kimia Organik Fessenden',
                'deskripsi' => 'Buku teks Organic Chemistry by Fessenden edisi 3, terjemahan lengkap dengan soal. Wajib untuk mahasiswa Kimia, Biokimia, TPB sains, dan praktikum organik lanjut.',
                'kategori' => 'akademik', 'harga_per_hari' => 8000, 'deposit' => 25000,
                'stok' => 3, 'status' => 'aktif', 'rating_avg' => 4.1, 'total_sewa' => 22,
                'foto' => ['images/items/akademik-buku-textbook.jpg'],
            ],

            /* ── 43 ── Akademik */
            [
                'user_id' => $u[3]->id, 'nama' => 'Drawing Tablet Wacom Intuos M',
                'deskripsi' => 'Drawing tablet Wacom Intuos Medium, 2540 lpi, 8192 level tekanan, 4 express key. Cocok untuk desain grafis, ilustrasi digital, diagram teknis, dan presentasi interaktif.',
                'kategori' => 'akademik', 'harga_per_hari' => 30000, 'deposit' => 90000,
                'stok' => 2, 'status' => 'aktif', 'rating_avg' => 4.5, 'total_sewa' => 8,
                'foto' => ['images/items/akademik-drawing-tablet.jpg'],
            ],

            /* ── 44 ── Akademik */
            [
                'user_id' => $u[4]->id, 'nama' => 'Mikroskop Biologi Compound Motic BA210',
                'deskripsi' => 'Mikroskop compound Motic BA210, perbesaran 40–1000×, lensa achromat planachromat, LED. Cocok untuk praktikum biologi, mikobiologi, patologi tanaman, dan penelitian sel.',
                'kategori' => 'akademik', 'harga_per_hari' => 50000, 'deposit' => 200000,
                'stok' => 1, 'status' => 'aktif', 'rating_avg' => 4.6, 'total_sewa' => 5,
                'foto' => ['images/items/akademik-microscope.jpg'],
            ],

            /* ── 45 ── Akademik */
            [
                'user_id' => $u[0]->id, 'nama' => 'Proyektor Mini HDMI Vankyo Leisure 3',
                'deskripsi' => 'Proyektor mini portable, support 1080P, koneksi HDMI/USB/VGA. Ukuran layar 32–170 inch. Ringan dan mudah dibawa ke kelas, lab, atau seminar kecil internal.',
                'kategori' => 'akademik', 'harga_per_hari' => 45000, 'deposit' => 120000,
                'stok' => 2, 'status' => 'aktif', 'rating_avg' => 4.3, 'total_sewa' => 9,
                'foto' => ['images/items/akademik-proyektor-hdmi.jpg'],
            ],

            /* ── 46 ── Lainnya */
            [
                'user_id' => $u[1]->id, 'nama' => 'Power Bank 26800mAh Anker PowerCore+',
                'deskripsi' => 'Power bank Anker PowerCore+ 26800mAh, 3 port USB-A + USB-C, fast charge 18W. Cocok untuk fieldwork panjang, perjalanan riset lapangan, dan backup daya peralatan outdoor.',
                'kategori' => 'lainnya', 'harga_per_hari' => 15000, 'deposit' => 50000,
                'stok' => 3, 'status' => 'aktif', 'rating_avg' => 4.4, 'total_sewa' => 16,
                'foto' => ['images/items/lainnya-power-bank-besar.jpg'],
            ],

            /* ── 47 ── Lainnya */
            [
                'user_id' => $u[2]->id, 'nama' => 'Printer Portable Canon SELPHY CP1300',
                'deskripsi' => 'Printer foto portable Canon SELPHY CP1300, cetak 10×15cm via WiFi/USB, resolusi 300dpi kualitas lab. Cocok untuk cetak foto lapangan, ID card event, dan dokumentasi skripsi.',
                'kategori' => 'lainnya', 'harga_per_hari' => 30000, 'deposit' => 80000,
                'stok' => 1, 'status' => 'aktif', 'rating_avg' => 4.3, 'total_sewa' => 6,
                'foto' => ['images/items/lainnya-printer-portable.jpg'],
            ],

            /* ── 48 ── Akademik */
            [
                'user_id' => $u[3]->id, 'nama' => 'Kalkulator Casio FX-350ES Plus',
                'deskripsi' => 'Kalkulator scientific Casio FX-350ES Plus, 252 fungsi, natural textbook display. Cocok untuk TPB, praktikum fisika dasar, kimia, dan ujian yang membutuhkan kalkulator standar.',
                'kategori' => 'akademik', 'harga_per_hari' => 8000, 'deposit' => 25000,
                'stok' => 5, 'status' => 'aktif', 'rating_avg' => 4.0, 'total_sewa' => 30,
                'foto' => ['images/items/kalkulator.jpg'],
            ],

            /* ── 49 ── Kendaraan */
            [
                'user_id' => $u[4]->id, 'nama' => 'Sepeda MTB Polygon Ceros N5',
                'deskripsi' => 'Sepeda MTB Polygon Ceros N5 27.5 inch, 10-speed Shimano Deore, fork SR Suntour. Ringan dan responsif. Cocok untuk trail pagi di kampus dan aktivitas fisik harian mahasiswa.',
                'kategori' => 'kendaraan', 'harga_per_hari' => 60000, 'deposit' => 175000,
                'stok' => 1, 'status' => 'aktif', 'rating_avg' => 4.5, 'total_sewa' => 8,
                'foto' => ['images/items/sepeda-mtb.jpg'],
            ],

        ];

        foreach ($items as $item) {
            Item::create($item);
        }
    }
}
