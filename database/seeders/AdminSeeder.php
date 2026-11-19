<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@elearning.test'],
            ['name' => 'Admin', 'password' => Hash::make('Admin123!'), 'role' => 'admin']
        );

        User::updateOrCreate(
            ['email' => 'student@elearning.test'],
            ['name' => 'Siswa Demo', 'password' => Hash::make('Student123!'), 'role' => 'student']
        );
    }
}
