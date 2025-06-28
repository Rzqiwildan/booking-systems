<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User; // Menggunakan model User standar Laravel
use Illuminate\Support\Facades\Hash;

class AdminFilamentSeeder extends Seeder
{
    public function run()
    {
        // Membuat akun admin untuk Filament
        User::create([
            'name' => 'ArfaIndonesia',
            'email' => 'arfatransportasi@gmail.com',  // Ganti dengan email admin Anda
            'password' => Hash::make('Perhatian01'),  // Ganti dengan password yang aman
        ]);
    }
}