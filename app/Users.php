<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class Users extends Eloquent
{
    protected $primaryKey = '_id';
    protected $collection = 'users';
    protected $fillable = ['username', 'password'];
    protected $hidden = ['password', '_id', 'updated_at'];
}
