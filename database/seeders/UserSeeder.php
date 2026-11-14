<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Seed the single canonical TSA1/TSA3 user record.
     */
    public function run(): void
    {
        User::query()->updateOrCreate(
            ['username' => 'Kat.1na'],
            [
                'full_name' => 'Katarina Ivanov',
                'email' => 'katarina.ivanov@gmail.com',
            ]
        );
    }
}
