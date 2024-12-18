<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(
            RolePermissionSeeder::class,
            AdminUserSeeder::class
        );

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'manager@library.com',
        ])->assignRole('manager');
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'librarian@library.com',
        ])->assignRole('librarian');
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'member@library.com',
        ])->assignRole('member');
    }
}
