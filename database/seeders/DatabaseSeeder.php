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
        $this->call([
            RolePermissionSeeder::class,
            AdminUserSeeder::class,
            AuthorSeeder::class,
            BookSeeder::class,
        ]);

        User::factory()->create([
            'name' => 'Manager User',
            'email' => 'manager@library.com',
        ])->assignRole('manager');
        User::factory()->create([
            'name' => 'Librarian User',
            'email' => 'librarian@library.com',
        ])->assignRole('librarian');
        User::factory()->create([
            'name' => 'Member User',
            'email' => 'member@library.com',
        ])->assignRole('member');
    }
}

