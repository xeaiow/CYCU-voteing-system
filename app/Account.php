<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class Account extends Eloquent
{
    protected $primaryKey = '_id';
    protected $collection = 'account';
    protected $fillable = ['type', 'username', 'password', 'name', 'email', 'dept', 'token'];
}
