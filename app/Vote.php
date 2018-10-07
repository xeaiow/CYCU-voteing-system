<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class Vote extends Eloquent
{
    protected $primaryKey = '_id';
    protected $collection = 'vote';
    protected $fillable = ['group_id', 'class', 'student_id', 'activity_id', 'info'];
}
