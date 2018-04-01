<?php


namespace Qusaifarraj\Models;

use \Illuminate\Database\Eloquent\Model;


/**
* 
*/
class UserPermission extends Model{

    protected $table = 'user_permissions';

    protected $fillable = [
        'is_admin'
    ];

    public static $defaults = [
        'is_admin' => false
    ];

}