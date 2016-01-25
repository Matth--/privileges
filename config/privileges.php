<?php

return [
    /*
    | Roles array
    | Here you can define all the roles that will be used
    | in this application.
     */
    'roles' => [
        'admin',
        'author',
        'user'
    ],

    /*
    | Permissions array
    | This array has all permissions. If you want more, just
    | add them to this array. If you want to add permissions
    | after you initialized the package, you will have to add
    | them like a normal model add (Permission::create([]);)
     */
    'permissions' => [
        'manage_users'          => 'User can manage users',
        'manage_roles'          => 'User can manage roles',
        'manage_permissions'    => 'user can manage permissions',
        'manage_posts'          => 'User can manage blog posts',
    ],

    /*
    | Permission_role array
    | this array is a 2-dimensional array where you can link
    | the permissions to the specific role.
     */
    'role_permission' => [
        'admin'     => ['manage_users', 'manage_roles', 'manage_permissions'],
        'author'    => [],
        'user'      => [],
    ],

    /*
    | Some default users
    | to add one, add a name, email, password and the roles
     */
    'users' => [
        [
            'name' => 'administrator',
            'email' => 'admin@example.com',
            'password' => 'privilegesFTW!',
            'roles' => ['admin'],
        ],
        [
            'name' => 'author',
            'email' => 'author@example.com',
            'password' => 'privilegesFTW!',
            'roles' => ['author'],
        ],
        [
            'name' => 'user',
            'email' => 'user@example.com',
            'password' => 'privilegesFTW!',
            'roles' => ['user'],
        ],
    ],
];