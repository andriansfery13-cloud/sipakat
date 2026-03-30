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
        // Create admin user
        User::updateOrCreate(
            ['email' => 'admin@coretax.local'],
            [
                'name' => 'Administrator',
                'password' => Hash::make('password'),
            ]
        );

        // Default settings
        Setting::setValue('company_npwp', '000000000000000');
        Setting::setValue('company_name', 'PT Contoh Perusahaan');
    }
}
