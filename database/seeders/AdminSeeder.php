<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // Super Admin
        $admin = User::firstOrCreate(
            ['email' => 'admin@psycora.com'],
            [
                'name'     => 'Administrator',
                'password' => Hash::make('password'),
                'gender'   => 'L',
            ]
        );
        $admin->assignRole('admin');

        // Admin kedua
        $admin2 = User::firstOrCreate(
            ['email' => 'kepala@psycora.com'],
            [
                'name'     => 'Kepala Sekolah',
                'password' => Hash::make('password'),
                'gender'   => 'L',
            ]
        );
        $admin2->assignRole('admin');
    }
}