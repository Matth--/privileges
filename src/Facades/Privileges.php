<?php

namespace MatthC\Privileges\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class PrivilegesFacade
 * @package MatthC\Privileges
 *
 * @author Matthieu Calie <matthieu.calie@gmail.com>
 */
class Privileges extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'privileges';
    }
}
