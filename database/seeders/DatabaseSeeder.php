<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            EmailVerificationSeeder::class,  // Jalankan terlebih dahulu untuk whitelist email
            UserSeeder::class,
            ItemSeeder::class,
            RentalSeeder::class,
            MessageSeeder::class,
            ReportSeeder::class,
            VerificationSeeder::class,
        ]);
    }
}
