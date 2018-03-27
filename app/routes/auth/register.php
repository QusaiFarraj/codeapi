<?php

use Qusaifarraj\User\UserPermission;

$app->get('/register', $guest(), function () use ($app){
    $app->render('auth/register.php');
})->name('register');

$app->post('/register', $guest(), function () use ($app){
    

    $req = $app->request;

    $email = $req->post('email');
    $username = $req->post('username');
    $password = $req->post('password');
    $passwordConfirm = $req->post('password_confirm');


    $v = $app->validation;

    $v->validate([
        'email' => [$email, 'required|email|uniqueEmail'],
        'username' => [$username, 'required|alnumDash|max(20)|uniqueUsername'],
        'password' => [$password, 'required|min(6)'],
        'password_confirm' => [$passwordConfirm, 'required|matches(password)'],
    ]);

    if($v->passes()){
        
        $identifier = $app->randomlib->generateString(128);

        $user = $app->user->create([
            'email' => $email,
            'username' => $username,
            'password' => $app->hash->password($password),
            'active' => false,
            'active_hash' => $app->hash->hash($identifier)
        ]);

        $user->permissions()->create(UserPermission::$defaults);

        $app->mail->send('email/auth/registered.php', ['user' => $user, 'identifier' => $identifier], function ($message) use ($user){
            $message->to($user->email, $user->username);
            $message->subject('Thanks For Registering!');
        });

        $app->flash('global', 'You have been registered.');
        $app->response->redirect($app->urlFor('home'));
    }

    $app->render('auth/register.php', [
        'errors' => $v->errors(),
        'request' => $req,
    ]);

})->name('register.post');