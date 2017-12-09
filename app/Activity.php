<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class Activity extends Eloquent
{
    protected $primaryKey = '_id';
    protected $collection = 'activity';
    protected $fillable = ['id', 'title', 'description', 'voter', 'img', 'started', 'deadline'];
}
