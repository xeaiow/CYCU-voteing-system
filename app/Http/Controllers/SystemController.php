<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SystemController extends Controller
{
    // 登入頁面
    public function login ()
    {
        return view('login');
    }
}
