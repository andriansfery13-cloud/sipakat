<?php

namespace Database\Seeders;

use App\Models\Setting;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Superadmin User
        User::updateOrCreate(
            ['email' => 'superadmin@coretax.local'],
            [
                'name' => 'Super Administrator',
                'password' => Hash::make('password'),
                'role' => 'superadmin',
                'kecamatan_id' => null,
            ]
        );

        // 2. Create Default Kecamatan
        $kecamatan = \App\Models\Kecamatan::updateOrCreate(
            ['kode_kecamatan' => 'KEC-MKS-01'],
            [
                'nama_kecamatan' => 'Kecamatan Makassar',
                'alamat' => 'Jl. Urip Sumoharjo, Makassar',
            ]
        );

        // 3. Create Admin Kecamatan User
        User::updateOrCreate(
            ['email' => 'admin@makassar.local'],
            [
                'name' => 'Admin Makassar',
                'password' => Hash::make('password'),
                'role' => 'admin_kecamatan',
                'kecamatan_id' => $kecamatan->id,
            ]
        );

        // 4. Default global settings (kecamatan_id = null)
        Setting::setValue('company_npwp', '000000000000000');
        Setting::setValue('company_name', 'Sistem Pajak Coretax Terpusat');
    }
}
