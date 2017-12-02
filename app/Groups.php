<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class Groups extends Eloquent
{
    protected $primaryKey = '_id';
    protected $collection = 'groups';
    protected $fillable = ['id', 'activity', 'groups', 'img', 'count'];
}
