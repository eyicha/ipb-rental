<?php

namespace Database\Seeders;

use App\Models\EmailVerification;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EmailVerificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $emails = [
            // Student emails (dummy)
            ['email' => 'erisa@apps.ipb.ac.id', 'nim' => 'J0403241095', 'name' => 'Erisa Salsabila', 'is_verified' => false],
            ['email' => 'john@apps.ipb.ac.id', 'nim' => 'J0403241001', 'name' => 'John Doe', 'is_verified' => false],
            ['email' => 'jane@apps.ipb.ac.id', 'nim' => 'J0403241002', 'name' => 'Jane Smith', 'is_verified' => false],
            ['email' => 'ahmad@apps.ipb.ac.id', 'nim' => 'J0403241003', 'name' => 'Ahmad Rizki', 'is_verified' => false],
            ['email' => 'siti@apps.ipb.ac.id', 'nim' => 'J0403241004', 'name' => 'Siti Rahmawati', 'is_verified' => false],
            
            // Admin emails
            ['email' => 'admin@apps.ipb.ac.id', 'nim' => 'ADMIN001', 'name' => 'Administrator', 'is_verified' => false],
            ['email' => 'superadmin@apps.ipb.ac.id', 'nim' => 'SADMIN001', 'name' => 'Super Admin', 'is_verified' => false],
            
            // More dummy students
            ['email' => 'budi@apps.ipb.ac.id', 'nim' => 'J0403241005', 'name' => 'Budi Santoso', 'is_verified' => false],
            ['email' => 'ani@apps.ipb.ac.id', 'nim' => 'J0403241006', 'name' => 'Ani Wijaya', 'is_verified' => false],
            ['email' => 'rini@apps.ipb.ac.id', 'nim' => 'J0403241007', 'name' => 'Rini Handoko', 'is_verified' => false],
            ['email' => 'eko@apps.ipb.ac.id', 'nim' => 'J0403241008', 'name' => 'Eko Putro', 'is_verified' => false],
            ['email' => 'dina@apps.ipb.ac.id', 'nim' => 'J0403241009', 'name' => 'Dina Kusuma', 'is_verified' => false],
        ];

        foreach ($emails as $email) {
            EmailVerification::updateOrCreate(
                ['email' => $email['email']],
                $email
            );
        }

        $this->command->info('Email verification whitelist created successfully!');
    }
}
