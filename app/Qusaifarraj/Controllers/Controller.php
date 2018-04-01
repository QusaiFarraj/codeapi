<?php

namespace Qusaifarraj\Controllers;

use Interop\Container\ContainerInterface;


/**
* Main set up Controller for all common methods shared
*/
class Controller
{
    protected $container;
    
    /**
     * Basic Controller class costructor.
     *
     * @param Contianer $container.
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * Basic Controller magic __get().
     *
     * It allows the direct call of the predefind and user-defind properties inside the Container class
     *
     * @param string $property.
     *
     * @return Object|string
     */
    public function __get($property)
    {
        if ($this->container->{$property}) {
            return $this->container->{$property};
        }
    }

    /**
     * Verifies if recaptcha has passed or not.
     *
     *
     * @param string $recaptcha | default=null.
     * @param string $remoteIp | default=$_SERVER['REMOTE_ADDR']
     *
     * @return bool
     */
    protected function checkRecaptcha($recaptchaResponse=null, $remoteIp=null)
    {
        if ($recaptchaResponse !== null) {
            if ($remoteIp === null) $remoteIp = $_SERVER['REMOTE_ADDR'];
            
            // Following the google developer site on how to handle recaptcha responses.
            $resp = $this->recaptcha->verify($recaptchaResponse, $remoteIp);
            if ($resp->isSuccess()) {
                return true;
            } else {
                $errors = $resp->getErrorCodes();
                return $errors; //Array of errors
            }

            // My own implementation. Works great for api responses
            // $client = new \GuzzleHttp\Client();
            // $res = $client->request('POST', 'https://www.google.com/recaptcha/api/siteverify', [
            //     'form_params' => [
            //         'secret' => $this->container['configs']['auth']['recaptcha']['secret'],
            //         'response' => $recaptcha
            //      ]
            // ]);
            // $reponse = (string) $res->getBody();
            // return $response['success'] === true ? true : false;
        }

        return false;
    }
}
