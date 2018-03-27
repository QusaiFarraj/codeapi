<?php

$app->get('/change-password', $authenticated(),function() use ($app){
    $app->render('auth/password/change.php');
})->name('password.change');

$app->post('/change-password', $authenticated(), function () use ($app) {
    
    $request = $app->request;

    $passwordOld = $app->request->post('password_old');
    $passwordNew = $app->request->post('password_new');
    $passwordConfirm = $app->request->post('password_confirm');

    $v = $app->validation;

    $v->validate([
        'password_old' => [$passwordOld, 'required|matchesCurrentPassword()'],
        'password_new' => [$passwordNew, 'required|min(6)'],
        'password_confirm' => [$passwordConfirm, 'required|matches(password_new)']
    ]);

    if($v->passes()){
        
        $user = $app->auth;

        $user->update(['password' => $app->hash->password($passwordNew)]);

        // Send email
        $app->mail->send('email/auth/password/changed.php', [], function ($message) use ($user){
            $message->to($user->email, $user->username);
            $message->subject('Password Changed');
        });


        $app->flash('global', 'You password has been changed!');
        $app->response->redirect($app->urlFor('home'));

    } else {
        $app->render('auth/password/change.php', [
            'errors' => $v->errors()
        ]);
    }



})->name('password.change.post');