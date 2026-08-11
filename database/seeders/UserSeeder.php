<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'full_name' => 'Sara Mohsen',
                'username' => 'sara',
                'email' => 'sara@example.com',
                'password' => Hash::make('password123'),
                'phone' => '0500000001',
                'avatar_url' => null,
            ],
            [
                'full_name' => 'Ahmed Ali',
                'username' => 'ahmed',
                'email' => 'ahmed@example.com',
                'password' => Hash::make('password123'),
                'phone' => '0500000002',
                'avatar_url' => null,
            ],
            [
                'full_name' => 'Nora Khalid',
                'username' => 'nora',
                'email' => 'nora@example.com',
                'password' => Hash::make('password123'),
                'phone' => '0500000003',
                'avatar_url' => null,
            ],
            [
                'full_name' => 'Faisal Saad',
                'username' => 'faisal',
                'email' => 'faisal@example.com',
                'password' => Hash::make('password123'),
                'phone' => '0500000004',
                'avatar_url' => null,
            ],
        ];

        foreach ($users as $user) {
            User::create($user);
        }
    }
}
