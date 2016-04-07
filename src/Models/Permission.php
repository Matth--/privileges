<?php

namespace MatthC\Privileges\Models;

/**
 * Class Permission
 * @package MatthC\Privileges\Models
 *
 * @author Matthieu Calie <matthieu.calie@gmail.com>
 */
class Permission extends PrivilegesModel
{
    /**
     * @var array
     */
    protected $fillable = ['name', 'description'];

    /**
     * Get linked Roles
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }
}
