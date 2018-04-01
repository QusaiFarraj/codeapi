<?php

namespace Qusaifarraj\Helpers;


/**
* Hash class that manages password and identifire hashing 
*/
class Hash
{

    protected $config;
    
    function __construct($config)
    {
        $this->config = $config;
    }

    public function password($password){
        return password_hash(
            $password, 
            $this->config['algo'], 
            ['cost' => $this->config['cost']]
        );
    }

    public function passwordCheck($password, $hash){
       return password_verify($password, $hash);
    }

    public function hash($val){
        return hash('sha256', $val);
    }

    public function hashCheck($know, $val){
        return hash_equals($know, $val);   
    }
}