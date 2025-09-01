<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user if it doesn't exist
        User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'email' => 'admin@example.com',
                'password' => Hash::make('password'),
                'is_admin' => true,
                'email_verified_at' => now(),
            ]
        );
        $admin = User::where('email', 'admin@example.com')->first();
        $role = Role::firstOrCreate(['name' => 'admin']);
        if (!$admin->hasRole('admin')) {
            $admin->assignRole($role);
        }

        $this->command->info('Admin user created with email: admin@example.com and password: password (role: admin)');
    }
}
