<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    //Run Database Seeder
    public function run(): void
    {
        $users = [
            [
                'name' => 'Admin',
                'email' => 'adminhelp@help.desk',
                'role' => 'admin',
            ],

            [
                'name' => 'Support Agent One',
                'email' => 'support1@help.desk',
                'role' => 'support',
            ],

            [
                'name' => 'Support Agent Two',
                'email' => 'support2@help.desk',
                'role' => 'support',
            ],
        ];

        foreach ($users as $user){
            User::updateOrCreate(
                ['email' => $user['email']],
                [
                    ...$user,
                    'password' => Hash::make('password'),
                    'email_verified_at' => now(),
                ]
            );
        }

        User::factory()
        ->count(10)
        ->create();
    }
}
