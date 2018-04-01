<?php

namespace Qusaifarraj\Middlewares;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use \Qusaifarraj\Helpers\Cookie;
use \Qusaifarraj\Helpers\Session;


/**
* 
*/
class AuthMiddleware extends Middleware
{

    /**
     * Auth Middleware for auhtentication before loading any page
     *
     * @param  \Psr\Http\Message\ServerRequestInterface $request  PSR7 request
     * @param  \Psr\Http\Message\ResponseInterface      $response PSR7 response
     * @param  callable                                 $next     Next middleware
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function __invoke(Request $request, Response $response, $next)
    {
        if (!$this->auth()->check()) {
            $this->flash('global', $this->lang('alerts.requires_auth'));
            return $this->redirect($response, 'login');
        }

        // Check if user has a cookie set up before, then direct him to dashboard
        $this->checkRememberMe($request, $response, $next);

        $this->container->view['auth'] = $this->user();
        $this->container->view['baseUrl'] = $this->config('url');
        
        $response = $next($request, $response);
        return $response;
    }


    /**
     * Checks if a user-agent has cookie already set up for the user
     *
     * @param  \Psr\Http\Message\ServerRequestInterface $request  PSR7 request
     * @param  \Psr\Http\Message\ResponseInterface      $response PSR7 response
     *
     * @return \Slim\Twig\View | null
     */
    protected function checkRememberMe(Request $request, Response $response, $next){
        
        if (Cookie::exists($this->config('auth.cookie.name')) && !($this->auth())) {
            $data = Cookie::get($this->config('auth.cookie.name'));
            $credentials = explode('___', $data);

            // if cookie value is missed up, then delete it
            if (empty(trim($data)) || count($credentials) !== 2) {
                Cookie::destroy($this->config('auth.cookie.name'));
                return $this->redirect($response, 'home');
            } else {
                $identifier = $credentials[0];
                $token = $this->hash($credentials[1]);

                $user = $this->user('remember_identifier', $identifier);
                if ($user) {
                    if ($this->hashCheck($token, $user->remember_token)) {
                        if ($user->active) {
                            Session::set($this->configs('auth.session'), $user->id);
                            $this->container->view['auth'] = $this->user('id', $user->id);
                            // We must define a reponse with a redirect to detect a session when we first hit the page, then add the requested page to the link
                            $response = $response->withRedirect($this->config('url') . $_SERVER['REQUEST_URI']);
                            return $next($request, $response);
                        } else {
                            Cookie::destroy($this->config('auth.session'));
                            
                            $user->removeRememberCredentials();
                            $this->flash('global','Your account has not been activated.');
                            return $this->redirect($response, 'login');
                        }
                    } else {
                        $user->removeRememberCredentials();
                    }
                }
            }
        }
    }
}
