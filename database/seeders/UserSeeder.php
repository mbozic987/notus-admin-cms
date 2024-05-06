<?php

namespace Database\Seeders;

use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        // Create 2 moderator users
        $moderators = User::factory(2)->create();
        foreach ($moderators as $moderator) {
            $moderator->assignRole('moderator');
        }

        // Create the remaining users
        $users = User::factory(7)->create();
        foreach ($users as $user) {
            $user->assignRole('user');
        }
    }
}
