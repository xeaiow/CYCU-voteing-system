<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Redirect;
use Session;
use App\Users;
use App\Groups;
use App\Vote;
use App\Account;


class SystemController extends Controller
{
    public function login_demo (Request $request)
    {
        $username = $request->username;
        $getAccount = Account::where('username', $request->username)->where('password', $request->password);
        
        if ($getAccount->count() == 1) {

            $token = bin2hex(random_bytes(32));

            $userInfo = $getAccount->first(['name', 'dept']);

            Account::where('username', $username)->update(['token'=> $token]);

            $response['name']       = $userInfo['name'];
            $response['username']   = $username;
            $response['dept']       = $userInfo['dept'];
            $response['token']      = $token;
            $response['status'] = true;
        }
        else
        {
            $response['status'] = false;
        }
        return json_encode($response);
    }

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


        $dept = array('資訊管理學系一年甲班', '資訊管理學系一年乙班', '資訊管理學系二年甲班', '資訊管理學系二年乙班', '資訊管理學系三年甲班', '資訊管理學系三年乙班', '資訊管理系碩士班二年級');

        // 判斷系級或資格不符直接剔除
        if ($orders['type2'] !== "student" || !in_array($orders['i_DEPT_NAME_C'], $dept, true))
        {
            $response['status'] = false;
            $response['msg']    = 1; // 資格不符
            return json_encode($response);
        }

        $token = bin2hex(random_bytes(32));
        $response['token']  = $token;

        $user = Users::where('idcode', $userId);

        if ($user->count() > 0)
        {
            $userInfo = $user->first(['name', 'i_DEPT_NAME_C']);

            Users::where('idcode', $userId)->update(['token'=> $token]);

            $response['name']   = $userInfo['name'];
            $response['username']   = $userId;
            $response['dept']   = $userInfo['i_DEPT_NAME_C'];
        }
        else
        {
            $orders['token'] = $token;
        
            // 新增使用者資料
            Users::create($orders);
    
            $response['name']   = $orders['name'];
            $response['username']   = $userId;
            $response['dept']   = $orders['i_DEPT_NAME_C']; 
        }
        
        $response['status'] = true;
        return json_encode($response);
    }

    public function voting (Request $req)
    {
        if ($req->token == null || $req->group_id == null)
        {
            $response['status'] = false;
            return json_encode($response);
        }

        // 由 token 取得學生資料
        $orders = Account::where('token', $req->token);

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
        $thisGroups = Vote::where('idcode', $userInfo['username'])->where('activity_id', $groups['activity']);
        $votingLimit = $thisGroups->count();

        // 判斷是否已經投過這組 或 判斷是否對這個活動投超過三次
        if ( $votingLimit >= 3 )
        {
            $response['status'] = false;
            $response['msg'] = 1;
            return json_encode($response);
        }

        if ( $thisGroups->where('group_id', $req->group_id)->count() == 1 )
        {
            $response['status'] = false;
            $response['msg'] = 2;
            return json_encode($response);
        }

        if ($groups['activity'] == null)
        {
            $response['status'] = false;
            return json_encode($response);
        }
        
        // 欲投票資料
        $voting = [
            'group_id'      => $req->group_id,
            'activity_id'   => $groups['activity'],
            'id'            => $userInfo['_id'],
            'idcode'        => $userInfo['username'],
            'token'         => $userInfo['token']
        ];

        // 新增投票資料
        Vote::create($voting);

        // 更新票數
        Groups::Where('_id', $req->group_id)->increment('count');

        $response['count']  = 3 - ($votingLimit + 1);
        $response['status'] = true;
        $response['token']  = $userInfo['token'];
        $response['name']   = $userInfo['name'];
        $response['dept']   = $userInfo['dept'];

        return json_encode($response);
    }
}
