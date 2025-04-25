<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $email = 'reyhan.123140022@student.itera.ac.id';

        // Check if admin already exists
        if (!DB::table('users')->where('email', $email)->exists()) {
            DB::table('users')->insert([
                'name' => 'Reyhan Capri Moraga',
                'email' => $email,
                'password' => Hash::make('admin1'),
                'role' => 'admin',
                'no_hp' => '082269283309',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
