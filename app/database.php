<?php

$app->getContainer()->get('db')->bootEloquent();

// use Illuminate\Database\Capsule\Manager as Capsule;

// $configs = $app->getContainer()->get('configs')['db'];
// // var_dump($configs);die();
// $capsule = new Capsule;

// $capsule->addConnection([
//     'driver' => $configs['driver'],
//     'host' => $configs['host'],
//     'database' => $configs['database'],
//     'username' => $configs['username'],
//     'password' => $configs['password'],
//     'charset' => $configs['charset'],
//     'collation' => $configs['collation'],
//     'prefix' => $configs['prefix'],
// ]);

// $capsule->bootEloquent();