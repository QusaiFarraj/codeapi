<?php

namespace Qusaifarraj\Auth;

use \Qusaifarraj\Models\User as User;
use \Qusaifarraj\Helpers\Session as Session;
use Interop\Container\ContainerInterface;

/**
* Auth class that manages authentication 
*/
class Auth extends User
{
    protected $container;

    public function __construct($container)
    {
        $this->container = $container;
    }

    /**
     * Check if your authenticated
     */
    public function check()
    {
        return Session::exists($this->container->configs['auth']['session']);
    }

    /**
     * Find user by $col, $val
     */
    public function user($col=null, $val=null)
    {   
        if ($col === null && $val === null) {
            $col = 'id';
            $val = Session::get($this->container->configs['auth']['session']);
        }
        return User::where($col, $val)->first();
    }
}