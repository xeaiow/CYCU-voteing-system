<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Activity;
use App\Groups;
use App\Vote;
use Redirect;
use Session;

class PineappleController extends Controller
{
    // 新增活動
    public function activity_create (Request $request)
    {
        
        $activity = [
            'title' => $request->title,
            'description' => $request->description,
            'voter' => $request->voter,
            'img' => 'https://static.collectui.com/shots/3874110/the-night-king-large',
            'started' => $request->started,
            'deadline' => $request->deadline
        ];

        Activity::create($activity);
    }
}
