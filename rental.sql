-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 22 Apr 2026 pada 17.09
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `rental`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `items`
--

CREATE TABLE `items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `nama` varchar(255) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `kategori` enum('elektronik','fotografi','audio','drone','akademik','olahraga','perabot','kendaraan','lainnya') NOT NULL DEFAULT 'lainnya',
  `harga_per_hari` int(11) NOT NULL,
  `deposit` int(11) NOT NULL DEFAULT 0,
  `stok` int(11) NOT NULL DEFAULT 1,
  `status` enum('aktif','nonaktif','habis') NOT NULL DEFAULT 'aktif',
  `foto` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`foto`)),
  `rating_avg` decimal(3,1) NOT NULL DEFAULT 0.0,
  `total_sewa` int(11) NOT NULL DEFAULT 0,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `items`
--

INSERT INTO `items` (`id`, `user_id`, `nama`, `deskripsi`, `kategori`, `harga_per_hari`, `deposit`, `stok`, `status`, `foto`, `rating_avg`, `total_sewa`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 2, 'Kamera Canon EOS 200D', 'Kamera DSLR entry-level Canon EOS 200D, kondisi baik. Termasuk lensa kit 18-55mm. Cocok untuk fotografi landscape, portrait, dan vlog.', 'fotografi', 85000, 200000, 1, 'aktif', '[\"images\\/items\\/foto-kamera-dslr.jpg\"]', 4.8, 12, NULL, '2026-04-22 08:09:08', '2026-04-22 08:09:08'),
(2, 2, 'Drone DJI Mini 2', 'Drone DJI Mini 2 dengan kamera 4K. Ringan dan mudah dibawa. Baterai 3 unit included. Sempurna untuk dokumentasi acara dan konten media sosial.', 'drone', 200000, 500000, 1, 'aktif', '[\"images\\/items\\/drone-dji-mini.jpg\"]', 4.9, 8, NULL, '2026-04-22 08:09:08', '2026-04-22 08:09:08'),
(3, 3, 'Laptop ASUS ROG Gaming', 'Laptop gaming ASUS ROG Strix G15, AMD Ryzen 7, RAM 16GB, SSD 512GB, GPU RTX 3060. Cocok untuk editing video, gaming, dan rendering.', 'elektronik', 120000, 300000, 1, 'aktif', '[\"images\\/items\\/elektronik-laptop-gaming.jpg\"]', 4.7, 15, NULL, '2026-04-22 08:09:08', '2026-04-22 08:09:08'),
(4, 3, 'Speaker Bluetooth JBL Charge 5', 'Speaker portable JBL Charge 5, waterproof IPX7, bass kencang. Baterai tahan 20 jam. Ideal untuk outdoor, acara, atau studio mini.', 'audio', 35000, 100000, 2, 'aktif', '[\"images\\/items\\/audio-speaker-bluetooth.jpg\"]', 4.6, 20, NULL, '2026-04-22 08:09:08', '2026-04-22 08:09:08'),
(5, 4, 'Sepeda MTB Trek Marlin 5', 'Sepeda gunung Trek Marlin 5 ukuran 29 inch. Kondisi terawat, ban baru. Cocok untuk bersepeda santai di area kampus atau trail ringan.', 'olahraga', 50000, 150000, 1, 'aktif', '[\"images\\/items\\/olahraga-sepeda-road.jpg\"]', 4.5, 9, NULL, '2026-04-22 08:09:08', '2026-04-22 08:09:08'),
(6, 4, 'Proyektor Mini Portable', 'Proyektor portable 1080p, brightness 3500 lumens. Mendukung HDMI dan wireless screen mirroring. Ideal untuk presentasi, nonton bareng, atau seminar.', 'elektronik', 75000, 200000, 1, 'aktif', '[\"images\\/items\\/elektronik-proyektor-mini.jpg\"]', 4.4, 7, NULL, '2026-04-22 08:09:08', '2026-04-22 08:09:08'),
(7, 5, 'Tenda Camping 4 Orang', 'Tenda dome kapasitas 4 orang, waterproof, mudah dipasang. Termasuk groundsheet dan pasak. Cocok untuk camping, hiking, atau keperluan outdoor.', 'olahraga', 45000, 100000, 2, 'aktif', '[\"images\\/items\\/tenda-camping.jpg\"]', 4.3, 6, NULL, '2026-04-22 08:09:08', '2026-04-22 08:09:08'),
(8, 5, 'Stabilizer Gimbal DJI OM 5', 'Gimbal smartphone DJI OM 5, 3-axis stabilization, magnetic attach. Baterai tahan 6.4 jam. Perfect untuk konten creator dan vlogging.', 'fotografi', 55000, 150000, 1, 'aktif', '[\"images\\/items\\/gimbal-dji.jpg\"]', 4.7, 11, NULL, '2026-04-22 08:09:08', '2026-04-22 08:09:08'),
(9, 6, 'Kalkulator Casio fx-991EX', 'Kalkulator scientific Casio ClassWiz fx-991EX. 552 fungsi, spreadsheet mode. Wajib untuk praktikum sains, statistika, dan matematika tingkat lanjut.', 'akademik', 10000, 30000, 3, 'aktif', '[\"images\\/items\\/akademik-kalkulator-scientific.jpg\"]', 4.2, 25, NULL, '2026-04-22 08:09:08', '2026-04-22 08:09:08'),
(10, 6, 'Mic Condenser Rode NT1-A', 'Microphone condenser Rode NT1-A, cardioid polar pattern, self-noise 4.5dB. Cocok untuk rekaman podcast, voiceover, instrumen akustik.', 'audio', 65000, 200000, 1, 'aktif', '[\"images\\/items\\/audio-microphone-condenser.jpg\"]', 4.8, 10, NULL, '2026-04-22 08:09:08', '2026-04-22 08:09:08'),
(11, 2, 'Kamera Sony A6400 Mirrorless', 'Kamera mirrorless Sony A6400, sensor APS-C 24.2MP, autofokus real-time eye tracking. Cocok untuk street photography, portrait, dan konten kreator cepat.', 'fotografi', 100000, 250000, 1, 'aktif', '[\"images\\/items\\/foto-kamera-mirrorless.jpg\"]', 4.7, 8, NULL, '2026-04-22 08:09:08', '2026-04-22 08:09:08'),
(12, 3, 'Lensa Tele Canon EF 70-200mm f/2.8', 'Lensa tele zoom Canon EF 70-200mm f/2.8L IS III USM. Cocok untuk fotografi olahraga, wildlife, dan portrait dengan bokeh creamy yang indah.', 'fotografi', 90000, 300000, 1, 'aktif', '[\"images\\/items\\/foto-lensa-tele.jpg\"]', 4.9, 5, NULL, '2026-04-22 08:09:08', '2026-04-22 08:09:08'),
(13, 4, 'Tripod Carbon Fiber Benro', 'Tripod carbon fiber Benro Series 2, tinggi max 165cm, load capacity 8kg. Lightweight dan kokoh. Cocok untuk foto landscape, long exposure, dan video stabil.', 'fotografi', 25000, 80000, 2, 'aktif', '[\"images\\/items\\/foto-tripod.jpg\"]', 4.4, 13, NULL, '2026-04-22 08:09:08', '2026-04-22 08:09:08'),
(14, 5, 'GoPro Hero 11 Black', 'Action camera GoPro Hero 11 Black, video 5.3K/60fps, waterproof hingga 10m. Termasuk mounting kit lengkap. Ideal untuk petualangan outdoor dan vlog ekstrem.', 'fotografi', 75000, 200000, 1, 'aktif', '[\"images\\/items\\/foto-gopro.jpg\"]', 4.8, 7, NULL, '2026-04-22 08:09:08', '2026-04-22 08:09:08'),
(15, 6, 'Lighting Kit Studio 3 Titik', 'Set lighting studio 3 titik: key light 150W, fill light 75W, backlight 75W — semuanya LED softbox. Cocok untuk foto produk, portrait studio, dan live streaming.', 'fotografi', 60000, 150000, 1, 'aktif', '[\"images\\/items\\/foto-lighting-kit.jpg\"]', 4.5, 4, NULL, '2026-04-22 08:09:08', '2026-04-22 08:09:08'),
(16, 2, 'Drone DJI Air 2S', 'Drone DJI Air 2S, kamera 1-inch CMOS 20MP, video 5.4K. Obstacle sensing 4 arah, OcuSync 3.0 jangkauan 12km. Ideal untuk videografi semi-profesional.', 'drone', 250000, 600000, 1, 'aktif', '[\"images\\/items\\/drone-dji-air.jpg\"]', 4.8, 6, NULL, '2026-04-22 08:09:08', '2026-04-22 08:09:08'),
(17, 3, 'Drone DJI Mavic 3', 'Drone flagship DJI Mavic 3, dual kamera Hasselblad, video 5.1K/50fps, terbang 46 menit. Obstacle sensing omnidirectional. Kualitas sinematik terbaik di kelasnya.', 'drone', 350000, 800000, 1, 'aktif', '[\"images\\/items\\/drone-dji-mavic.jpg\"]', 5.0, 3, NULL, '2026-04-22 08:09:08', '2026-04-22 08:09:08'),
(18, 4, 'Drone FPV Racing Kit DJI', 'Drone FPV racing DJI dengan goggles V2 dan remote motion controller. Kecepatan max 140km/h, manual mode. Cocok untuk pengguna berpengalaman dan konten adrenalin.', 'drone', 300000, 700000, 1, 'aktif', '[\"images\\/items\\/drone-fpv.jpg\"]', 4.6, 2, NULL, '2026-04-22 08:09:08', '2026-04-22 08:09:08'),
(19, 5, 'MacBook Pro M2 14\"', 'MacBook Pro 14 inch chip Apple M2 Pro, RAM 16GB, SSD 512GB, layar Liquid Retina XDR. Performa tinggi untuk editing video 4K, desain grafis, dan coding intensif.', 'elektronik', 150000, 500000, 1, 'aktif', '[\"images\\/items\\/elektronik-laptop-macbook.jpg\"]', 4.9, 10, NULL, '2026-04-22 08:09:08', '2026-04-22 08:09:08'),
(20, 6, 'iPad Pro 12.9\" M2', 'iPad Pro 12.9 inch chip M2, layar Liquid Retina XDR ProMotion 120Hz. Cocok untuk digital art, presentasi interaktif, video call resolusi tinggi, dan produktivitas mobile.', 'elektronik', 80000, 300000, 1, 'aktif', '[\"images\\/items\\/elektronik-ipad.jpg\"]', 4.7, 9, NULL, '2026-04-22 08:09:08', '2026-04-22 08:09:08'),
(21, 2, 'Monitor Portable 15.6\" 4K', 'Monitor portable 15.6 inch resolusi 4K UHD, koneksi USB-C dan Mini HDMI. Tipis dan ringan. Ideal untuk dual-screen presentation, workstation mobile, dan gaming on-the-go.', 'elektronik', 45000, 150000, 2, 'aktif', '[\"images\\/items\\/elektronik-monitor-portable.jpg\"]', 4.5, 11, NULL, '2026-04-22 08:09:08', '2026-04-22 08:09:08'),
(22, 3, 'Hard Disk External 2TB WD', 'Hard disk external WD My Passport 2TB, USB 3.0, enkripsi hardware. Portable dan andal. Cocok untuk backup skripsi, transfer footage video, dan penyimpanan data riset.', 'elektronik', 15000, 50000, 3, 'aktif', '[\"images\\/items\\/elektronik-harddisk.jpg\"]', 4.3, 18, NULL, '2026-04-22 08:09:08', '2026-04-22 08:09:08'),
(23, 4, 'Proyektor Epson EB-X41', 'Proyektor Epson EB-X41 XGA 3600 lumen. Termasuk layar portable dan kabel HDMI. Cocok untuk seminar kelas besar, rapat himpunan, dan presentasi kampus resmi.', 'elektronik', 70000, 200000, 2, 'aktif', '[\"images\\/items\\/proyektor.jpg\"]', 4.6, 14, NULL, '2026-04-22 08:09:08', '2026-04-22 08:09:08'),
(24, 5, 'Headphone Bose QuietComfort 45', 'Headphone over-ear Bose QC45, active noise-cancelling terdepan. Baterai 24 jam. Audio jernih untuk studio mixing, sesi belajar fokus, dan perjalanan jauh.', 'audio', 40000, 120000, 1, 'aktif', '[\"images\\/items\\/audio-headphone-bose.jpg\"]', 4.8, 12, NULL, '2026-04-22 08:09:08', '2026-04-22 08:09:08'),
(25, 6, 'Headphone Sony WH-1000XM5', 'Headphone Sony WH-1000XM5, ANC industri terbaik, LDAC hi-res audio. Baterai 30 jam. Nyaman untuk sesi belajar panjang, rekaman studio, dan perjalanan jauh.', 'audio', 45000, 130000, 1, 'aktif', '[\"images\\/items\\/audio-headphone-sony.jpg\"]', 4.9, 7, NULL, '2026-04-22 08:09:08', '2026-04-22 08:09:08'),
(26, 2, 'Speaker Marshall Emberton II', 'Speaker bluetooth Marshall Emberton II, 360° stereo sound, IPX7 waterproof. Baterai 30 jam. Suara khas Marshall yang hangat dan bertenaga — sempurna untuk gathering.', 'audio', 40000, 120000, 1, 'aktif', '[\"images\\/items\\/audio-speaker-marshall.jpg\"]', 4.7, 9, NULL, '2026-04-22 08:09:08', '2026-04-22 08:09:08'),
(27, 3, 'Audio Interface Focusrite Scarlett 2i2', 'Audio interface Focusrite Scarlett 2i2 Gen 3, 2 input XLR/TRS, 24-bit/192kHz. Ideal untuk rekaman vokal, podcast profesional, instrumen, dan home studio berkualitas.', 'audio', 35000, 100000, 1, 'aktif', '[\"images\\/items\\/audio-audio-interface.jpg\"]', 4.6, 6, NULL, '2026-04-22 08:09:08', '2026-04-22 08:09:08'),
(28, 4, 'Speaker JBL Boombox 3', 'Speaker portabel JBL Boombox 3, output 80W, IP67 waterproof & dustproof. Baterai 24 jam. Suara menggelegar untuk pesta outdoor, pentas seni, dan gathering besar.', 'audio', 55000, 150000, 1, 'aktif', '[\"images\\/items\\/speaker-jbl.jpg\"]', 4.5, 8, NULL, '2026-04-22 08:09:08', '2026-04-22 08:09:08'),
(29, 5, 'Microphone Condenser Blue Yeti', 'Mikrofon kondenser USB Blue Yeti, 4 polar pattern. Plug-and-play tanpa driver. Suara kristal jernih untuk podcast, live streaming, Zoom, dan rekaman vokal indie.', 'audio', 40000, 120000, 1, 'aktif', '[\"images\\/items\\/mic-condenser.jpg\"]', 4.7, 11, NULL, '2026-04-22 08:09:08', '2026-04-22 08:09:08'),
(30, 6, 'Backpack Hiking 60L Deuter Aircontact', 'Carrier Deuter Aircontact 60+10L, frame aluminium, hipbelt ergonomis, waterproof cover included. Ideal untuk camping gunung, hiking multi-hari, dan perjalanan lapangan.', 'olahraga', 35000, 100000, 2, 'aktif', '[\"images\\/items\\/olahraga-camping-backpack.jpg\"]', 4.6, 8, NULL, '2026-04-22 08:09:08', '2026-04-22 08:09:08'),
(31, 2, 'Raket Badminton Yonex Astrox 88D', 'Raket Yonex Astrox 88D Pro, head-heavy balance, nanofiber carbon. Cocok untuk smash powereful. Kondisi prima, sudah di-restring dengan Yonex BG65 tegangan 28lbs.', 'olahraga', 20000, 60000, 2, 'aktif', '[\"images\\/items\\/olahraga-raket-badminton.jpg\"]', 4.5, 15, NULL, '2026-04-22 08:09:08', '2026-04-22 08:09:08'),
(32, 3, 'Sepeda MTB Polygon Siskiu T8', 'Sepeda MTB full-suspension Polygon Siskiu T8 29\", travel 130mm, rem hydraulic Shimano SLX. Cocok untuk trail menantang, cross-country, dan eksplorasi hutan Dramaga.', 'olahraga', 70000, 200000, 1, 'aktif', '[\"images\\/items\\/olahraga-sepeda-mtb.jpg\"]', 4.7, 5, NULL, '2026-04-22 08:09:08', '2026-04-22 08:09:08'),
(33, 4, 'Skateboard Longboard 40\"', 'Longboard 40 inch deck maple, trucks aluminium, roda 70mm 78A. Cocok untuk cruising santai di kampus, downhill ringan, dan belajar skateboarding bagi pemula.', 'olahraga', 25000, 75000, 2, 'aktif', '[\"images\\/items\\/olahraga-skateboard.jpg\"]', 4.3, 7, NULL, '2026-04-22 08:09:08', '2026-04-22 08:09:08'),
(34, 5, 'Raket Tenis Wilson Ultra 100', 'Raket tenis Wilson Ultra 100 v3.2, 300g, head 100sq.in. Cocok untuk pemain intermediate hingga advanced. Termasuk tas raket dan 1 kaleng bola baru.', 'olahraga', 25000, 75000, 2, 'aktif', '[\"images\\/items\\/olahraga-tenis-raket.jpg\"]', 4.4, 9, NULL, '2026-04-22 08:09:08', '2026-04-22 08:09:08'),
(35, 6, 'Tenda Dome Event 6×6m', 'Tenda frame 6×6m, terpal 500gsm waterproof, rangka pipa galvanis. Kapasitas 30–40 orang. Ideal untuk bazaar, pameran UKM, dan acara outdoor kampus besar.', 'perabot', 200000, 500000, 1, 'aktif', '[\"images\\/items\\/perabot-tenda-dome.jpg.jpeg\"]', 4.4, 4, NULL, '2026-04-22 08:09:08', '2026-04-22 08:09:08'),
(36, 2, 'Genset Portable 1000W Honda EU10i', 'Generator portable Honda EU10i 1000W inverter, konsumsi bahan bakar rendah, kebisingan 59dB. Tangki 2.1L tahan 8 jam. Ideal untuk acara outdoor dan power backup.', 'perabot', 120000, 350000, 1, 'aktif', '[\"images\\/items\\/perabot-genset.jpg\"]', 4.5, 3, NULL, '2026-04-22 08:09:08', '2026-04-22 08:09:08'),
(37, 3, 'Set Kursi Lipat Chitose 10pcs', 'Paket 10 kursi lipat Chitose polypropylene, rangka besi powder-coated, tahan 120kg/kursi. Ringan dan mudah disusun. Cocok untuk seminar, rapat, dan acara wisuda.', 'perabot', 50000, 100000, 3, 'aktif', '[\"images\\/items\\/perabot-kursi-lipat.jpg\"]', 4.2, 10, NULL, '2026-04-22 08:09:08', '2026-04-22 08:09:08'),
(38, 4, 'Meja Lipat Serbaguna 180cm', 'Meja lipat plastik HDPE 180×60cm, rangka baja, kapasitas 150kg. Mudah dilipat dalam hitungan detik. Cocok untuk bazaar, pameran, meja registrasi, dan outdoor event.', 'perabot', 30000, 80000, 5, 'aktif', '[\"images\\/items\\/perabot-meja-lipat.jpg\"]', 4.1, 12, NULL, '2026-04-22 08:09:08', '2026-04-22 08:09:08'),
(39, 5, 'Sound System Portable 200W', 'Paket sound system: 2 speaker aktif 100W, mixer 6 channel, 2 mic wireless, stand speaker. Cocok untuk seminar, pentas seni, workshop, dan gathering komunitas kampus.', 'perabot', 150000, 400000, 1, 'aktif', '[\"images\\/items\\/perabot-sound-system.jpg\"]', 4.6, 7, NULL, '2026-04-22 08:09:08', '2026-04-22 08:09:08'),
(40, 6, 'Sepeda Lipat Dahon Speed Uno', 'Sepeda lipat Dahon Speed Uno 20 inch, single speed, frame aluminium 10kg. Lipat dalam 10 detik. Ideal untuk komuting antar fakultas dan dibawa ke dalam kelas/lab.', 'kendaraan', 40000, 100000, 2, 'aktif', '[\"images\\/items\\/kendaraan-sepeda-lipat.jpg\"]', 4.4, 14, NULL, '2026-04-22 08:09:08', '2026-04-22 08:09:08'),
(41, 2, 'Sepeda MTB XC Trail 29\"', 'Sepeda MTB hardtail 29 inch, fork suspensi 100mm, 21 speed Shimano Altus. Cocok untuk trail cross-country, bersepeda lintas kampus, dan eksplorasi Babakan Raya.', 'kendaraan', 55000, 150000, 1, 'aktif', '[\"images\\/items\\/kendaraan-sepeda-mtb.jpg\"]', 4.3, 6, NULL, '2026-04-22 08:09:08', '2026-04-22 08:09:08'),
(42, 3, 'Skuter Listrik Ninebot G30', 'Skuter listrik Segway Ninebot KickScooter G30, kecepatan max 30km/h, jangkauan 65km. Ideal untuk mobilitas kampus cepat dan kunjungan antar gedung/fakultas.', 'kendaraan', 80000, 250000, 2, 'aktif', '[\"images\\/items\\/kendaraan-skuter-listrik.jpg\"]', 4.7, 11, NULL, '2026-04-22 08:09:08', '2026-04-22 08:09:08'),
(43, 4, 'Buku Textbook Kimia Organik Fessenden', 'Buku teks Organic Chemistry by Fessenden edisi 3, terjemahan lengkap dengan soal. Wajib untuk mahasiswa Kimia, Biokimia, TPB sains, dan praktikum organik lanjut.', 'akademik', 8000, 25000, 3, 'aktif', '[\"images\\/items\\/akademik-buku-textbook.jpg\"]', 4.1, 22, NULL, '2026-04-22 08:09:08', '2026-04-22 08:09:08'),
(44, 5, 'Drawing Tablet Wacom Intuos M', 'Drawing tablet Wacom Intuos Medium, 2540 lpi, 8192 level tekanan, 4 express key. Cocok untuk desain grafis, ilustrasi digital, diagram teknis, dan presentasi interaktif.', 'akademik', 30000, 90000, 2, 'aktif', '[\"images\\/items\\/akademik-drawing-tablet.jpg\"]', 4.5, 8, NULL, '2026-04-22 08:09:08', '2026-04-22 08:09:08'),
(45, 6, 'Mikroskop Biologi Compound Motic BA210', 'Mikroskop compound Motic BA210, perbesaran 40–1000×, lensa achromat planachromat, LED. Cocok untuk praktikum biologi, mikobiologi, patologi tanaman, dan penelitian sel.', 'akademik', 50000, 200000, 1, 'aktif', '[\"images\\/items\\/akademik-microscope.jpg\"]', 4.6, 5, NULL, '2026-04-22 08:09:08', '2026-04-22 08:09:08'),
(46, 2, 'Proyektor Mini HDMI Vankyo Leisure 3', 'Proyektor mini portable, support 1080P, koneksi HDMI/USB/VGA. Ukuran layar 32–170 inch. Ringan dan mudah dibawa ke kelas, lab, atau seminar kecil internal.', 'akademik', 45000, 120000, 2, 'aktif', '[\"images\\/items\\/akademik-proyektor-hdmi.jpg\"]', 4.3, 9, NULL, '2026-04-22 08:09:08', '2026-04-22 08:09:08'),
(47, 3, 'Power Bank 26800mAh Anker PowerCore+', 'Power bank Anker PowerCore+ 26800mAh, 3 port USB-A + USB-C, fast charge 18W. Cocok untuk fieldwork panjang, perjalanan riset lapangan, dan backup daya peralatan outdoor.', 'lainnya', 15000, 50000, 3, 'aktif', '[\"images\\/items\\/lainnya-power-bank-besar.jpg\"]', 4.4, 16, NULL, '2026-04-22 08:09:08', '2026-04-22 08:09:08'),
(48, 4, 'Printer Portable Canon SELPHY CP1300', 'Printer foto portable Canon SELPHY CP1300, cetak 10×15cm via WiFi/USB, resolusi 300dpi kualitas lab. Cocok untuk cetak foto lapangan, ID card event, dan dokumentasi skripsi.', 'lainnya', 30000, 80000, 1, 'aktif', '[\"images\\/items\\/lainnya-printer-portable.jpg\"]', 4.3, 6, NULL, '2026-04-22 08:09:08', '2026-04-22 08:09:08'),
(49, 5, 'Kalkulator Casio FX-350ES Plus', 'Kalkulator scientific Casio FX-350ES Plus, 252 fungsi, natural textbook display. Cocok untuk TPB, praktikum fisika dasar, kimia, dan ujian yang membutuhkan kalkulator standar.', 'akademik', 8000, 25000, 5, 'aktif', '[\"images\\/items\\/kalkulator.jpg\"]', 4.0, 30, NULL, '2026-04-22 08:09:08', '2026-04-22 08:09:08'),
(50, 6, 'Sepeda MTB Polygon Ceros N5', 'Sepeda MTB Polygon Ceros N5 27.5 inch, 10-speed Shimano Deore, fork SR Suntour. Ringan dan responsif. Cocok untuk trail pagi di kampus dan aktivitas fisik harian mahasiswa.', 'kendaraan', 60000, 175000, 1, 'aktif', '[\"images\\/items\\/sepeda-mtb.jpg\"]', 4.5, 8, NULL, '2026-04-22 08:09:08', '2026-04-22 08:09:08');

-- --------------------------------------------------------

--
-- Struktur dari tabel `messages`
--

CREATE TABLE `messages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `sender_id` bigint(20) UNSIGNED NOT NULL,
  `receiver_id` bigint(20) UNSIGNED NOT NULL,
  `rental_id` bigint(20) UNSIGNED DEFAULT NULL,
  `pesan` text NOT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `messages`
--

INSERT INTO `messages` (`id`, `sender_id`, `receiver_id`, `rental_id`, `pesan`, `is_read`, `created_at`, `updated_at`) VALUES
(1, 2, 3, NULL, 'Halo Kak Siti, laptop ROG-nya masih tersedia tidak minggu ini?', 1, '2026-04-17 07:39:08', '2026-04-17 07:39:08'),
(2, 3, 2, NULL, 'Halo Mas Budi! Masih tersedia kok. Mau dipakai buat apa?', 1, '2026-04-17 07:44:09', '2026-04-17 07:44:09'),
(3, 2, 3, NULL, 'Mau buat editing video tugas akhir, kira-kira 4 hari boleh?', 1, '2026-04-17 07:49:09', '2026-04-17 07:49:09'),
(4, 3, 2, NULL, 'Boleh banget! Silakan ajukan sewa lewat aplikasi ya, nanti saya konfirmasi.', 1, '2026-04-17 07:54:09', '2026-04-17 07:54:09'),
(5, 2, 3, NULL, 'Siap, sudah saya ajukan. Terima kasih banyak Kak!', 1, '2026-04-17 07:59:09', '2026-04-17 07:59:09'),
(6, 3, 2, NULL, 'Oke, sudah saya terima. Silakan transfer DP sesuai nominal di aplikasi ya.', 1, '2026-04-17 08:04:09', '2026-04-17 08:04:09'),
(7, 2, 3, NULL, 'Siap Kak! Nanti langsung saya kirim buktinya.', 1, '2026-04-17 08:07:09', '2026-04-17 08:07:09'),
(8, 3, 2, NULL, 'Oke ditunggu ya Mas. Kalau ada pertanyaan feel free tanya sini 😊', 0, '2026-04-17 08:08:09', '2026-04-17 08:08:09'),
(9, 2, 4, NULL, 'Mas Ahmad, proyektornya bisa dilihat langsung tidak sebelum sewa?', 1, '2026-04-19 07:49:09', '2026-04-19 07:49:09'),
(10, 4, 2, NULL, 'Bisa dong! Saya di asrama B3 kamar 204. Bisa datang kapan saja jam 08–20.', 1, '2026-04-19 07:51:09', '2026-04-19 07:51:09'),
(11, 2, 4, NULL, 'Sip, nanti sore saya mampir ya. Kecerahan proyektornya cukup untuk ruangan ukuran sedang?', 1, '2026-04-19 07:59:09', '2026-04-19 07:59:09'),
(12, 4, 2, NULL, '3500 lumens, cukup bahkan di ruangan dengan sedikit cahaya. Pasti memuaskan!', 1, '2026-04-19 08:01:09', '2026-04-19 08:01:09'),
(13, 2, 4, NULL, 'Mantap, oke saya ajukan sekarang. Terima kasih infonya Mas!', 1, '2026-04-19 08:06:09', '2026-04-19 08:06:09'),
(14, 2, 5, NULL, 'Kak Dewi, gimbal DJI OM5-nya masih tersedia minggu depan?', 1, '2026-04-15 07:54:09', '2026-04-15 07:54:09'),
(15, 5, 2, NULL, 'Hai! Masih tersedia. Untuk keperluan apa?', 1, '2026-04-15 07:57:09', '2026-04-15 07:57:09'),
(16, 2, 5, NULL, 'Mau bikin konten Instagram, butuh footage yang smooth.', 1, '2026-04-15 07:59:09', '2026-04-15 07:59:09'),
(17, 5, 2, NULL, 'Cocok banget! OM5 hasilnya super smooth. Mau berapa hari?', 1, '2026-04-15 08:01:09', '2026-04-15 08:01:09'),
(18, 2, 5, NULL, 'Sekitar 5 hari. Boleh ya Kak?', 1, '2026-04-15 08:04:09', '2026-04-15 08:04:09'),
(19, 5, 2, NULL, 'Boleh, langsung ajukan saja. Nanti saya proses segera!', 1, '2026-04-15 08:07:09', '2026-04-15 08:07:09'),
(20, 2, 5, NULL, 'Sudah diajukan Kak, terima kasih! 🙏', 1, '2026-04-15 08:08:09', '2026-04-15 08:08:09'),
(21, 3, 4, NULL, 'Mas Ahmad, sepedanya ukuran berapa? Tinggi badan saya 158cm.', 1, '2026-04-12 07:49:09', '2026-04-12 07:49:09'),
(22, 4, 3, NULL, 'Frame 15 inch, cocok untuk tinggi 150–165cm. Posisi sadel bisa disesuaikan.', 1, '2026-04-12 07:52:09', '2026-04-12 07:52:09'),
(23, 3, 4, NULL, 'Perfect! Oke saya ajukan sekarang ya.', 1, '2026-04-12 07:54:09', '2026-04-12 07:54:09'),
(24, 4, 3, NULL, 'Siap, ditunggu ya konfirmasinya.', 1, '2026-04-12 07:56:09', '2026-04-12 07:56:09'),
(25, 5, 6, NULL, 'Kak, Rode NT1-A ini butuh phantom power ya?', 1, '2026-04-18 07:59:09', '2026-04-18 07:59:09'),
(26, 6, 5, NULL, 'Betul, butuh phantom power 48V. Sudah punya audio interface?', 1, '2026-04-18 08:01:09', '2026-04-18 08:01:09'),
(27, 5, 6, NULL, 'Ada Focusrite Scarlett Solo, kompatibel?', 1, '2026-04-18 08:04:09', '2026-04-18 08:04:09'),
(28, 6, 5, NULL, 'Kompatibel 100%! Langsung order saja, kualitas rekaman dijamin jernih.', 1, '2026-04-18 08:07:09', '2026-04-18 08:07:09');

-- --------------------------------------------------------

--
-- Struktur dari tabel `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_reset_tokens_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(5, '2024_01_01_000001_create_items_table', 1),
(6, '2024_01_01_000002_create_rentals_table', 1),
(7, '2024_01_01_000003_create_messages_table', 1),
(8, '2024_01_01_000004_create_reports_table', 1),
(9, '2024_01_01_000005_create_verifications_table', 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `rentals`
--

CREATE TABLE `rentals` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `item_id` bigint(20) UNSIGNED NOT NULL,
  `penyewa_id` bigint(20) UNSIGNED NOT NULL,
  `pemilik_id` bigint(20) UNSIGNED NOT NULL,
  `tanggal_mulai` date NOT NULL,
  `tanggal_selesai` date NOT NULL,
  `durasi_hari` int(11) NOT NULL,
  `total_harga` int(11) NOT NULL,
  `deposit` int(11) NOT NULL DEFAULT 0,
  `status` enum('pending','dp_paid','active','finished','cancelled') NOT NULL DEFAULT 'pending',
  `catatan` text DEFAULT NULL,
  `bukti_dp` varchar(255) DEFAULT NULL,
  `rating` int(11) DEFAULT NULL,
  `ulasan` text DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `rentals`
--

INSERT INTO `rentals` (`id`, `item_id`, `penyewa_id`, `pemilik_id`, `tanggal_mulai`, `tanggal_selesai`, `durasi_hari`, `total_harga`, `deposit`, `status`, `catatan`, `bukti_dp`, `rating`, `ulasan`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 3, 2, 3, '2026-03-23', '2026-03-27', 4, 780000, 300000, 'finished', NULL, NULL, 5, 'Laptop ROG-nya kenceng banget, editing video jadi lancar! Pemiliknya juga responsif dan baik.', NULL, '2026-04-22 08:09:08', '2026-04-22 08:09:08'),
(2, 4, 2, 3, '2026-04-08', '2026-04-10', 2, 170000, 100000, 'finished', NULL, NULL, NULL, NULL, NULL, '2026-04-22 08:09:08', '2026-04-22 08:09:08'),
(3, 8, 2, 5, '2026-04-20', '2026-04-25', 5, 425000, 150000, 'active', NULL, NULL, NULL, NULL, NULL, '2026-04-22 08:09:08', '2026-04-22 08:09:08'),
(4, 6, 2, 4, '2026-04-22', '2026-04-25', 3, 425000, 200000, 'dp_paid', NULL, NULL, NULL, NULL, NULL, '2026-04-22 08:09:08', '2026-04-22 08:09:08'),
(5, 9, 2, 6, '2026-04-25', '2026-04-27', 2, 50000, 30000, 'pending', NULL, NULL, NULL, NULL, NULL, '2026-04-22 08:09:08', '2026-04-22 08:09:08'),
(6, 10, 2, 6, '2026-04-12', '2026-04-14', 2, 330000, 200000, 'cancelled', NULL, NULL, NULL, NULL, NULL, '2026-04-22 08:09:08', '2026-04-22 08:09:08'),
(7, 1, 3, 2, '2026-04-24', '2026-04-27', 3, 455000, 200000, 'pending', NULL, NULL, NULL, NULL, NULL, '2026-04-22 08:09:08', '2026-04-22 08:09:08'),
(8, 1, 4, 2, '2026-04-27', '2026-04-29', 2, 370000, 200000, 'dp_paid', NULL, NULL, NULL, NULL, NULL, '2026-04-22 08:09:08', '2026-04-22 08:09:08'),
(9, 2, 5, 2, '2026-04-19', '2026-04-25', 6, 1700000, 500000, 'active', NULL, NULL, NULL, NULL, NULL, '2026-04-22 08:09:08', '2026-04-22 08:09:08'),
(10, 2, 6, 2, '2026-04-02', '2026-04-05', 3, 1100000, 500000, 'finished', NULL, NULL, 5, 'Drone-nya terbang mulus, foto 4K jernih banget. Highly recommended!', NULL, '2026-04-22 08:09:08', '2026-04-22 08:09:08'),
(11, 5, 3, 4, '2026-03-28', '2026-03-31', 3, 300000, 150000, 'finished', NULL, NULL, 4, 'Sepeda kondisi baik, ban tidak kempis. Cocok untuk gowes santai di kampus.', NULL, '2026-04-22 08:09:08', '2026-04-22 08:09:08'),
(12, 7, 4, 5, '2026-04-04', '2026-04-08', 4, 280000, 100000, 'finished', NULL, NULL, 5, 'Tenda kokoh dan gampang dipasang. Waterproof beneran, hujan deras pun aman!', NULL, '2026-04-22 08:09:08', '2026-04-22 08:09:08'),
(13, 9, 5, 6, '2026-04-10', '2026-04-15', 5, 80000, 30000, 'finished', NULL, NULL, 5, 'Fitur lengkap banget, sangat membantu saat ujian statistika.', NULL, '2026-04-22 08:09:08', '2026-04-22 08:09:08'),
(14, 3, 6, 3, '2026-03-18', '2026-03-20', 2, 540000, 300000, 'finished', NULL, NULL, 4, 'Laptop gaming kencang, oke untuk render video pendek.', NULL, '2026-04-22 08:09:08', '2026-04-22 08:09:08'),
(15, 10, 3, 6, '2026-04-21', '2026-04-24', 3, 395000, 200000, 'active', NULL, NULL, NULL, NULL, NULL, '2026-04-22 08:09:08', '2026-04-22 08:09:08'),
(16, 4, 4, 3, '2026-04-22', '2026-04-24', 2, 170000, 100000, 'dp_paid', NULL, NULL, NULL, NULL, NULL, '2026-04-22 08:09:08', '2026-04-22 08:09:08'),
(17, 6, 5, 4, '2026-04-26', '2026-04-29', 3, 425000, 200000, 'pending', NULL, NULL, NULL, NULL, NULL, '2026-04-22 08:09:08', '2026-04-22 08:09:08'),
(18, 1, 6, 2, '2026-03-13', '2026-03-16', 3, 455000, 200000, 'finished', NULL, NULL, 4, 'Kamera DSLR bagus, gambar tajam. Pemiliknya ramah dan helpful.', NULL, '2026-04-22 08:09:08', '2026-04-22 08:09:08'),
(19, 8, 4, 5, '2026-04-14', '2026-04-16', 2, 260000, 150000, 'cancelled', NULL, NULL, NULL, NULL, NULL, '2026-04-22 08:09:08', '2026-04-22 08:09:08'),
(20, 7, 3, 5, '2026-04-15', '2026-04-18', 3, 235000, 100000, 'finished', NULL, NULL, NULL, NULL, NULL, '2026-04-22 08:09:08', '2026-04-22 08:09:08');

-- --------------------------------------------------------

--
-- Struktur dari tabel `reports`
--

CREATE TABLE `reports` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `reporter_id` bigint(20) UNSIGNED NOT NULL,
  `terlapor_id` bigint(20) UNSIGNED DEFAULT NULL,
  `item_id` bigint(20) UNSIGNED DEFAULT NULL,
  `rental_id` bigint(20) UNSIGNED DEFAULT NULL,
  `kategori` enum('penipuan','barang_rusak','tidak_sesuai','keterlambatan','lainnya') NOT NULL DEFAULT 'lainnya',
  `judul` varchar(255) DEFAULT NULL,
  `deskripsi` text NOT NULL,
  `bukti` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`bukti`)),
  `status` enum('pending','diproses','selesai') NOT NULL DEFAULT 'pending',
  `balasan_admin` text DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `reports`
--

INSERT INTO `reports` (`id`, `reporter_id`, `terlapor_id`, `item_id`, `rental_id`, `kategori`, `judul`, `deskripsi`, `bukti`, `status`, `balasan_admin`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 2, 3, NULL, 1, 'tidak_sesuai', NULL, 'Speaker yang saya terima kondisinya berbeda dengan foto di listing. Salah satu driver tweeter berbunyi sember dan tidak disebutkan sebelumnya. Saya sudah mencoba menghubungi pemilik namun belum ada respons.', NULL, 'pending', NULL, NULL, '2026-04-22 08:09:09', '2026-04-22 08:09:09'),
(2, 3, 4, NULL, 11, 'keterlambatan', NULL, 'Pemilik item sangat lambat merespons konfirmasi sewa. Saya menunggu konfirmasi lebih dari 3 hari padahal sudah menghubungi via chat. Ini menyebabkan rencana acara saya terganggu.', NULL, 'diproses', 'Terima kasih atas laporannya. Kami telah menghubungi pemilik item dan mengingatkan kewajiban untuk merespons permintaan sewa dalam 24 jam. Jika masalah berlanjut, mohon informasikan kembali.', NULL, '2026-04-22 08:09:09', '2026-04-22 08:09:09'),
(3, 4, NULL, NULL, NULL, 'penipuan', NULL, 'Saya mendapati ada pengguna yang menawarkan sewa di luar platform melalui DM Instagram dengan harga lebih murah, mengatasnamakan salah satu pemilik item di aplikasi ini. Diduga akun palsu/penipu.', NULL, 'selesai', 'Laporan telah kami investigasi. Akun yang dimaksud telah kami nonaktifkan dan dilaporkan ke pihak terkait. Kami menghimbau seluruh pengguna untuk hanya bertransaksi melalui platform resmi IPB Rental. Terima kasih atas kepeduliannya.', NULL, '2026-04-22 08:09:09', '2026-04-22 08:09:09');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `nim` varchar(255) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `whatsapp` varchar(255) DEFAULT NULL,
  `lokasi` varchar(255) DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `role` enum('user','admin') NOT NULL DEFAULT 'user',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `name`, `nim`, `email`, `whatsapp`, `lokasi`, `avatar`, `role`, `email_verified_at`, `password`, `remember_token`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'Administrator', NULL, 'admin@ipbrental.ac.id', '081234567890', 'IPB Dramaga, Bogor', NULL, 'admin', NULL, '$2y$12$GZLGIz3siXCt6xMNsxTKhuIaOTBzV.bDHc6kDLJJ11I13Lh/6KDsm', NULL, NULL, '2026-04-22 08:09:07', '2026-04-22 08:09:07'),
(2, 'Budi Santoso', 'G1401211001', 'user@ipbrental.ac.id', '081111111111', 'Dramaga', NULL, 'user', NULL, '$2y$12$CL.hWEhp21nJSXHbCOn80.7xyG04B8e3B1uroVdWWqug15fR176DG', NULL, NULL, '2026-04-22 08:09:07', '2026-04-22 08:09:07'),
(3, 'Siti Rahayu', 'G1401211002', 'siti@students.ipb.ac.id', '082222222222', 'Babakan', NULL, 'user', NULL, '$2y$12$KbFFDiIur6RrJdpHks/DOeNware.sw9VYsGlq33SA19VYf8k/Yj9K', NULL, NULL, '2026-04-22 08:09:07', '2026-04-22 08:09:07'),
(4, 'Ahmad Fauzi', 'G1401211003', 'ahmad@students.ipb.ac.id', '083333333333', 'Cibanteng', NULL, 'user', NULL, '$2y$12$okoj8sqjNRT58fwwFTkXkOvGma79lZWYXMN0xZ0tjSSZsrB3d/tby', NULL, NULL, '2026-04-22 08:09:08', '2026-04-22 08:09:08'),
(5, 'Dewi Kurniawati', 'G1401211004', 'dewi@students.ipb.ac.id', '084444444444', 'Dramaga', NULL, 'user', NULL, '$2y$12$wEwaBws8c4XWY/xmqQOZe.29qg4sVaeiA065txiCOK/S5mPdeWEZO', NULL, NULL, '2026-04-22 08:09:08', '2026-04-22 08:09:08'),
(6, 'Rizky Pratama', 'G1401211005', 'rizky@students.ipb.ac.id', '085555555555', 'Ciomas', NULL, 'user', NULL, '$2y$12$oRt3Ehq735jRq1CUp51ZBeWPSiouLzhJgZP9iBl48qFrR6PfJ.7Ua', NULL, NULL, '2026-04-22 08:09:08', '2026-04-22 08:09:08');

-- --------------------------------------------------------

--
-- Struktur dari tabel `verifications`
--

CREATE TABLE `verifications` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `tipe` enum('ktm','ktp') NOT NULL DEFAULT 'ktm',
  `file` varchar(255) NOT NULL,
  `status` enum('pending','verified','rejected') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `verifications`
--

INSERT INTO `verifications` (`id`, `user_id`, `tipe`, `file`, `status`, `created_at`, `updated_at`) VALUES
(1, 3, 'ktm', 'verifications/sample_ktm_siti.jpg', 'pending', '2026-04-22 08:09:09', '2026-04-22 08:09:09'),
(2, 4, 'ktm', 'verifications/sample_ktm_ahmad.jpg', 'verified', '2026-04-22 08:09:09', '2026-04-22 08:09:09'),
(3, 5, 'ktp', 'verifications/sample_ktp_dewi.jpg', 'rejected', '2026-04-22 08:09:09', '2026-04-22 08:09:09');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indeks untuk tabel `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `items_user_id_foreign` (`user_id`);

--
-- Indeks untuk tabel `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `messages_sender_id_foreign` (`sender_id`),
  ADD KEY `messages_receiver_id_foreign` (`receiver_id`),
  ADD KEY `messages_rental_id_foreign` (`rental_id`);

--
-- Indeks untuk tabel `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indeks untuk tabel `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indeks untuk tabel `rentals`
--
ALTER TABLE `rentals`
  ADD PRIMARY KEY (`id`),
  ADD KEY `rentals_item_id_foreign` (`item_id`),
  ADD KEY `rentals_penyewa_id_foreign` (`penyewa_id`),
  ADD KEY `rentals_pemilik_id_foreign` (`pemilik_id`);

--
-- Indeks untuk tabel `reports`
--
ALTER TABLE `reports`
  ADD PRIMARY KEY (`id`),
  ADD KEY `reports_reporter_id_foreign` (`reporter_id`),
  ADD KEY `reports_terlapor_id_foreign` (`terlapor_id`),
  ADD KEY `reports_item_id_foreign` (`item_id`),
  ADD KEY `reports_rental_id_foreign` (`rental_id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_nim_unique` (`nim`);

--
-- Indeks untuk tabel `verifications`
--
ALTER TABLE `verifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `verifications_user_id_foreign` (`user_id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `items`
--
ALTER TABLE `items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT untuk tabel `messages`
--
ALTER TABLE `messages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT untuk tabel `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT untuk tabel `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `rentals`
--
ALTER TABLE `rentals`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT untuk tabel `reports`
--
ALTER TABLE `reports`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `verifications`
--
ALTER TABLE `verifications`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `items`
--
ALTER TABLE `items`
  ADD CONSTRAINT `items_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_receiver_id_foreign` FOREIGN KEY (`receiver_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `messages_rental_id_foreign` FOREIGN KEY (`rental_id`) REFERENCES `rentals` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `messages_sender_id_foreign` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `rentals`
--
ALTER TABLE `rentals`
  ADD CONSTRAINT `rentals_item_id_foreign` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `rentals_pemilik_id_foreign` FOREIGN KEY (`pemilik_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `rentals_penyewa_id_foreign` FOREIGN KEY (`penyewa_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `reports`
--
ALTER TABLE `reports`
  ADD CONSTRAINT `reports_item_id_foreign` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `reports_rental_id_foreign` FOREIGN KEY (`rental_id`) REFERENCES `rentals` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `reports_reporter_id_foreign` FOREIGN KEY (`reporter_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reports_terlapor_id_foreign` FOREIGN KEY (`terlapor_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Ketidakleluasaan untuk tabel `verifications`
--
ALTER TABLE `verifications`
  ADD CONSTRAINT `verifications_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
