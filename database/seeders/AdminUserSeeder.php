<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        // Membuat akun admin
        $admin = User::create([
            'name' => 'ArfaIndonesia',
            'email' => 'arfatransportasi@gmail.com',
            'password' => Hash::make('Perhatian01'), // Gantilah dengan password yang aman
        ]);

        // Jika Anda menggunakan package seperti Spatie Laravel Permission untuk role:
        $admin->assignRole('admin');
    }
}