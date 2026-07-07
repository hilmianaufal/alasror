<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Buat role admin jika belum ada
        $adminRole = Role::firstOrCreate([
            'name' => 'admin',
            'guard_name' => 'web',
        ]);

        // Buat user admin
        $admin = User::updateOrCreate(
            [
                'email' => 'admin@alasror.com',
            ],
            [
                'name' => 'Administrator',
                'phone' => '081234567890',
                'password' => 'admin123', // otomatis di-hash oleh model
                'is_active' => true,
                'notes' => 'Super Administrator',
            ]
        );

        // Berikan role admin
        $admin->syncRoles([$adminRole]);
    }
}
