<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Activity;
use App\Groups;
use App\Vote;
use Redirect;
use Session;

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
        
        $voting   = Vote::Where('student_id', Session::get('username'))->Where('activity_id', $getGroupsInfo['activity'])->count();

        if ($activity->count() == 1)
        {
            $response['info']   = $getGroupsInfo;
            $response['status'] = true;
            $response['voting'] = $voting;

            return json_encode($response);
        }

        $response['status'] = false;
        echo json_encode($response);
    }

    // 確定投票
    public function voting_handle (Request $request)
    {
        $group_id   = $request->id;
        $student_id = Session::get('username');

        $isExists = Vote::Where('group_id', $group_id)->Where('student_id', $student_id);
        $groups = Groups::Where('_id', $group_id)->first();

        if ($isExists->count() == 0)
        {
            $voting = [
                'student_id' => $student_id,
                'group_id' => $group_id,
                'activity_id' => $groups['activity']
            ];

            Vote::create($voting);

            $response['status'] = true;

            return json_encode($response);
        }

        $response['status'] = false;
        return json_encode($response);
    }
}
