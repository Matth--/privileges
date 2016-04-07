<?php

namespace MatthC\Privileges\Models;

use Illuminate\Cache\TaggableStore;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Role
 * @package MatthC\Privileges\Models
 *
 * @author Matthieu Calie <matthieu.calie@gmail.com>
 */
class Role extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['name', 'description'];

    /**
     * Cache the roles
     *
     * @return mixed
     */
    public function cachedPermissions()
    {
        if(Cache::getStore() instanceof TaggableStore) {
            $primaryKeyName = $this->primaryKey;

            $cacheKey = 'priviliges_roles_permissions'.$this->$primaryKeyName;
            return Cache::tags('roles_permissions')->remember($cacheKey, 60, function () {
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
    {   //both inserts and updates
        parent::save($options);
        Cache::tags('roles_permissions')->flush();
    }

    /**
     * Flush the cached roles when role is deleted
     *
     * @param array $options
     * @return bool|null|void
     */
    public function delete(array $options = [])
    {
        parent::delete($options);
        Cache::tags('roles_permissions')->flush();
    }

    /**
     * Flush the cached roles when new cache is restored
     * restore = undo soft deleting
     */
    public function restore()
    {
        parent::restore();
        Cache::tags('roles_permissions')->flush();
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
