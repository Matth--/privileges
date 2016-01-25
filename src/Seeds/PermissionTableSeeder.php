<?php

namespace MatthC\Privileges\Seeds;


use MatthC\Privileges\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionTableSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = config('privileges.permissions');

        foreach($permissions as $name => $description) {
            Permission::create([
                'name' => $name,
                'description' => $description,
            ]);
        }
    }
}
