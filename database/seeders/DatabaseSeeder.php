<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        // Membuat user Admin
        User::create([
            'id_pengguna' => 'ADM001', // Kolom kustom Anda
            'name' => 'Administrator',
            'email' => 'admin@gmail.com',
            'peran' => 'admin', // Kolom kustom Anda
            'email_verified_at' => now(),
            'password' => Hash::make('password'), // Password default, jangan lupa di-hash!
            'foto_profil' => null, // Kolom kustom Anda, bisa diisi null atau path gambar
            'remember_token' => \Illuminate\Support\Str::random(10),
        ]);

        // Membuat user Staff (sebagai contoh kedua)
        User::create([
            'id_pengguna' => 'STF001',
            'name' => 'Staff Gudang',
            'email' => 'staff@gmail.com',
            'peran' => 'staff',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'foto_profil' => null,
            'remember_token' => \Illuminate\Support\Str::random(10),
        ]);
    }
}