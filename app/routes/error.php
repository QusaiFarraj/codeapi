<?php

$app->get('/404', function ($request, $response) use ($app){
    $this->view->render($response, 'errors/404.twig');
})->setName('error.404');