<?php

namespace Qusaifarraj\Helpers;

use \Violin\Violin;
use \Qusaifarraj\Models\User;
use \Qusaifarraj\Helpers\Hash;
use \Qusaifarraj\Auth\Auth;


/**
* Validation Class. It contains custom vaildation rule and field messages.
*/
class Validator extends Violin
{    
    protected $user;
    protected $hash;
    protected $auth;

    public function __construct(Hash $hash, Auth $auth=null)
    {
        $this->user = new User();
        $this->hash = $hash;
        $this->auth = $auth;

        $this->addFieldMessages([
            'email' => [
                'uniqueEmail' => 'Email is already in use. '
            ],
            'username' => [
                'uniqueUsername' => 'Username is already taken.'
            ]
        ]);

        $this->addRuleMessages([
            'matchesCurrentPassword' => 'That does not match your current password.',
            'confirmPasswordMatch' => 'Confrim Password doesn\'t match New Password'
        ]);
    }

    public function validate_uniqueEmail($value, $input, $args)
    {
        $user = $this->user->where('email', $value);
        return ! (bool) $user->count(); // to know if it exists then return false
    }

    public function validate_uniqueUsername($value, $input, $args)
    {
        return ! (bool) $this->user->where('username', $value)->count();
    }

    public function validate_matchesCurrentPassword($value, $input, $args)
    {
        if ($this->auth->user() && $this->hash->passwordCheck($value, $this->auth->user()->password)) {
            return true;
        }
        return false;
    }

    // for now to change the password confirm error msg output
    public function validate_confirmPasswordMatch($value, $input, $args)
    {
        return $value === $input[$args[0]];
    }
}
