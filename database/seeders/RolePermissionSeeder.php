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
            'view_loan',
            'start_loan',
            'end_loan',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Create roles and assign permissions
        $roles = [
            'admin' => $permissions,
            'manager' => [
                'create_books', 'edit_books', 'delete_books',
                'create_authors', 'edit_authors', 'delete_authors',
                'view_users', 'create_users', 'edit_users', 'delete_users',
                'access_dashboard', 'view_loan',
            ],
            'librarian' => [
                'create_books', 'edit_books',
                'create_authors', 'edit_authors',
                'access_dashboard', 'view_users', 'view_loan',
            ],
            'member' => ['view_loan', 'start_loan', 'end_loan'],
        ];

        foreach ($roles as $roleName => $rolePermissions) {
            $role = Role::create(['name' => $roleName]);
            $role->givePermissionTo($rolePermissions);
        }
    }
}

