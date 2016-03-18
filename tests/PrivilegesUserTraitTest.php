<?php

namespace MatthC\Privileges\Tests;

use Mockery as m;
use PHPUnit_Framework_TestCase;
use MatthC\Privileges\Traits\PrivilegesUserTrait;

class PrivilegesUserTraitTest extends PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        parent::tearDown();
        m::close();
    }
}

class UserStub
{
    use PrivilegesUserTrait;
}
