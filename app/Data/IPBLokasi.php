<?php

namespace App\Data;

class IPBLokasi
{
    public static function all(): array
    {
        return [
            'IPB Dramaga' => [
                // Fakultas
                'Fakultas Pertanian (FAPERTA)',
                'Fakultas Perikanan dan Ilmu Kelautan (FPIK)',
                'Fakultas Peternakan (FAPET)',
                'Fakultas Kehutanan dan Lingkungan (FAHUTAN)',
                'Fakultas Teknik dan Teknologi (FATETA)',
                'Fakultas Matematika dan IPA (FMIPA)',
                'Fakultas Ekonomi dan Manajemen (FEM)',
                'Fakultas Ekologi Manusia (FEMA)',
                'Fakultas Kedokteran',
                'Sekolah Kedokteran Hewan dan Biomedis',
                'Sekolah Sains Data, Matematika, dan Informatika',
                // Area umum
                'Asrama TPB Putra',
                'Asrama TPB Putri',
                'Asrama Baraanangsana',
                'Asrama Sylvalestari',
                'Perpustakaan IPB',
                'Masjid Al-Hurriyyah',
                'Student Center (SC)',
                'Gymnasium IPB',
                'Rektorat IPB',
            ],
            'IPB Baranangsiang' => [
                'IPB Baranangsiang',
            ],
            'IPB Gunung Gede' => [
                'Sekolah Bisnis IPB',
            ],
            'IPB Cilibende (Vokasi)' => [
                'Gedung CA Vokasi',
                'Gedung CB Vokasi',
                'Gedung Zeta Vokasi',
                'Gedung Prima Vokasi',
            ],
            'IPB Taman Kencana' => [
                'IPB Taman Kencana',
            ],
            'IPB Sukabumi' => [
                'IPB Sukabumi',
            ],
        ];
    }

    public static function flat(): array
    {
        $flat = [];
        foreach (self::all() as $kampus => $lokasis) {
            foreach ($lokasis as $lokasi) {
                $flat[] = $lokasi;
            }
        }
        return $flat;
    }

    public static function findKampus(string $lokasi): ?string
    {
        foreach (self::all() as $kampus => $lokasis) {
            if (in_array($lokasi, $lokasis) || $kampus === $lokasi) {
                return $kampus;
            }
        }
        return null;
    }
}