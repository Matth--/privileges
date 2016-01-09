<?php

namespace MatthC\Privileges\Seeds;

use MatthC\Privileges\Models\Role;
use Illuminate\Database\Seeder;
use App\User;

class UsersAndUserRolesTableSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = config('privileges.users');

        foreach($users as $user_to_create) {
            $user = User::create([
                'name' => $user_to_create['name'],
                'email' => $user_to_create['email'],
                'password' => bcrypt($user_to_create['password']),
            ]);

            foreach($user_to_create['roles'] as $role_to_add) {
                $role = Role::where('name', $role_to_add)->first();

                if($role)
                {
                    $user->roles()->attach($role->id);
                }
            }
        }


    }
}