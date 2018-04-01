<?php

namespace Qusaifarraj\Middlewares;

/**
* 
*/
class AdminMiddleware extends Middleware
{
    /**
     * Admin Middleware for auhtentication for admin site
     *
     * @param  \Psr\Http\Message\ServerRequestInterface $request  PSR7 request
     * @param  \Psr\Http\Message\ResponseInterface      $response PSR7 response
     * @param  callable                                 $next     Next middleware
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function __invoke($request, $response, $next)
    {
        if($this->auth()->check()) {
            if($this->user()->isAdmin()) {
                $response = $next($request, $response);
                return $response;
            }
        }
        return $this->notFound($request, $response);
    }
}