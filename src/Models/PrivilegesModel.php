<?php

namespace MatthC\Privileges\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

/**
 * Class PrivilegesModel
 * @package MatthC\Privileges\Models
 *
 * @author Matthieu Calie <matthieu.calie@gmail.com>
 */
class PrivilegesModel extends Model
{
    /**
     * @var
     */
    protected $cacheName;

    /**
     * Check if tag caching is allowed
     *
     * @return bool
     */
    public function isTagCacheAllowed()
    {
        return (Cache::getStore() instanceof TaggableStore);
    }

    /**
     * Flush the cache if tag caching is allowed
     */
    public function flushCache()
    {
        if($this->isTagCacheAllowed()) {
            Cache::tags($this->cacheName)->flush();
        }
    }
}
