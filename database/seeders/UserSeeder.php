<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin user
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@medicstore.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Pharmacist user
        User::create([
            'name' => 'Pharmacist User',
            'email' => 'pharmacist@medicstore.com',
            'password' => Hash::make('password'),
            'role' => 'pharmacist',
        ]);

        // Patient users
        User::create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => Hash::make('password'),
            'role' => 'patient',
        ]);

        User::create([
            'name' => 'Jane Smith',
            'email' => 'jane@example.com',
            'password' => Hash::make('password'),
            'role' => 'patient',
        ]);
    }
}
