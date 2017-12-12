<?php

namespace App\Http\Middleware;

use Closure;
use Session;
use Redirect;

class ManagerisLoginMiddleware
{
    public function handle($request, Closure $next)
    {
        // 判斷是否已登入，如果登入就踢回首頁
        if (!Session::has('m_username')) {
            
            return Redirect::to('/pineapple/login');
        }
        return $next($request);
    }
}
