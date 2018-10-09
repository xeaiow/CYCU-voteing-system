<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Redirect;
use Session;
use App\Users;
use App\Groups;
use App\Vote;


class SystemController extends Controller
{
    // 確認 iTouch 身分
    public function login_touch (Request $request)
    {
        $url        = "https://itouch.cycu.edu.tw/active_system/login/login2.jsp" ;
        $ref_url    = "https://itouch.cycu.edu.tw/active_project/cycu2100h_06/acpm3/json/ss_loginUser.jsp";
        $userId     = $request->userId;
        $password   = $request->password;
        $group_id   = $request->group_id;
        
        $cookie_jar = "./cookie.txt";
        
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
        curl_setopt($ch, CURLOPT_POSTFIELDS, "UserNm=".$userId."&UserPasswd=".$password);
        curl_exec ($ch);

        curl_close ($ch);
        
        $ch2 = curl_init();

        curl_setopt($ch2, CURLOPT_URL, "https://itouch.cycu.edu.tw/active_project/cycu2100h_06/acpm3/json/ss_loginUser.jsp");
        curl_setopt($ch2, CURLOPT_HEADER, 0);
        curl_setopt($ch2, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch2, CURLOPT_USERAGENT,"Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)" );
        curl_setopt($ch2, CURLOPT_CONNECTTIMEOUT, 0);

        curl_setopt($ch2, CURLOPT_COOKIEFILE, $cookie_jar);
        
        $orders = (array) json_decode(curl_exec($ch2));
        curl_close($ch2);


        $dept = array('資訊管理學系一年甲班', '資訊管理學系一年乙班', '資訊管理學系二年甲班', '資訊管理學系二年乙班', '資訊管理學系三年甲班', '資訊管理學系三年乙班');

        // 判斷系級或資格不符直接剔除
        if ($orders['type2'] !== "student" || !in_array($orders['i_DEPT_NAME_C'], $dept, true))
        {
            $response['status'] = false;
            $response['msg']    = 1; // 資格不符
            return json_encode($response);
        }
        else
        {
            $token = bin2hex(random_bytes(32));
            $orders['token'] = $token;
            
            // 新增使用者資料
            Users::create($orders);

            $response['token']  = $token;
            $response['name']   = $orders['name'];
            $response['dept']   = $orders['i_DEPT_NAME_C'];
            $response['status'] = true;

            return json_encode($response);
        }
    }

    public function voting (Request $req)
    {
        // 由 token 取得學生資料
        $orders = Users::where('token', $req->token);

        // 檢測是否有這組 token
        if ($orders->count() != 1)
        {
            $response['status'] = false;
            $response['msg'] = 3;
            return json_encode($response);
        }

        // 取得這個組別的競賽 id
        $groups = Groups::where('_id', $req->group_id)->first();

        // 抓出這組 token 的個人資料
        $userInfo = $orders->first()->toArray();

        // 判斷這組帳號是否已經投過
        $thisGroups = Vote::where('info.idcode', $userInfo['idcode'])->where('activity_id', $groups['activity']);
        $votingLimit = $thisGroups->count();

        // 判斷是否已經投過這組 或 判斷是否對這個活動投超過三次
        if ( $thisGroups->where('group_id', $req->group_id)->count() == 1 || $votingLimit >= 3 )
        {
            $response['status'] = false;
            $response['msg'] = 1;
            return json_encode($response);
        }
        else
        {
            // 欲投票資料
            $voting = [
                'group_id'      => $req->group_id,
                'activity_id'   => $groups['activity'],
                'info'          => $userInfo
            ];

            // 新增投票資料
            Vote::create($voting);

            // 更新票數
            Groups::Where('_id', $req->group_id)->increment('count');

            $response['count']  = 3 - ($votingLimit + 1);
            $response['status'] = true;
            $response['token']  = $userInfo['token'];
            $response['name']   = $userInfo['name'];
            $response['dept']   = $userInfo['i_DEPT_NAME_C'];

            return json_encode($response);
        }
    }

    // 登入作業
    public function login_handle (Request $request)
    {
        $url        = "http://cmap.cycu.edu.tw:8080//MyMentor/stdLogin.do" ;
        $ref_url    = "http://cmap.cycu.edu.tw:8080/MyMentor/courseCreditStructure.do";
        $userId     = $request->username;
        $password   = $request->password;
        
        $cookie_jar = tempnam('/tmp','cookie.txt');
        
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
