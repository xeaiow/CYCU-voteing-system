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
            'photo' => $request->cover,
            'img' => $request->img,
            'count' => 1
        ];

        Groups::create($data);
    }
}
