<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Seed the application's roles and permissions.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Permissions (CRUD on core resources)
        $permissions = [
            // Users
            'user.view',
            'user.create',
            'user.update',
            'user.delete',
            // Projects
            'project.view',
            'project.create',
            'project.update',
            'project.archive',
            // Tickets
            'ticket.view',
            'ticket.create',
            'ticket.update',
            'ticket.assign',
            'ticket.close',
            // Admin Area Access
            'admin.access',
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm]);
        }

        // Roles
        $roles = [
            'admin' => [
                'admin.access',
                'user.view',
                'user.create',
                'user.update',
                'user.delete',
                'project.view',
                'project.create',
                'project.update',
                'project.archive',
                'ticket.view',
                'ticket.create',
                'ticket.update',
                'ticket.assign',
                'ticket.close',
            ],
            'project_manager' => [
                'project.view',
                'project.create',
                'project.update',
                'ticket.view',
                'ticket.create',
                'ticket.update',
                'ticket.assign',
                'ticket.close',
            ],
            'agent' => [
                'ticket.view',
                'ticket.create',
                'ticket.update',
                'ticket.close',
            ],
            'reporter' => [
                'ticket.view',
                'ticket.create',
            ],
        ];

        foreach ($roles as $roleName => $rolePerms) {
            $role = Role::firstOrCreate(['name' => $roleName]);
            $role->syncPermissions($rolePerms);
        }
    }
}
