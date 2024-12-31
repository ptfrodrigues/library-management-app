<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            'create_books',
            'edit_books',
            'delete_books',
            'create_authors',
            'edit_authors',
            'delete_authors',
            'view_users',
            'create_users',
            'edit_users',
            'delete_users',
            'access_dashboard',
            'force_delete',
            'view_catalog',
            'create_catalog',
            'edit_catalog',
            'delete_catalog',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        $roles = [
            'admin' => $permissions,
            'manager' => [
                'create_books', 'edit_books', 'delete_books',
                'create_authors', 'edit_authors', 'delete_authors',
                'view_users', 'create_users', 'edit_users', 'delete_users',
                'access_dashboard',
                'view_catalog', 'create_catalog', 'edit_catalog', 'delete_catalog',
            ],
            'librarian' => [
                'create_books', 'edit_books',
                'create_authors', 'edit_authors',
                'access_dashboard', 'view_users',
                'view_catalog',
            ],
            'member' => ['view_catalog'],
        ];

        foreach ($roles as $roleName => $rolePermissions) {
            $role = Role::create(['name' => $roleName]);
            $role->givePermissionTo($rolePermissions);
        }
    }
}
