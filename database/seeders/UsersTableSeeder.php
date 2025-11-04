<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Nguyen Van A',
            'email' => 'nguyenvana@gmail.com',
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
            'name' => 'Nguyen Van B',
            'email' => 'nguyenvanb@gmail.com',
            'password' => bcrypt('123456'),
            'phone_number' => '0123456788',
            'status' => 'pending',
            'avatar' => '',
            'address' => 'Quan 8, HCM City',
            'created_at' => now(),
            'updated_at' => now(),'role_id' => 2,

        ]);
        User::create([
            'name' => 'Nguyen Van C',
            'email' => 'nguyenvanc@gmail.com',
            'password' => bcrypt('123456'),
            'phone_number' => '0123456787',
            'status' => 'pending',
            'avatar' => '',
            'address' => 'Quan 8, HCM City',
            'role_id' => 3,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
