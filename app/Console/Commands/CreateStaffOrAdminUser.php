<?php

namespace App\Console\Commands;

use App\Models\Role;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class CreateStaffOrAdminUser extends Command
{
    protected $signature = 'user:create {role : admin|staff} {email : Email đăng nhập} {--name=} {--password=}';

    protected $description = 'Tạo tài khoản admin/nhân viên để đăng nhập trang quản trị';

    public function handle(): int
    {
        $roleName = (string) $this->argument('role');
        $email = (string) $this->argument('email');

        if (!in_array($roleName, ['admin', 'staff'], true)) {
            $this->error('Role chỉ nhận: admin hoặc staff');
            return self::FAILURE;
        }

        $name = (string) ($this->option('name') ?: 'New ' . ucfirst($roleName));
        $password = (string) ($this->option('password') ?: '');

        if ($password === '') {
            $password = (string) $this->secret('Nhập mật khẩu');
            $confirm = (string) $this->secret('Nhập lại mật khẩu');

            if ($password === '' || $password !== $confirm) {
                $this->error('Mật khẩu trống hoặc không khớp.');
                return self::FAILURE;
            }
        }

        if (User::where('email', $email)->exists()) {
            $this->error('Email đã tồn tại: ' . $email);
            return self::FAILURE;
        }

        $roleId = Role::where('name', $roleName)->value('id');
        if (!$roleId) {
            $this->error('Không tìm thấy role. Hãy chạy seed RolesTableSeeder trước.');
            return self::FAILURE;
        }

        User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
            'status' => 'active',
            'role_id' => $roleId,
            'activation_token' => null,
        ]);

        $this->info("Đã tạo tài khoản {$roleName}: {$email}");

        return self::SUCCESS;
    }
}
