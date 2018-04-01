<?php

namespace Qusaifarraj\Middlewares;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;


/**
* CSRF Middleware class
*/
class CsrfMiddleware extends Middleware
{
    
    
    /**
     * Sets the csrf values with hidden input tags middleware invokable class
     *
     * @param  \Psr\Http\Message\ServerRequestInterface $request  PSR7 request
     * @param  \Psr\Http\Message\ResponseInterface      $response PSR7 response
     * @param  callable                                 $next     Next middleware
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function __invoke(Request $request, Response $response, $next)
    {
        $this->container->view->getEnvironment()->addGlobal('csrf', [
            'field' => '
                <input type="hidden" name="' . $this->container->csrf->getTokenNameKey() . '" value="' . $this->container->csrf->getTokenName() . '">
                <input type="hidden" name="' . $this->container->csrf->getTokenValueKey() . '" value="' . $this->container->csrf->getTokenValue() . '">
            '
        ]);

        $response = $next($request, $response);
        return $response;
    }


    // future work
    protected function ckeck(Request $request, Response $response){

        // generate a hashed key for the session
        if(!isset($_SESSION[$this->key])){
            $_SESSION[$this->key] = $this->hash($this->container->randomlib->generateString(128));
        }

        $token = $_SESSION[$this->key];

        if(in_array($request->getMethod(), ['POST', 'PUT', 'DELETE'])){

            $submittedToken= $request->post($this->key) ?: '';

            if(!$this->hashCheck($token, $submittedToken)){
                throw new \Exception('CSRF Mismatch', 1);        
            }
        }   
    }
}
