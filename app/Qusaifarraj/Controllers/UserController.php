<?php

namespace Qusaifarraj\Controllers;


use \Qusaifarraj\Models\User as User;
use \Qusaifarraj\Models\UserPermission as UserPermission;
use Carbon\Carbon;


/**
* 
*/
class UserController extends Controller
{
    /**
    *   Login function
    */
    public function login($request, $response)
    {
        if ($request->isPost()) {
            $router = $this->router;
            $user = $this->user;
            $body = $request->getParsedBody();
            
            $identifier = $body['identifier'];
            $password = $body['password'];
            $remember = $body['remember'];

            $v = $this->validation;

            $v->validate([
                'identifier|Email' => [$identifier, 'required'],
                'password|Password' => [$password, 'required'],
            ]);

            if ($v->passes()) {
                $user = $user
                    ->where('username', $identifier)
                    ->Where('active', true)
                    ->orWhere('email', $identifier)
                    ->Where('active', true)
                    ->first();

                if ($user && $this->hash->passwordCheck($password, $user->password)) {
                    $this->session->set($this->configs['auth']['session'], $user->id); 
                    // $_SESSION[$this->configs['auth']['session']] = $user->id;

                    if ($remember == 'on') {

                        $rememberIdentifier = $this->randomlib->generateString(128);
                        $rememberToken = $this->randomlib->generateString(128);

                        $user->updateRememberCredentials(
                            $rememberIdentifier,
                            $this->hash->hash($rememberToken)
                        );

                        $this->cookie->set(
                            $this->configs['auth']['cookie']['name'],
                            "{$rememberIdentifier}___{$rememberToken}",
                            $this->configs['auth']['cookie']['expires'],
                            $this->configs['auth']['cookie']['path'], 
                            $this->configs['auth']['cookie']['domain'], 
                            $this->configs['auth']['cookie']['secure'],
                            $this->configs['auth']['cookie']['httpOnly']
                        );
                    }

                    $this->flash->addMessage('global', 'You are now signed in!');
                    return $response->withRedirect($router->pathFor('home'));
                } else {
                    $this->flash->addMessage('global', 'Incorrect username and/or password!');
                    return $response->withRedirect($router->pathFor('login'));
                }
            }

            $this->view->render($response, 'auth/login.twig', [
                'errors' => $v->errors(),
                'request' => $body,
            ]);            
        } else {
            return $this->view->render($response, 'auth/login.twig');
        }
    }

    public function register($request, $response)
    {
        if ($request->isPost()) {
            $body = $request->getParsedBody();
            $router = $this->router;
            $v = $this->validation;
            $user = new User();

            $email = $body['email'];
            $username = $body['username'];
            $password = $body['password'];
            $passwordConfirm = $body['password_confirm'];
            $recaptcha = $body['g-recaptcha-response'];

            $v->validate([
                'email|Email' => [$email, 'required|email|uniqueEmail'],
                'username|Username' => [$username, 'required|alnumDash|max(20)|uniqueUsername'],
                'password|Password' => [$password, 'required|min(6)'],
                'password_confirm|Confirm Password' => [$passwordConfirm, 'required|confirmPasswordMatch(password)'],
                'g-recaptcha-response|reCaptcha' => [$recaptcha, 'required'],
            ]);

            $checkRecaptcha = $this->checkRecaptcha($recaptcha);

            if ($checkRecaptcha && $v->passes()) {
                $identifier = $this->randomlib->generateString(128);

                $user = $user->create([
                    'email' => $email,
                    'username' => $username,
                    'password' => $this->hash->password($password),
                    'active' => false,
                    'active_hash' => $this->hash->hash($identifier)
                ]);

                $user->permissions()->create(UserPermission::$defaults);

                if ($this->configs['mail']['send'] === true) {
                    $this->mail->send('email/auth/registered.twig', ['auth' => $user, 'user' => $user, 'identifier' => $identifier], function ($message) use ($user) {
                        $message->to($user->email, $user->username);
                        $message->subject('Thanks For Registering!');
                    });
                }

                $this->flash->addMessageNow('global', 'You have been registered.');
                return $this->view->render($response, 'auth/welcome.twig');
            }

            return $this->view->render($response, 'auth/register.twig', [
                'errors' => $v->errors(),
                'request' => $body,
            ]);
        } else {
            return $this->view->render($response, 'auth/register.twig');
        }
    }


    public function activate($request, $response)
    {
        // die('cha');
        $router = $this->router;
        $params = $request->getQueryParams();

        $email = $params['email'];
        $identifier = $params['identifier'];

        $hashedIdentifier = $this->hash->hash($identifier);

        $user = $this->user->where('email', $email)->first();

        if(!$user){
            $this->flash->addMessage('global', 'Could\'t activate your account. Please try again!');
            return $response->withRedirect($router->pathFor('home'));
        }
        if(!$user->active_hash){
            $this->flash->addMessage('global', 'Could\'t activate your account. Please try again!');
            return $response->withRedirect($router->pathFor('home'));
        }
        if(!$this->hash->hashCheck($user->active_hash, $hashedIdentifier)){
            $this->flash->addMessage('global', 'Could\'t activate your account. Please try again!');
            return $response->withRedirect($router->pathFor('home'));
        }
var_dump($user->activateAccount(1, null)); die();
        // set active true and hash to null
        $user->activateAccount(1, null);

        $this->flash->addMessage('global', 'Your account has been activated!');
        return $response->withRedirect($router->pathFor('dashboard'));
    }

    public function resendActivation($request, $response)
    {
        // $router = $this->router;
        // $params = $request->getQueryParams();

        // $email = $params['email'];
        // $identifier = $params['identifier'];

        // $hashedIdentifier = $this->hash->hash($identifier);

        // $user = $this->user->where('email', $email)->first();

        // if(!$user){
        //     $this->flash->addMessageNow('global', 'Could\'t activate your account. Please try again!');
        //     return $response->withRedirect($router->pathFor('home'));
        // }
        // if(!$user->recover_hash){
        //     $this->flash->addMessageNow('global', 'Could\'t activate your account. Please try again!');
        //     return $response->withRedirect($router->pathFor('home'));
        // }
        // if(!$app->hash->hashCheck($user->recover_hash, $hashedIdentifier)){
        //     $this->flash->addMessageNow('global', 'Could\'t activate your account. Please try again!');
        //     return $response->withRedirect($router->pathFor('home'));
        // }

        $this->flash->addMessageNow('global', 'activation email has been sent!');
        return $response->withRedirect($router->pathFor('home'));
    }

    public function changePassword($request, $response)
    {
        if ($request->isPost()) {
            
            $router = $this->router;
            $body = $request->getParsedBody();

            $passwordOld = $body['password_old'];
            $passwordNew = $body['password_new'];
            $passwordConfirm = $body['password_confirm'];

            $v = $this->validation;

            $v->validate([
                'password_old|Old Password' => [$passwordOld, 'required|matchesCurrentPassword()'],
                'password_new|New Password ' => [$passwordNew, 'required|min(6)'],
                'password_confirm|Confirm Password' => [$passwordConfirm, 'required|confirmPasswordMatch(password_new)']
            ]);

            if($v->passes()){
                
                $user = $this->auth;

                $user->update(['password' => $this->hash->password($passwordNew)]);

                // Send email
                if ($this->configs['mail']['send'] === true) {
                    $this->mail->send('email/auth/password/changed.twig', [], function ($message) use ($user){
                        $message->to($user->email, $user->username);
                        $message->subject('Password Changed');
                    });
                }

                $this->flash->addMessage('global', 'You password has been changed!');
                return $response->withRedirect($router->pathFor('home'));
            } else {
                return $this->view->render($response, 'auth/password/change.twig', [
                    'errors' => $v->errors()
                ]);
            } 
        } else {
            return $this->view->render($response, 'auth/password/change.twig');
        }
    }

    public function recoverPassword($request, $response)
    {
        if ($request->isPost()) {

            $router = $this->router;
            $v = $this->validation;
            $body = $request->getParsedBody();

            $email = $body['email'];

            $v->validate([
                'email' => [$email, 'required|email']
            ]);

            if($v->passes()){
                
                $user = $this->user->where('email', $email)->first();

                if(!$user){
                    $this->flash->addMessage('global', 'Cound not find that user.');
                    return $response->withRedirect($router->pathFor('password.recover'));
                } else {
                    
                    $identifier = $this->randomlib->generateString(128);
                    
                    $user->update(['recover_hash' => $this->hash->hash($identifier)]);

                    if ($this->configs['mail']['send'] === true) {
                        $this->mail->send('email/auth/password/recover.twig', ['user' => $user, 'identifier' => $identifier], function ($message) use ($user){
                            $message->to($user->email, $user->username);
                            $message->subject('Recover your password.');
                        });
                    }

                    $this->flash->addMessage('global', 'We have sent you instructions on how to reset your password');
                    return $response->withRedirect($router->pathFor('home'));
                }

            } else {
                return $this->view->render($response, 'auth/password/recover.twig', [
                    'errors' => $v->errors()
                ]);
            }
        } else {
            return $this->view->render($response, 'auth/password/recover.twig');
        }
    }

    public function resetPassword($request, $response)
    {
        if ($request->isPost()) {

            $router = $this->router;
            $params = $request->getQueryParams();
            $body = $request->getParsedBody();

            $email = $params['email'];
            $identifier = $params['identifier'];

            $password = $body['password'];
            $passwordConfirm = $body['password_confirm'];


            $hashedIdentifier = $this->hash->hash($identifier);

            $user = $this->user->where('email', $email)->first();

            if (!$user){
                return $response->withRedirect($router->pathFor('home'));
            }
            if (!$user->recover_hash){
                return $response->withRedirect($router->pathFor('home'));
            }
            if (!$this->hash->hashCheck($user->recover_hash, $hashedIdentifier)){
                return $response->withRedirect($router->pathFor('home'));
            }

            $v = $this->validation;

            $v->validate([
                'password' => [$password, 'required|min(6)'],
                'password_confirm' => [$passwordConfirm, 'required|confirmPasswordMatch(password)']
            ]);

            if($v->passes()){
                $user->update([
                    'password' => $this->hash->password($password),
                    'recover_hash' => null
                ]);

                $this->flash->addMessage('global', 'Your password has been reset and you can sign in');

                return $response->withRedirect($router->pathFor('home'));

            } else {
                $this->view->render($response, 'auth/password/reset.twig', [
                    'errors' => $v->errors(),
                    'email' => $user->email,
                    'identifier' => $identifier
                ]);
            }

            return $response->withRedirect($router->pathFor('dashboard'));
        } else {
            
            $router = $this->router;
            $params = $request->getQueryParams();

            $email = $params['email'];
            $identifier = $params['identifier'];

            $hashedIdentifier = $this->hash->hash($identifier);

            $user = $this->user->where('email', $email)->first();

            if(!$user){
                return $response->withRedirect($router->pathFor('home'));
            }
            if(!$user->recover_hash){
                return $response->withRedirect($router->pathFor('home'));
            }
            if(!$app->hash->hashCheck($user->recover_hash, $hashedIdentifier)){
                return $response->withRedirect($router->pathFor('home'));
            }

            return $this->view->render($response, 'auth/password/reset.twig', [
                'email' => $user->email,
                'identifier' => $identifier
            ]);
        }
    }

    public function logout($request, $response)
    {
        $router = $this->router;
        unset($_SESSION[$this->configs['auth']['session']]);

        if ($_COOKIE[$this->configs['auth']['cookie']['name']]) {
            $app->auth->removeRememberCredentials();
            setcookie(
                $this->configs['auth']['cookie']['name'], 
                '',
                1, 
                $this->configs['auth']['cookie']['path'], 
                $this->configs['auth']['cookie']['domain'], 
                $this->configs['auth']['cookie']['secure'],
                $this->configs['auth']['cookie']['httpOnly']
            );
        }

        $this->flash->addMessage('global', 'You have been looged out!');
        return $response->withRedirect($router->pathFor('home'));
    }

    public function dashboard($request, $response, $args)
    {
        $router = $this->router;
        
        if ($request->isPost()) {
            
            return $response->withRedirect($router->pathFor('dashboard'));
        } else {
            $username = $args['username'];
            $user = $this->user->where('username', $username)->first();

            if(!$user){
                return $response->withRedirect($router->pathFor('error.404'));
            }

            
            return $this->view->render($response, 'user/profile.twig', ['user' => $user]);
        }
    }
}
