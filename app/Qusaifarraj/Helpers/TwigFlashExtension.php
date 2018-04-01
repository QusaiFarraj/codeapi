<?php

namespace Qusaifarraj\Helpers;

use \Slim\Flash\Messages as Flash;
use Twig_Extension;
use Twig_SimpleFunction;


class TwigFlashExtension extends Twig_Extension
{
    protected $flash;
    
    public function __construct(Flash $flash)
    {
        $this->flash = $flash;
    }

    public function getName()
    {
        return 'twig-flash-extension';
    }

    public function getFunctions()
    {
        return [
            new Twig_SimpleFunction('flash', [$this, 'getMessages']),
            new Twig_SimpleFunction('flash_has_message', [$this, 'hasMessage']),
        ];
    }

    /**
    * Gets all Flash messages or by key
    */
    public function getMessages($key=null)
    {
        if ($key !== null) {
            return $this->flash->getFirstMessage($key);
        }
        return $this->flash->getMessages();
    }

    /**
    * Returns if Flash has a message for this key
    */
    public function hasMessage($key=null)
    {
        if ($key !== null) {
            return $this->flash->hasMessage($key);
        }

        return false;
    }
}