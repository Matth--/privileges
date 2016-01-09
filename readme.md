#Privileges

This is a small role-permission integration for laravel projects. I know Entrust exists but i wanted to try it myself.

##Installation
Download the package into the vendor folder

```
composer require matthc/privileges
```

Add the Service Provider to config/app.php

```
...
MatthC\Privileges\PrivilegeServiceProvider::class,
...
```

publish files

```
$ php artisan vendor:publish
```

Run the migrations

```
$ php artisan migrate
```

Adjust the settings in config/privileges.php and run the following commands to add the roles and permissions you want.

```
$ php artisan privileges:db:seed
```

If you want to add users with specific roles, run the following command:

```
$ php artisan privileges:db:users
```

Add the trait to the usermodel

```
use MatthC\Privileges\Traits\PrivilegeUserTrait;

class User extends Authenticatable
{
    use PrivilegeUserTrait;
    
    ...
}
```

##Usage

###User has role
To check if a user has a specific role:

```
//one role
$user->hasRole('admin'); //returns true/false

//multiple roles
$user->hasRole(['admin', 'author']); //returns true if the user has one of these roles

//user must have all roles
$user->hasRole(['admin', 'author'], true);

```

###User has permission
To check if a user has a specific permission:

```
//one permission
$user->can('create_post');

//multiple permissions
$user->can(['create_post', 'update_post']);

//all true
$user->can(['create_post', 'update_post'], true);

```
##Middleware
You can also use predefined middleware. Add the following lines to the route middleware array in app/Http/kernel.php

```
protected $routeMiddleware = [
        ...
        'role' => \MatthC\Privileges\Middleware\PrivilegeRoleMiddleware::class,
        'permission' => \MatthC\Privileges\Middleware\PrivilegePermissionMiddleware::class,
    ];
```

Example usage:

```
Route::group(['middleware' => ['role:admin']], function() {
    //add routes
});
```