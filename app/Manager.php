<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class Manager extends Eloquent
{
    protected $primaryKey = '_id';
    protected $collection = 'manager';
    protected $fillable = ['username', 'password', 'token', 'level'];
    protected $hidden = ['password', '_id', 'updated_at'];
}
