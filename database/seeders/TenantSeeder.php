<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\Kecamatan;
use App\Models\User;
use App\Models\Employee;
use App\Models\Setting;

class TenantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Create Kecamatan Ciparay
        $ciparay = Kecamatan::firstOrCreate(['name' => 'Kecamatan Ciparay']);

        // 2. Assign all existing users to Ciparay as admin_kecamatan
        User::where('role', 'admin_kecamatan')->orWhereNull('role')->update([
            'role' => 'admin_kecamatan',
            'kecamatan_id' => $ciparay->id,
        ]);

        // 3. Assign all existing employees to Ciparay
        Employee::whereNull('kecamatan_id')->update([
            'kecamatan_id' => $ciparay->id,
        ]);

        // 4. Assign all existing settings to Ciparay
        Setting::whereNull('kecamatan_id')->update([
            'kecamatan_id' => $ciparay->id,
        ]);

        // 5. Create Superadmin user
        User::updateOrCreate(
            ['email' => 'superadmin@coretax.local'],
            [
                'name' => 'Super Administrator',
                'password' => Hash::make('password'),
                'role' => 'superadmin',
                'kecamatan_id' => null,
            ]
        );
    }
}
