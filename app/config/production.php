<?php

return [

    'app' =>[
        'url' => 'http://localhost',
        'hash' =>[
            'algo' => PASSWORD_BCRYPT,
            'cost' => 10
        ]
    ],
    'db' => [ 
        'driver' => 'mysql',
        'host' => '127.0.0.1',
        'name' => 'rcc',
        'username' => 'root',
        'paassword' => 'farraj1991',
        'charset' => 'utf8',
        'collection' => 'utf8_unicode_ci',
        'prefix' => ''
    ],
    'auth' => [
        'session' => 'user_id',
        'remember' => 'user_r'
    ],
    'mail' => [
        'smpt_auth' => true,
        'smtp_secure' => 'tls',
        'host' => 'smtp.gmail.com',
        'username' => 'qusai91919@gmail.com',
        'password' => 'Ema1234!',
        'port' => 587,
        'html' =>true,
        'from_name' => 'Qusai Farraj',
        'reply_to_email' => 'qusai@flamotechs.com',
        'reply_to_text' => 'Reply_to'
    ],
    'twig' => [
        'debug' => 'true',
    ],
    'csrf' => [
        'key' => 'csrf_token'
    ]
];