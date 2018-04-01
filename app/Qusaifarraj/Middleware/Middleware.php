<?php

namespace Qusaifarraj\Middlewares;

use Interop\Container\ContainerInterface;
use Slim\Exception\NotFoundException;

/**
 * Middleware class contains common functions that is shared between all middlewares. 
 * It needs some revision.
 */
class Middleware
{
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function auth()
    {
        return $this->container->auth;
    }
    
    public function user()
    {
        return $this->auth()->user();
    }

    public function hash($value)
    {
        return $this->container->hash->hash($value);
    }
    public function hashCheck($token, $rememberToken)
    {
        return $this->container->hash->hashCheck($token, $rememberToken);
    }

    public function flash($type, $message)
    {
        $this->container->flash->addMessage($type, $message);
    }
    
    public function config($key)
    {
        return $this->container->configs[$key];
    }
    
    public function lang($key)
    {
        return $this->config("lang." . $key);
    }
    
    public function notFound($request, $response)
    {
        throw new NotFoundException($request, $response);
    }


    protected function router()
    {
        return $this->container['router'];
    }
    public function redirect($response, $path)
    {
        return $response->withRedirect($this->router()->pathFor($path));
    }
}