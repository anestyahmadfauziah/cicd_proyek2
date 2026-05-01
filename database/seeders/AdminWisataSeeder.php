<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AdminWisata;
use Illuminate\Support\Facades\Hash;

class AdminWisataSeeder extends Seeder
{
    public function run(): void
    {
    AdminWisata::create([
    'username' => 'admin',
    'password' => Hash::make('AdminWst1!'),
    'role' => 'admin',
    'status' => 'aktif',
    'created_at' => now(),
    'updated_at' => now(),
]);
    }
}