<?php

namespace Qusaifarraj\Middleware;

use Exception;

/**
* 
*/
class CsrfMiddleware{
    
    protected $key;

    public function call(){

        $this->key = $this->app->config->get('csrf.key');

        $this->app->hook('slim.before', [$this, 'ckeck']);
        $this->next->call();
    }


    public function ckeck(){

        // generate a hashed key for the session
        if(!isset($_SESSION[$this->key])){
            $_SESSION[$this->key] = $this->app->hash->hash($this->app->randomlib->generateString(128));
        }

        $token = $_SESSION[$this->key];

        if(in_array($this->app->request->getMethod(), ['POST', 'PUT', 'DELETE'])){

            $submittedToken= $this->app->request->post($this->key) ?: '';

            if(!$this->app->hash->hashCheck($token, $submittedToken)){
                throw new Exception('CSRF Mismatch', 1);        
            }
        }

         $this->app->view->appendData([
            'csrf_key' => $this->key,
            'csrf_token' => $token
        ]);
    }
}