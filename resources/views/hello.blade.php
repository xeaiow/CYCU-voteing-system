<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Hello</title>
</head>
<body>
    <!-- 準備給 Vue 的掛載點 -->
    <div id="app">
        <!-- 使用我們建立的元件，
             一個有傳入名字，一個沒有 -->
        <Hello name="Tony"></Hello>
        <Hello></Hello>
    </div>
    <!-- 載入打包後的 js 檔 -->
    <script src="{{ asset('js/hello.js') }}"></script>
    <script src="https://unpkg.com/vue-router/dist/vue-router.js"></script>
</body>
</html>