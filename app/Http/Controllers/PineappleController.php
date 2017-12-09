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
        $response = $client->request('POST', 'https://api.imgur.com/3/image', [
                'headers' => [
                    'authorization' => 'Client-ID ' . '5f2eaa3314e3d73',
                    'content-type' => 'application/x-www-form-urlencoded',
                ],
                'form_params' => [
                    'image' => base64_encode(file_get_contents($request->userImage))
                ],
            ]);
        return response()->json(json_decode(($response->getBody()->getContents())));
    }
}
