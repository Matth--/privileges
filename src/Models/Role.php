<?php

namespace MatthC\Privileges\Models;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use MatthC\Privileges\Contracts\RoleInterface;

/**
 * Class Role
 * @package MatthC\Privileges\Models
 *
 * @author Matthieu Calie <matthieu.calie@gmail.com>
 */
class Role extends PrivilegesModel implements RoleInterface
{
    /**
     * @var array
     */
    protected $fillable = ['name', 'description'];

    /**
     * @var string
     */
    protected $cacheName = 'privileges_role_permissions';

    /**
     * Cache the roles
     *
     * @return mixed
     */
    public function cachedPermissions()
    {
        if($this->isTagCacheAllowed()) {
            $primaryKeyName = $this->primaryKey;

            $cacheKey = 'permissions_for_role'.$this->$primaryKeyName;
            return Cache::tags($this->cacheName)->remember($cacheKey, 60, function () {
                return $this->permissions()->get();
            });
        }

        return $this->permissions()->get();
    }


    /**
     * Check if the role has a permission
     *
     * @param $name
     * @param $requireAll
     * @return bool
     */
    public function hasPermission($name, $requireAll = null)
    {
        if(!is_array($name)) {
            foreach($this->cachedPermissions() as $permission)
            {
                if($permission->name == $name) {
                    return true;
                }
            }
        } else {
            foreach($name as $single_permission)
            {
                $hasPermission = $this->hasPermission($single_permission);
                if($hasPermission && !$requireAll) {
                    return true;
                } elseif (!$hasPermission && $requireAll) {
                    return false;
                }
                return $requireAll;
            }
        }
        return false;
    }

    /**
     * Flush the cached roles when new cache is saved
     *
     * @param array $options
     * @return bool|void
     */
    public function save(array $options = [])
    {
        if(!parent::save($options)) {
            return false;
        }

        $this->flushCache();
        return true;
    }

    /**
     * Flush the cached roles when role is deleted
     *
     * @param array $options
     * @return bool|null|void
     */
    public function delete(array $options = [])
    {
        if(!parent::delete($options)) {
            return false;
        }
        
        $this->flushCache();
        return true;
    }

    /**
     * Flush the cached roles when new cache is restored
     * restore = undo soft deleting
     */
    public function restore()
    {
        if(!parent::restore()) {
            return false;
        }

        $this->flushCache();
        return true;
    }

    /**
     * Attach al the permissions or detach all the currently attached ones
     *
     * @param mixed $permissions
     */
    public function savePermissions($permissions)
    {
        if(!empty($permissions)) {
            $this->permissions()->sync($permissions);
        } else {
            $this->permissions()->detach();
        }
    }

    /**
     * Attach a permission
     *
     * @param $permission
     */
    public function attachPermission($permission)
    {
        if (is_object($permission)) {
            $permission = $permission->getKey();
        }
        if (is_array($permission)) {
            $permission = $permission['id'];
        }
        $this->permissions()->attach($permission);
    }

    /**
     * Detach a permission
     *
     * @param $permission
     */
    public function detachPermission($permission)
    {
        if (is_object($permission))
            $permission = $permission->getKey();
        if (is_array($permission))
            $permission = $permission['id'];
        $this->perms()->detach($permission);
    }

    /**
     * Attach multiple permissions
     *
     * @param $permissions
     */
    public function attachPermissions($permissions)
    {
        foreach($permissions as $permission) {
            $this->attachPermission($permission);
        }
    }

    /**
     * Detach multiple permissions
     *
     * @param $permissions
     */
    public function detachPermissions($permissions)
    {
        foreach($permissions as $permission) {
            $this->detachPermission($permission);
        }
    }

    /**
     * Boot the role model
     * Attach event listener to remove the many-to-many records when trying to delete
     * Will NOT delete any records if the role model uses soft deletes.
     *
     * @return void|bool
     */
    public static function boot()
    {
        parent::boot();
        static::deleting(function(Role $role) {
            if (!method_exists(Role::class, 'bootSoftDeletes')) {
                $role->users()->sync([]);
                $role->permissions()->sync([]);
            }
            return true;
        });
    }

    /**
     * Get All attached permissions
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function permissions()
    {
        return $this->belongsToMany(Permission::class);
    }

    /**
     * Get all users with this role
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany(Config::get('auth.providers.users.model'));
    }
}
