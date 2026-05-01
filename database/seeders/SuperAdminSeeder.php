<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SuperAdmin;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        SuperAdmin::create([
            'nama' => 'Superadmin', // wajib (NOT NULL)
            'username' => 'Superadmin', // optional
            'email' => 'superadmin@example.com',
            'password' => Hash::make('SuperAdm123!'),
            'role' => 'superadmin'
        ]);
    }
}