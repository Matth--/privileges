<?php

namespace MatthC\Privileges\Seeds;

use MatthC\Privileges\Models\Role;
use Illuminate\Database\Seeder;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = config('privileges.roles');

        foreach($roles as $role) {
            Role::create([
                'name' => $role,
                'description' => $role . ' role',
            ]);
        }
    }
}