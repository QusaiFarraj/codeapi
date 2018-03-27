<?php

use Slim\App;
use Slim\Views\Twig;
use Slim\Views\TwigExtension;

use Noodlehaus\Config;
use RandomLib\Factory as RandomLib;

use Qusaifarraj\User\User;
use Qusaifarraj\Mail\Mailer;
use Qusaifarraj\Helpers\Hash;
use Qusaifarraj\Validation\Validator;
use Qusaifarraj\Middleware\BeforeMiddleware;
use Qusaifarraj\Middleware\CsrfMiddleware;

use PHPMailer\PHPMailer\PHPMailer;


session_cache_limiter(false);
session_start();

ini_set('display_error', 'On');

define('INC_ROOT', dirname(__DIR__));

require INC_ROOT.'/vendor/autoload.php';

$app = new App([
    'mode'=> file_get_contents(INC_ROOT . '/mode.php'),
    'view'=> new Twig(INC_ROOT . '/app/views')
]);

$app->add(new BeforeMiddleware);
$app->add(new CsrfMiddleware);

$app->configureMode($app->config('mode'), function () use ($app){
    $app->config = Config::Load(INC_ROOT . "/app/config/{$app->mode}.php");
});

require 'database.php';
require 'filters.php';
require 'routes.php'; 

$app->container->auth = false;

$app->container->set('user', function () {
    return new User;
});

$app->container->singleton('hash', function() use ($app){
    return new Hash($app->config);
});

$app->container->singleton('validation', function() use ($app){
    return new Validator($app->user, $app->hash, $app->auth);
 });

$app->container->singleton('mail', function() use ($app){


//  MAILGUN API

// # Include the Autoloader (see "Libraries" for install instructions)
// use Mailgun\Mailgun;

// # Instantiate the client.
// $mgClient = new Mailgun('key-9dc5374568c347519ea26ed8e6b537a8', new \Http\Adapter\Guzzle6\Client());
// // $mgClient = new Mailgun('key-9dc5374568c347519ea26ed8e6b537a8');
// $domain = "Flamotechs.com";
// $mgClient->setSslEnabled(false);

// # Make the call to the client.
// $result = $mgClient->sendMessage($domain, array(
//     'from'    => 'Excited User <qusai@Flamotechs.com>',
//     'to'      => 'Baz <qusai919@gmail.com>',
//     'subject' => 'Hello',
//     'text'    => 'Testing some Mailgun awesomness!'
// ));
// var_dump($result);


    $mail = new PHPMailer;
    //Tell PHPMailer to use SMTP
    $mail->isSMTP();
    //Enable SMTP debugging
    // 0 = off (for production use)
    // 1 = client messages
    // 2 = client and server messages
    $mail->SMTPDebug = 2;
    //Set the hostname of the mail server
    $mail->Host = $app->config->get('mail.host');
    // use
    // $mail->Host = gethostbyname('smtp.gmail.com');
    // if your network does not support SMTP over IPv6
    //Set the SMTP port number - 587 for authenticated TLS, a.k.a. RFC4409 SMTP submission
    $mail->Port = $app->config->get('mail.port');
    //Set the encryption system to use - ssl (deprecated) or tls
    $mail->SMTPSecure = $app->config->get('mail.smtp_secure');
    //Whether to use SMTP authentication
    $mail->SMTPAuth = $app->config->get('mail.smpt_auth');
    //Username to use for SMTP authentication - use full email address for gmail
    $mail->Username =$app->config->get('mail.username');
    //Password to use for SMTP authentication
    $mail->Password = $app->config->get('mail.password');
    //Set who the message is to be sent from
    // $mail->setFrom('qusai91919@gmail.com', 'Qusai Last');
    $mail->setFrom($app->config->get('mail.username'), $app->config->get('mail.from_name'));
    // // //Set an alternative reply-to address
    $mail->addReplyTo($app->config->get('mail.reply_to_email'), $app->config->get('mail.reply_to_text'));

    $mail->IsHTML($app->config->get('mail.html'));

    // return mailer object
   return new Mailer($app->view, $mail);
});

$app->container->singleton('randomlib', function (){
    $factory = new RandomLib();
    return $factory->getMediumStrengthGenerator();
});



$view = $app->view();

$view->parserOptions = [
    'debug' => $app->config->get('twig.debug')
];

$view->parserExtensions = [
    new TwigExtension
];
