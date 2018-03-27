<?php

$authenticationCheck = function ($required) use ($app){
    return function () use ($required, $app) {
        if((!$app->auth && $required) || ($app->auth && !$required)){
            $app->redirect($app->urlFor('home'));
        }
    };
};


$authenticated = function () use ($authenticationCheck) {
    return $authenticationCheck(true);
};


// Guest Middleware
$guest = function () use ($authenticationCheck){
    return $authenticationCheck(false);
};

// Admin Middleware
$admin = function () use ($app){
    return function () use ($app) {
        if(!$app->auth || !$app->auth->isAdmin()){
            $app->redirect($app->urlFor('home'));
        }
    };
};
