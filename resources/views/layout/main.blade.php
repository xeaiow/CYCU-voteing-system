<!DOCTYPE html>
<html lang="zh-tw">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="https://unpkg.com/vue"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="{{ asset('js/semantic.min.js') }}"></script>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <link rel="stylesheet" href="{{ asset('css/semantic.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <title>活動列表 - 中原大學資訊管理學系投票系統</title>
</head>
<body>

<div class="ui inverted menu cycuvote-theme cycuvote-menu fixed">
    <a class="active item font-style" href="{{ url('/') }}">
        中原大學資訊管理學系投票系統
    </a>
    <a class="item font-style" href="{{ url('/') }}">
        活動列表
    </a>
    <div class="right menu">
        <a class="item font-style" href="{{ url('login') }}">登入</a>
    </div>
</div>

<div class="ui container">
    @yield('content')
</div>

</body>
</html>