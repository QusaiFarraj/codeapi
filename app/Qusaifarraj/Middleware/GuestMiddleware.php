<?php

namespace Qusaifarraj\Middlewares;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;


/**
* Guest Middleware
*/
class GuestMiddleware extends Middleware
{
    /**
     * Guest Middleware. It checks if the user is logged in or not. 
     * If logged in, then send him to home
     *
     * @param  \Psr\Http\Message\ServerRequestInterface $request  PSR7 request
     * @param  \Psr\Http\Message\ResponseInterface      $response PSR7 response
     * @param  callable                                 $next     Next middleware
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function __invoke(Request $request, Response $response, $next)
    {
        if($this->auth()->check()) {
            return $this->redirect($response, 'home');
        }
        
        $response = $next($request, $response);
        return $response;
    }
}