<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name'      => 'Administrator',
            'nim'       => null,
            'email'     => 'admin@ipbrental.ac.id',
            'password'  => Hash::make('admin123'),
            'role'      => 'admin',
            'whatsapp'  => '081234567890',
            'lokasi'    => 'IPB Dramaga, Bogor',
        ]);

        $users = [
            ['name' => 'Budi Santoso',    'nim' => 'G1401211001', 'email' => 'user@ipbrental.ac.id',     'wa' => '081111111111', 'lok' => 'Dramaga'],
            ['name' => 'Siti Rahayu',     'nim' => 'G1401211002', 'email' => 'siti@students.ipb.ac.id',  'wa' => '082222222222', 'lok' => 'Babakan'],
            ['name' => 'Ahmad Fauzi',     'nim' => 'G1401211003', 'email' => 'ahmad@students.ipb.ac.id', 'wa' => '083333333333', 'lok' => 'Cibanteng'],
            ['name' => 'Dewi Kurniawati', 'nim' => 'G1401211004', 'email' => 'dewi@students.ipb.ac.id',  'wa' => '084444444444', 'lok' => 'Dramaga'],
            ['name' => 'Rizky Pratama',   'nim' => 'G1401211005', 'email' => 'rizky@students.ipb.ac.id', 'wa' => '085555555555', 'lok' => 'Ciomas'],
        ];

        foreach ($users as $u) {
            User::create([
                'name'     => $u['name'],
                'nim'      => $u['nim'],
                'email'    => $u['email'],
                'password' => Hash::make('user123'),
                'role'     => 'user',
                'whatsapp' => $u['wa'],
                'lokasi'   => $u['lok'],
            ]);
        }
    }
}
