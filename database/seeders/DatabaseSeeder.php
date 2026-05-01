<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'phone' => '+212667676927',
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'ilyas',
            'email' => 'ilyas@gmail.com',
            'password' => Hash::make('ilyas123'),
            'role' => 'user',
            'phone' => '+212667676927',
            'email_verified_at' => now(),
        ]);
    }
}
