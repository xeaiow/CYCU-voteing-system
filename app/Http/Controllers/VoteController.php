<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Activity;
use App\Groups;
use App\Vote;
use Redirect;

class VoteController extends Controller
{
    // 活動個別資訊
    public function activity_content ()
    {
        return view('activity.content');
    }

    // 取得所有可投票的活動
    public function get_activity ()
    {
        $today = date('Y-m-d');
        echo Activity::Where('started', '<=', $today)->Where('deadline', '>=', $today)->get();
    }

    // 取得指定的競賽資訊
    public function get_activity_info (Request $request)
    {
        $activity = Groups::find($request->id);
        echo Activity::find($activity['activity']);
    }

    // 取得組別資訊
    public function get_groups_info (Request $request)
    {
        $today = date('Y-m-d');

        $activity = Activity::Where('_id', $request->id)->Where('started', '<=', $today)->Where('deadline', '>=', $today);
        
        if ($activity->count() == 1)
        {
            $response['activity']   = $activity->first();
            $response['groups']     = Groups::Where('activity', $request->id)->get();
            $response['message']    = false;
            return json_encode($response);
        }

        $response['message'] = true;

        echo json_encode($response);
    }

    // 取得組別個別資訊
    public function get_group_info (Request $request)
    {
        $today    = date('Y-m-d');

        $getGroupsInfo = Groups::Where('_id', $request->id)->first();

        $activity = Activity::Where('_id', $getGroupsInfo['activity'])->Where('started', '<=', $today)->Where('deadline', '>=', $today);
        
        if ($activity->count() == 1)
        {
            $response['info']   = $getGroupsInfo;
            $response['status'] = true;
            return json_encode($response);
        }

        $response['status'] = false;
        echo json_encode($response);
    }

    // 針對某個組投票
    public function get_group_voting (Request $request)
    {
        $count = Vote::Where('student_id', $request->student_id)->Where('group_id', $request->id)->count();

        // 如果投過就阻止
        if ($count > 0)
        {
            $response['status'] = false;
            return json_encode($response);
        }

        Vote::create([
            'group_id'   => $request->id,
            'student_id' => $request->student_id,
            'class'      => $request->class
        ]);
    }
}
