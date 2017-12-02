<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Redirect;
use Session;
use App\Users;

class SystemController extends Controller
{
    // 登入頁面
    public function login ()
    {
        return view('login');
    }
    
    // 確認 iTouch 身分
    public function login_touch (Request $request)
    {

        $url        = "http://cmap.cycu.edu.tw:8080//MyMentor/stdLogin.do" ;
        $ref_url    = "http://cmap.cycu.edu.tw:8080/MyMentor/courseCreditStructure.do";
        $userId     = //$request->userId;
        $password   = //$request->password;
        
        $cookie_jar = tempnam('./tmp','cookie.txt');
        
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_jar);
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)");
        curl_setopt($ch, CURLOPT_TIMEOUT, 40);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_REFERER, $ref_url);
        curl_setopt($ch, CURLOPT_HEADER, TRUE);
        curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "userId=10244257&password=Dream0919");
        curl_exec ($ch);

        curl_close ($ch);
        
        $ch2 = curl_init();

        curl_setopt($ch2, CURLOPT_URL, "http://cmap.cycu.edu.tw:8080/MyMentor/courseCreditStructure.do");
        curl_setopt($ch2, CURLOPT_HEADER, 0);
        curl_setopt($ch2, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch2, CURLOPT_USERAGENT,"Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)" );
        curl_setopt($ch2, CURLOPT_CONNECTTIMEOUT, 0);

        curl_setopt($ch2, CURLOPT_COOKIEFILE, $cookie_jar);
        
        $orders = curl_exec($ch2);
        curl_close($ch2);
        
        preg_match_all("/\<div id=\"selectbox\"\>(.*?)\<\/div\>/is", $orders, $arr);

        echo preg_replace('/\s+/', '', strip_tags($arr[0][0]));
    }

    // 登入作業
    public function login_handle (Request $request)
    {
        $url        = "http://cmap.cycu.edu.tw:8080//MyMentor/stdLogin.do" ;
        $ref_url    = "http://cmap.cycu.edu.tw:8080/MyMentor/courseCreditStructure.do";
        $userId     = $request->username;
        $password   = $request->password;
        
        $cookie_jar = tempnam('./tmp','cookie.txt');
        
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_jar);
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)");
        curl_setopt($ch, CURLOPT_TIMEOUT, 40);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_REFERER, $ref_url);
        curl_setopt($ch, CURLOPT_HEADER, TRUE);
        curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "userId=".$userId."&password=".$password);
        curl_exec ($ch);

        curl_close ($ch);
        
        $ch2 = curl_init();

        curl_setopt($ch2, CURLOPT_URL, "http://cmap.cycu.edu.tw:8080/MyMentor/courseCreditStructure.do");
        curl_setopt($ch2, CURLOPT_HEADER, 0);
        curl_setopt($ch2, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch2, CURLOPT_USERAGENT,"Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)" );
        curl_setopt($ch2, CURLOPT_CONNECTTIMEOUT, 0);

        curl_setopt($ch2, CURLOPT_COOKIEFILE, $cookie_jar);
        
        $orders = curl_exec($ch2);
        curl_close($ch2);
        
        preg_match_all("/\<div id=\"selectbox\"\>(.*?)\<\/div\>/is", $orders, $arr);


        // 判斷是否成功登入 iTouch
        if (empty($arr[0]) == 1)
        {
            $result['login_status'] = 0;
        }
        else
        {
            $str = preg_replace('/\s+/', '', strip_tags($arr[0][0]));

            // 產生一組新的 token
            $token = md5(uniqid(rand()));
            
            // 更新該使用者的 token
            $getUserInfo = Users::Where('username', $userId);
        
            if ($getUserInfo->count() == 0)
            {
                // 新增學生資料
                $profile = [
                    'username' => $userId,
                    'token' => $token,
                    'class' => $str
                ];

                $create_id = Users::create($profile)->id;

                // 新增系級資訊
                $profile = Users::Where('_id', $create_id);
                $profile->update(['level' => mb_substr($profile->first()['class'], -4)]);

                Session::put('level', mb_substr($profile->first()['class'], -4));
            }
            else
            {
                $getUserInfo->update(['token' => $token]);
                Session::put('level', $getUserInfo->first()['level']);
            }

            // 給予 token 跟 username 的 session
            Session::put('token', $token);
            Session::put('username', $userId);

            // 回傳個人資料
            $result = $getUserInfo->first();
            $result['login_status'] = 1;
        }
        echo json_encode($result);
    }

    // 判斷登入狀態
    public function login_status ()
    {
        $getLoginInfo = Users::Where('username', Session::get('username'))->Where('token', Session::get('token'));
        
        if ($getLoginInfo->count() == 1)
        {
            return $getLoginInfo->first();
        }

        $response['status'] = false;
        return $response;
    }

    // 登出
    public function logout_handle ()
    {
        Session::flush();
        return redirect('/');
    }

    // 初次登入儲存資訊
    public function login_save_handle (Request $request)
    {
        // 產生一組新的 token
        $token = md5(uniqid(rand()));

        // 新增學生資料
        $profile = [
            'username' => $request->username,
            'token' => $token
        ];

        $result = Users::create($profile);
        
        // 給予 token 跟 username 的 session
        Session::put('token', $token);
        Session::put('username', $request->username);

        echo $result;
    }

}
