<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class AdminStaffTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         User::create([
            'name' => 'Admin User',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('123456'),
            'phone_number' => '0123456789',
            'status' => 'pending',
            'avatar' => '',
            'address' => 'Quan 8, HCM City',
            'role_id' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        User::create([
            'name' => 'Staff User',
            'email' => 'staff@gmail.com',
            'password' => bcrypt('123456'),
            'phone_number' => '0123456788',
            'status' => 'pending',
            'avatar' => '',
            'address' => 'Quan 8, HCM City',
            'role_id' => 2,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
