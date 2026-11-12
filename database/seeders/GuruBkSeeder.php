<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Classes;

class GuruBkSeeder extends Seeder
{
    public function run(): void
    {
        $guruList = [
            [
                'name'     => 'Budi Wahyono, S.Pd',
                'email'    => 'budi.guru@psycora.com',
                'nip'      => 'BK-001',
            ],
            [
                'name'     => 'Siti Rahayu, M.Psi',
                'email'    => 'siti.guru@psycora.com',
                'nip'      => 'BK-002',
            ],
            [
                'name'     => 'Guru BK Psycora, S.Psi',
                'email'    => 'gurubk@psycora.com',
                'nip'      => 'BK-003',
            ],
        ];

        foreach ($guruList as $data) {
            $guru = User::firstOrCreate(
                ['email' => $data['email']],
                [
                    'name'     => $data['name'],
                    'password' => Hash::make('password'),
                    'nip'      => $data['nip'],
                    'gender'   => 'L',
                ]
            );

            $guru->assignRole('guru_bk');
        }
    }
}
