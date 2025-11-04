<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;

class RolePermisstionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminRole=Role::where('name', 'admin')->first();
        $staffRole=Role::where('name', 'staff')->first();
        $customerRole=Role::where('name', 'customer')->first();
        $allPermissions=Permission::all();
        // Assign all permissions to admin
        $adminRole->permissions()->sync($allPermissions);
        // Assign limited permissions to staff
        $staffPermissions = $allPermissions->whereIn('name', [
            'manage_products',
            'manage_orders',
            'manage_categories',
            'manage_vouchers',
        ]);
        $staffRole->permissions()->sync($staffPermissions);
        // Customers get no special permissions
        $customerRole->permissions()->sync([]);
    }
}