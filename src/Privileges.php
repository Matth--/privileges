<?php

namespace MatthC\Privileges;
use Illuminate\Foundation\Application;

/**
 * Class Privileges
 * @package MatthC\Privileges
 *
 * @author Matthieu Calie <matthieu.calie@gmail.com>
 */
class Privileges
{
    /**
     * @var Application
     */
    public $app;

    /**
     * Privileges constructor.
     *
     * @param Application $application
     */
    public function __construct(Application $application)
    {
        $this->app = $application;
    }

    /**
     * @param $role
     * @param bool $requireAll
     *
     * @return bool
     */
    public function hasRole($role, $requireAll = false)
    {
        if($this->user()) {
            return $this->user()->hasRole($role, $requireAll);
        }

        return false;
    }

    /**
     * @param $permission
     * @param bool $requireAll
     *
     * @return bool
     */
    public function can($permission, $requireAll = false)
    {
        if($this->user()) {
            return $this->user()->can($permission, $requireAll);
        }

        return false;
    }

    /**
     * @return mixed
     */
    public function user()
    {
        return $this->app->auth->user();
    }
}
