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

        // Membuat user superadmin
         User::create([
            'id_pengguna' => 'SADM999', 
            'name' => 'Super Admin',
            'email' => 'superadmin@gmail.com',
            'peran' => 'superadmin', 
            'email_verified_at' => now(),
            'password' => Hash::make('MZMsuperadmin1'), 
            'foto_profil' => null, 
            'remember_token' => \Illuminate\Support\Str::random(10),
        ]);

        // Membuat user Admin
        User::create([
            'id_pengguna' => 'ADM999', 
            'name' => 'Administrator',
            'email' => 'admin@gmail.com',
            'peran' => 'admin', 
            'email_verified_at' => now(),
            'password' => Hash::make('password'), 
            'foto_profil' => null, 
            'remember_token' => \Illuminate\Support\Str::random(10),
        ]);

        // Membuat user pegawai
        User::create([
            'id_pengguna' => 'PGW999',
            'name' => 'Pegawai',
            'email' => 'pegawai@gmail.com',
            'peran' => 'pegawai',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'foto_profil' => null,
            'remember_token' => \Illuminate\Support\Str::random(10),
        ]);
    }
}