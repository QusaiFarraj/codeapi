<?php

namespace Qusaifarraj\Models;

use \Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $table = 'users';

    protected $fillable =[
        'username',
        'first_name',
        'last_name',
        'email',
        'password',
        'active',
        'active_hash',
        'recover_hash',
        'remember_identifier',
        'remember_token'
    ];

    public function getFullName()
    {
        if(!$this->first_name || !$this->last_name){
            return null;
        }

        return "{$this->first_name} {$this->last_name}";
    }

    public function getFullNameOrUsername(){
        
        return $this->getFullName() ?: $this->username;
    }

    public function activateAccount($value, $hash){
        $this->update([
            'active' => $value,
            'active_hash' => $hash
        ]);
    }

    public function deactivateAccount($hash = null)
    {
        $this->activateAccount(false, $hash);
    }


    public function getAvatarUrl($options = []){
        
        $size = isset($options['size']) ? $options['size'] : 45;

        return 'https://www.gravatar.com/avatar/'. md5($this->email). '?s=' . $size . '&d=mm';
    }

    public function updateRememberCredentials($identifier, $token){
        return $this->update([
            'remember_identifier' => $identifier,
            'remember_token' => $token
        ]);
    }

    public function removeRememberCredentials(){
        
        $this->updateRememberCredentials(null, null);
    }

    public function hasPermission($permission){
        return (bool) $this->permissions->{$permission};
    }

    public function isAdmin(){
        return $this->hasPermission('is_admin'); 
    }

    public function permissions(){

        return $this->hasOne('\Qusaifarraj\Models\UserPermission', 'user_id');
    }
}
