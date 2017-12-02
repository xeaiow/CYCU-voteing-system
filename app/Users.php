<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class Users extends Eloquent
{
    protected $primaryKey = '_id';
    protected $collection = 'users';
    protected $fillable = ['username', 'password', 'token', 'class', 'level'];
    protected $hidden = ['password', '_id', 'updated_at'];
}
