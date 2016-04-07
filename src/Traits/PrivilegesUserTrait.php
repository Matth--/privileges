<?php

namespace MatthC\Privileges\Traits;

use MatthC\Privileges\Models\Role;
use Illuminate\Cache\TaggableStore;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;

/**
 * Class PrivilegesUserTrait
 * @package MatthC\Privileges\Traits
 *
 * @author Matthieu Calie <matthieu.calie@gmail.com>
 */
trait PrivilegesUserTrait
{
    protected $cacheName = 'privileges_user_roles';

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
            }
            return $requireAll;
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
            foreach ($this->cachedRoles() as $user_role)
            {
                $cachedPermissions = $user_role->cachedPermissions();
                foreach ($cachedPermissions as $perm)
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
        if(Cache::getStore() instanceof TaggableStore) {
            $primaryKeyName = $this->primaryKey;
            $cacheKey = 'roles_for_user'.$this->$primaryKeyName;
            return Cache::tags($this->cacheName)->remember($cacheKey, 60, function () {
                return $this->roles()->get();
            });
        }

        return $this->roles()->get();
    }

    /**
     * Flush the cached roles when new cache is saved
     *
     * @param array $options
     */
    public function save(array $options = [])
    {
        parent::save($options);

        if(Cache::getStore() instanceof TaggableStore) {
            Cache::tags($this->cacheName)->flush();
        }
    }

    /**
     * Flush the cached roles when new cache is deleted
     *
     * @param array $options
     */
    public function delete(array $options = [])
    {
        parent::delete($options);

        if(Cache::getStore() instanceof TaggableStore) {
            Cache::tags($this->cacheName)->flush();
        }
    }

    /**
     * Flush the cached roles when new cache is restored
     * restore = undo soft deleting
     */
    public function restore()
    {
        parent::restore();

        if(Cache::getStore() instanceof TaggableStore) {
            Cache::tags($this->cacheName)->flush();
        }
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
     * Attach a Role
     *
     * @param mixed $role
     */
    public function attachRole($role)
    {
        if(is_object($role)) {
            $role = $role->getKey();
        }
        if(is_array($role)) {
            $role = $role['id'];
        }
        $this->roles()->attach($role);
    }

    /**
     * Detach a Role
     *
     * @param mixed $role
     */
    public function detachRole($role)
    {
        if (is_object($role)) {
            $role = $role->getKey();
        }
        if (is_array($role)) {
            $role = $role['id'];
        }
        $this->roles()->detach($role);
    }

    /**
     * Attach multiple roles
     *
     * @param mixed $roles
     */
    public function attachRoles($roles)
    {
        foreach ($roles as $role) {
            $this->attachRole($role);
        }
    }

    /**
     * Detach multiple roles
     *
     * @param mixed $roles
     */
    public function detachRoles($roles = null)
    {
        if (!$roles) $roles = $this->roles()->get();

        foreach ($roles as $role) {
            $this->detachRole($role);
        }
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
