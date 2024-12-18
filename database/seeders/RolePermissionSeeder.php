<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear cached permissions to avoid conflicts
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $config = config('role_permission');
        $permissions = $config['permissions'];
        $roles = $config['roles'];

        DB::transaction(function () use ($permissions, $roles) {
            // Create permissions dynamically
            foreach ($permissions as $resource => $actions) {
                foreach ($actions as $action) {
                    Permission::updateOrCreate(['name' => $action]);
                }
            }

            // Create roles and assign permissions dynamically
            foreach ($roles as $roleName => $resources) {
                $role = Role::firstOrCreate(['name' => $roleName]);

                if ($resources === '*') {
                    // Grant all permissions to roles like admin
                    $role->givePermissionTo(Permission::all());
                } else {
                    // Gather permissions for the specific resources
                    $allowedPermissions = [];
                    foreach ($resources as $resource) {
                        if (isset($permissions[$resource])) {
                            $allowedPermissions = array_merge($allowedPermissions, $permissions[$resource]);
                        }
                    }
                    $role->syncPermissions($allowedPermissions);
                }
            }
        });
    }
}
