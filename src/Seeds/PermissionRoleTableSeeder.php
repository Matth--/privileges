<?php

namespace MatthC\Privileges\Seeds;

use MatthC\Privileges\Models\Permission;
use MatthC\Privileges\Models\Role;
use Illuminate\Database\Seeder;

class PermissionRoleTableSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role_permissions = config('privileges.role_permission');

        foreach($role_permissions as $role => $permissions) {
            $role = Role::where('name' , $role)->first();

            if($role) {
                foreach($permissions as $permission) {
                    $permission_to_attach = Permission::where('name', $permission)->first();
                    if($permission_to_attach) {
                        $role->permissions()->attach($permission_to_attach->id);
                    }
                }
            }
        }
    }
}