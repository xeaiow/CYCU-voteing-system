<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class VoteController extends Controller
{

    public function index ()
    {
        return view('front');
    }

    // 活動列表
    public function activity_lists ()
    {
        return view('activity.lists');
    }

    // 活動列表
    public function activity ()
    {
        return view('activity.index');
    }

    // 活動個別資訊
    public function activity_content ()
    {
        return view('activity.content');
    }
}
