<?php

$app->get('/', function ($request, $response){
    $this->view->render($response, "home.twig");
})->setName('home');

$app->get('/about', function ($request, $response){
    $this->view->render($response, "about.twig");
})->setName('home.about');

$app->get('/contactus', function ($request, $response){
    $this->view->render($response, "contactus.twig");
})->setName('home.contactus');

