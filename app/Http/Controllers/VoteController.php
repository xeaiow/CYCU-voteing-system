<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Activity;
use App\Groups;
use Redirect;

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

    // 取得所有可投票的活動
    public function get_activity ()
    {
        echo Activity::all();
    }

    // 取得所有可投票的組別
    public function get_groups ()
    {
        echo Groups::all();
    }
}
