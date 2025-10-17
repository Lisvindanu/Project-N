<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin utama
        User::firstOrCreate(
            ['email' => 'admin@projectN'],
            [
                'name' => 'Admin ProjectN',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );

        // Admin kedua
        User::firstOrCreate(
            ['email' => 'irvanbayu@admin.com'],
            [
                'name' => 'Irvan Bayu',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );
    }
}
