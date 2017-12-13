<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Activity;
use App\Groups;
use App\Vote;
use App\Manager;
use Redirect;
use Session;

class PineappleController extends Controller
{
    // 新增活動
    public function activity_create (Request $request)
    {
        
        $data = [
            'title' => $request->title,
            'description' => $request->description,
            'voter' => $request->voter,
            'img' => $request->img,
            'started' => $request->started,
            'deadline' => $request->deadline
        ];

        $Activity = Activity::create($data);
        echo $Activity->id;
    }

    // imgur 可多張圖片上傳
    public function upload_image (Request $request)
    {
        $client = new \GuzzleHttp\Client();
        $result = array();

        foreach ((array) $request->userImage as $index => $tmpName)
        {
            $result[] = json_decode($client->request('POST', 'https://api.imgur.com/3/image', [
                'headers' => [
                    'authorization' => 'Client-ID ' . '5f2eaa3314e3d73',
                    'content-type' => 'application/x-www-form-urlencoded',
                ],
                'form_params' => [
                    'image' => base64_encode(file_get_contents($tmpName))
                ],
            ])->getBody()->getContents());
        }

        
        return response()->json(($result));
    }

    // 取得未新增組別的活動資訊
    public function get_activity_create (Request $request)
    {
        echo Activity::Where('_id', $request->id)->first();
    } 

    // 在活動中新增組別
    public function finished_groups_create (Request $request)
    {
        $data = [
            'activity' => $request->activity,
            'groups' => $request->groups,
            'description' => $request->description,
            'photo' => $request->photo,
            'img' => $request->cover,
            'count' => 0
        ];

        Groups::create($data);
    }

    // 判斷登入狀態
    public function login_status ()
    {
        $getLoginInfo = Manager::Where('username', Session::get('m_username'))->Where('token', Session::get('m_token'));
        
        if ($getLoginInfo->count() == 1)
        {
            return $getLoginInfo->first();
        }
    }

    // 登入作業
    public function login_handle (Request $request)
    {
        $getUserInfo = Manager::Where('username', $request->username)->Where('password', $request->password);

        if ($getUserInfo->count() == 1)
        {
            $token = md5(uniqid(rand()));

            // 給予 token 跟 username 的 session
            Session::put('m_token', $token);
            Session::put('m_username', $request->username);

            // 更新 token
            $getUserInfo->update(['token' => $token]);

            // 回傳個人資料
            return $getUserInfo->first();
        
        }
    }

    // 登出
    public function logout_handle ()
    {
        Session::forget('m_token');
        Session::forget('m_username');
        return redirect('/pineapple');
    }

    // 取得活動
    public function get_activity (Request $request)
    {
        $today = date('Y-m-d');

        switch ($request->id) {
            case '1':
                $result = Activity::Where('started', '<=', $today)->Where('deadline', '>=', $today)->get();
                break;
            
            default:
                # code...
                break;
        }
        echo $result;
    }

    // 更新活動資訊
    public function set_activity (Request $request)
    {
        $ActivityInfo = Activity::Where('_id', $request->id)->update(
            [
                'title' => $request->title, 
                'description' => $request->description,
                'started' => $request->started,
                'deadline' => $request->deadline
            ]
        );

        // $ActivityInfo->title = $request->title;
        // $ActivityInfo->description = $request->description;
        // $ActivityInfo->started = $request->started;
        // $ActivityInfo->deadline = $request->deadline;

        // $ActivityInfo->save();
    }
}
