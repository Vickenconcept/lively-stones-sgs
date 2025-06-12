<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run()
    {
        $roles = [
            ['name' => 'super-admin', 'display_name' => 'Super Administrator'],
            ['name' => 'admin', 'display_name' => 'Administrator'],
            ['name' => 'teacher', 'display_name' => 'Teacher'],
            ['name' => 'student', 'display_name' => 'Student'],
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role['name']], $role);
        }
    }
} 