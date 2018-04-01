<?php

$container = $app->getContainer();

/*
=================
Main Components
=================
*/
$container['view'] = function ($c) {
    $view = new \Slim\Views\Twig($c->get('config')['twig']['tempPath']);
    
    // Instantiate and add Slim specific extension
    $basePath = rtrim(str_ireplace('index.php', '', $c['request']->getUri()->getBasePath()), '/');
    $view->addExtension(new \Slim\Views\TwigExtension($c['router'], $basePath));
    // for flash messages
    $view->addExtension(new \Qusaifarraj\Helpers\TwigFlashExtension($c['flash']));
    // for debuging only. should be removed on production
    $view->addExtension(new \Twig_Extension_Debug());

    // add global vars
    $auth = $c['auth']->user();
    $view->getEnvironment()->addGlobal('auth', $auth);

    return $view;
};
$container['configs'] = function ($c) {
    return $c->get('config');
};
$container['session'] = function ($c) {
    return $_SESSION;
};

/*
================
Error Handlers
================
*/
$container['notFoundHandler'] = function($c) {
    return function($request, $response) use ($c) {
        $response = $response->withStatus(404);
        return $c->view->render($response, 'errors/404.twig', [
            'request_uri' => urldecode($request->getUri())//$_SERVER['REQUEST_URI'])
        ]);
    };
};
$container['notAllowedHandler'] = function($c) {
    return function ($request, $response, $methods) use ($c) {
        $response = $response->withStatus(405);
        return $c->view->render($response, 'errors/405.twig', [
            'request_uri' => $request->getUri(),//,//$_SERVER['REQUEST_URI'],
            'method' => $request->getMethod(),//$_SERVER['REQUEST_METHOD'],
            'methods' => implode(', ', $methods)
        ]);
    };
};
$container['errorHandler'] = function($c) {
    return function($request, $response, $exception) use ($c) {
        $response = $response->withStatus(500);
        $data = '';
        if($c['config']['env'] === "dev") {
            $data = $exception;
        }
        return $c->view->render($response, 'errors/500.twig', ['data' => $data]);
    };
};

/*
=============
Middlewares
=============
*/
$container['AuthMiddleware'] = function ($c) {
    return new \Qusaifarraj\Middlewares\AuthMiddleware($c);
};
$container['AdminMiddleware'] = function ($c) {
    return new \Qusaifarraj\Middlewares\AdminMiddleware($c);
};
$container['CsrfMiddleware'] = function ($c) {
    return new \Qusaifarraj\Middlewares\CsrfMiddleware($c);
};
$container['GuestMiddleware'] = function ($c) {
    return new \Qusaifarraj\Middlewares\GuestMiddleware($c);
};

/*
=========
Models
=========
*/
$container['user'] = function ($c) {
    return new \Qusaifarraj\Models\User();
};

/*
=========
Auth
=========
*/
$container['auth'] = function ($c) {
    return new \Qusaifarraj\Auth\Auth($c);
};


/*
=============
Controllers
=============
*/
$container['UserController'] = function ($c) {
    return new \Qusaifarraj\Controllers\UserController($c);
};
$container['AdminController'] = function ($c) {
    return new \Qusaifarraj\Controllers\AdminController($c);
};

/**
===============
Helpers
===============
*/
$container['hash'] = function ($c) {
    return new \Qusaifarraj\Helpers\Hash($c['config']['hash']);
};
$container['validation'] = function($c) {
    return new \Qusaifarraj\Helpers\Validator($c['hash'], $c['auth']);
};
$container['recaptcha'] = function($c) {
    return new \ReCaptcha\ReCaptcha($c->get('config')['auth']['recaptcha']['secret']);
};
$container['flash'] = function ($c) {
    return new \Slim\Flash\Messages();
};
$container['session'] = function ($c) {
    return new \Qusaifarraj\Helpers\Session();
};
$container['cookie'] = function ($c) {
    return new \Qusaifarraj\Helpers\Cookie();
};
$container['randomlib'] = function ($c) {
    $factory = new \RandomLib\Factory();
    return $factory->getMediumStrengthGenerator();
};
$container['db'] = function ($c) {
    
    $capsule = new \Illuminate\Database\Capsule\Manager;
    $capsule->addConnection($c->get('config')['db']);
    $capsule->setAsGlobal();    
    return $capsule;
};
$container['csrf'] = function($c) {
    $guard = new \Slim\Csrf\Guard;
    $guard->setFailureCallable(function($request, $response, $next) use ($c) {
        $request = $request->withAttribute("csrf_status", false);
        if($request->getAttribute('csrf_status') === false) {
            $c['flash']->addMessage('error', "CSRF verification failed, terminating your request.");
            return $response->withStatus(400)->withRedirect($c['router']->pathFor('home'));
        } else {
            return $next($request, $response);
        }
    });
    return $guard;
};
$container['mail'] = function($c) {

    $configs = $c['config']['mail'];

//  MAILGUN API

// # Include the Autoloader (see "Libraries" for install instructions)
// use Mailgun\Mailgun;

// # Instantiate the client.
// $mgClient = new Mailgun('$c->get('config')['mailgun']['key'], new \Http\Adapter\Guzzle6\Client());
// $domain = "codesampling.com";
// $mgClient->setSslEnabled(false);

// # Make the call to the client.
// $result = $mgClient->sendMessage($domain, array(
//     'from'    => 'Excited User <qusai@codesampling.com>',
//     'to'      => 'Baz <qusai919@gmail.com>',
//     'subject' => 'Hello',
//     'text'    => 'Testing some Mailgun awesomness!'
// ));
// var_dump($result);


    $mail = new \PHPMailer\PHPMailer\PHPMailer;
    //Tell PHPMailer to use SMTP
    $mail->isSMTP();
    //Enable SMTP debugging
    // 0 = off (for production use)
    // 1 = client messages
    // 2 = client and server messages
    $mail->SMTPDebug = 2;
    //Set the hostname of the mail server
    $mail->Host = $configs['host'];
    // use
    // $mail->Host = gethostbyname('smtp.gmail.com');
    // if your network does not support SMTP over IPv6
    //Set the SMTP port number - 587 for authenticated TLS, a.k.a. RFC4409 SMTP submission
    $mail->Port = $configs['port'];
    //Set the encryption system to use - ssl (deprecated) or tls
    $mail->SMTPSecure = $configs['smtp_secure'];
    //Whether to use SMTP authentication
    $mail->SMTPAuth = $configs['smpt_auth'];
    //Username to use for SMTP authentication - use full email address for gmail
    $mail->Username =$configs['username'];
    //Password to use for SMTP authentication
    $mail->Password = $configs['password'];
    //Set who the message is to be sent from
    // $mail->setFrom('qusai91919@gmail.com', 'Qusai Last');
    $mail->setFrom($configs['username'], $configs['from_name']);
    // // //Set an alternative reply-to address
    $mail->addReplyTo($configs['reply_to_email'], $configs['reply_to_text']);

    $mail->IsHTML($configs['html']);

    // return mailer object
   return new Qusaifarraj\Mail\Mailer($c['view'], $mail);
};
