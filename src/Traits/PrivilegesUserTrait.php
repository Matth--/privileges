<?php

namespace MatthC\Privileges\Traits;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use MatthC\Privileges\Models\Role;

trait PrivilegesUserTrait
{
    /**
     * Check if a user has a certain Role
     *
     * @param $name
     * @param bool $requireAll
     * @return bool
     */
    public function hasRole($name, $requireAll = false)
    {
        if(!is_array($name)) {
            foreach($this->cachedRoles() as $role)
            {
                if($role->name == $name) {
                    return true;
                }
            }
        } else {
            foreach($name as $single_role)
            {
                $hasRole = $this->hasRole($single_role);
                if($hasRole && !$requireAll) {
                    return true;
                } elseif (!$hasRole && $requireAll) {
                    return false;
                }
                return $requireAll;
            }
        }
        return false;
    }

    /**
     * Check if a user has a specific permission
     *
     * @param $permission
     * @param bool $requireAll
     * @return bool
     */
    public function can($permission, $requireAll = false)
    {
        if(!is_array($permission)) {
            foreach ($this->cachedRoles() as $role)
            {
                foreach ($role->cachedPermissions() as $perm)
                {
                    if (str_is( $permission, $perm->name) ) {
                        return true;
                    }
                }
            }
        } else {
            foreach ($permission as $permissionName)
            {
                $hasPermission = $this->can($permissionName);
                if ($hasPermission && !$requireAll) {
                    return true;
                } elseif (!$hasPermission && $requireAll) {
                    return false;
                }
            }
            return $requireAll;
        }
        return false;
    }

    /**
     * Get Cached roles if they exist
     *
     * @return mixed
     */
    public function cachedRoles()
    {
        $cacheKey = 'priviliges_user_roles'.$this->primaryKey;
        return Cache::tags('user_roles')->remember($cacheKey, 60, function () {
            return $this->roles()->get();
        });
    }

    /**
     * Flush the cached roles when new cache is saved
     *
     * @param array $options
     */
    public function save(array $options = [])
    {   //both inserts and updates
        parent::save($options);
        Cache::tags('user_roles')->flush();
    }

    /**
     * Flush the cached roles when new cache is deleted
     *
     * @param array $options
     */
    public function delete(array $options = [])
    {
        parent::delete($options);
        Cache::tags('user_roles')->flush();
    }

    /**
     * Flush the cached roles when new cache is restored
     * restore = undo soft deleting
     */
    public function restore()
    {
        parent::restore();
        Cache::tags('user_roles')->flush();
    }

    /**
     * Override the boot method
     * Event listener to remove many-to-many relationship with roles
     *
     * @return void|bool
     */
    public static function boot()
    {
        parent::boot();
        static::deleting(function($user) {
            if (!method_exists(Config::get('auth.model'), 'bootSoftDeletes')) {
                $user->roles()->sync([]);
            }
            return true;
        });
    }

    /**
     * Get the roles
     *
     * @return mixed
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }
}
