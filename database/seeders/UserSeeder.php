<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@fotobox.local'],
            [
                'name'     => 'Admin',
                'password' => Hash::make('admin'),
                'is_admin' => true,
            ]
        );

        User::firstOrCreate(
            ['email' => 'user@fotobox.local'],
            [
                'name'     => 'User',
                'password' => Hash::make('user'),
                'is_admin' => false,
            ]
        );
    }
}
