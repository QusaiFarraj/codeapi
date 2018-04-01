<?php

define('INC_ROOT', dirname(__DIR__));

// load all dependencies
require INC_ROOT . '/vendor/autoload.php';

// Load configs. If we have any local configs, merge or replace values only. depends on the enviroment.
$config = require INC_ROOT . '/app/configs/config.php';
if(file_exists(INC_ROOT . '/app/configs/config.local.php'))
    $config = array_replace_recursive($config, require INC_ROOT . '/app/configs/config.local.php');

$app = new \Slim\App($config);

// start the session after initializing the $app
session_start();

// include all dependencies
require INC_ROOT . '/app/dependencies.php';

// Set errors on
ini_set('display_error', $app->getContainer()->get('config')['displayErrorDetails']);

// include routes, database and middlewares
require INC_ROOT . '/app/routes.php';
require INC_ROOT . '/app/database.php';
require INC_ROOT . '/app/middlewares.php';
// require INC_ROOT . '/filters.php';

// Add Middlewares
// $app->add();
// $app->add(new CsrfMiddleware);


$app->run();



// use Qusaifarraj\Middleware\BeforeMiddleware;
// use Qusaifarraj\Middleware\CsrfMiddleware;

