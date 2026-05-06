<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Security', // Ganti dengan nama asli
            'email' => 'security@example.com',
            'role' => 'security',
            'password' => Hash::make('1111'), // PIN Security
        ]);

        User::create([
            'name' => 'Bongkar/Muat', // Ganti dengan nama asli
            'email' => 'loading@example.com',
            'role' => 'loading',
            'password' => Hash::make('2222'), // PIN Loading
        ]);

        User::create([
            'name' => 'Officer TTB/SJ', // Ganti dengan nama asli
            'email' => 'ttb@example.com',
            'role' => 'ttb',
            'password' => Hash::make('3333'), // PIN TTB
        ]);

        User::create([
            'name' => 'Admin', // Ganti dengan nama asli
            'email' => 'admin@example.com',
            'role' => 'admin',
            'password' => Hash::make('9999'), // PIN Admin
        ]);
    }
}