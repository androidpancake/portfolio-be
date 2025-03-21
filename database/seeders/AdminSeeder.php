<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'admin',
            'user_id' => 'admin-portfolio',
            'username' => 'adminpfe',
            'is_approved' => '1',
            'password' => 'Admin123',
        ]);
    }
}
