<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class Users extends Eloquent
{
    protected $primaryKey = '_id';
    protected $collection = 'users';
    protected $fillable = ['type2', 'idcode', 'i_XEPTITL_NAME', 'name', 'type', 'email', 'i_DEPT_NAME_C'];
}
