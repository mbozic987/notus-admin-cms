<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create roles
        $adminRole = Role::create(['name' => 'admin']);
        $moderatorRole = Role::create(['name' => 'moderator']);
        Role::create(['name' => 'user']);

        // Define permissions
        $permissions = [
            'create user',
            'update user',
            'delete user',
            'create product',
            'update product',
            'delete product',
            'create category',
            'update category',
            'delete category',
            'update comment',
            'delete comment'
        ];

        // Create permissions
        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Assign permissions to roles
        $adminRole->syncPermissions(Permission::all());
        $moderatorRole->syncPermissions(['update comment', 'delete comment']);
    }
}
