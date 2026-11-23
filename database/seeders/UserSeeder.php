<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Seed the canonical TSA user account with Breeze authentication credentials.
     */
    public function run(): void
    {
        User::query()->updateOrCreate(
            ['username' => 'Kat.1na'],
            [
                'full_name' => 'Katarina Ivanov',
                'email' => 'katarina.ivanov@gmail.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
            ]
        );
    }
}
