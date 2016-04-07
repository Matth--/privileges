<?php

namespace MatthC\Privileges\Models;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
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
