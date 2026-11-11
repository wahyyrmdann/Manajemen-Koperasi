<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Seed the admin account.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Administrator',
            'email' => env('ADMIN_EMAIL', 'admin@koperasi.test'),
            'password' => env('ADMIN_PASSWORD', 'password'),
            'role' => UserRole::Admin,
        ]);
    }
}

