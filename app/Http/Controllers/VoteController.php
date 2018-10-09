<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Activity;
use App\Groups;
use App\Vote;
use App\Users;

class VoteController extends Controller
{
    // 取得所有可投票的活動
    public function get_activity ()
    {
        $today = date('Y-m-d');
        echo Activity::Where('started', '<=', $today)->Where('deadline', '>=', $today)->get();
    }

    // 取得所有已完成投票的活動
    public function get_finished_activity ()
    {
        $today = date('Y-m-d');
        echo Activity::Where('deadline', '<', $today)->get();
    }

    // 取得指定的競賽資訊
    public function get_activity_info (Request $request)
    {
        $activity = Groups::where('_id', $request->id);
        
        if ($activity->count() == 0)
        {
            $response['status'] = false;
            return json_encode($response);
        }

        $response['result'] = Activity::where('_id', $activity->first()['activity'])->first(['title', 'description', 'voter', 'img']);
        $response['status'] = true;

        return json_encode($response);
    }

    // 取得組別資訊
    public function get_groups_info (Request $request)
    {
        $today = date('Y-m-d');

        $activity = Activity::Where('_id', $request->id)->Where('started', '<=', $today)->Where('deadline', '>=', $today);
        
        if ($activity->count() == 1)
        {
            $response['activity']   = $activity->first(['title', 'description', 'img']);
            $response['groups']     = Groups::Where('activity', $request->id)->get(['activity', 'groups', 'description', 'photo', 'img']);
            $response['status'] = true;
            
            return json_encode($response);
        }

        $response['status'] = false;

        return json_encode($response);
    }

    // 取得前三名的組別
    public function get_groups_top (Request $request)
    {
        $today = date('Y-m-d');
        
        $activity = Activity::Where('_id', $request->id)->Where('deadline', '<', $today);

        if ($activity->count() == 1)
        {
            $response['activity']   = $activity->first();
            $response['groups']     = Groups::Where('activity', $request->id)->orderBy('count', 'desc')->get();
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

        $getGroupsInfo = Groups::where('_id', $request->id)->first(['activity', 'groups', 'description', 'photo', 'img']);

        $finished = Activity::where('_id', $getGroupsInfo['activity'])->where('started', '<=', $today)->where('deadline', '>=', $today)->count();

        //$fakerData = Users::where('idcode', $request->username)->where('token', $request->token)->count();

        // 判斷該使用者是否已經投過此組，或投過三次該活動
        $voteLimit = Vote::where('idcode', $request->username)->where('activity_id', $getGroupsInfo['activity']);
        $vote = false;

        if ($voteLimit->count() >= 3 || $voteLimit->where('group_id', $request->id)->count() > 0)
        {
            $vote = true;
        }

        if ($finished == 1)
        {
            $response['info']   = $getGroupsInfo;
            $response['status'] = true;
            $response['finished'] = ( $finished == 1 ? false : true ); // 是否已開票，否
            $response['voting'] = $vote;

            return json_encode($response);
        }

        $response['info']   = $getGroupsInfo;
        $response['finished'] = true;
        $response['voting'] = $vote;
        $response['status'] = false;

        echo json_encode($response);
    }
}
