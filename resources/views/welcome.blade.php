<!doctype html>
<html lang="zh-tw">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <meta property="og:title" content="中原大學資訊管理學系投票系統" />
    <meta property="og:url" content="http://140.135.112.191">
    <meta property="og:image" content="https://i.imgur.com/9PFi6gq.jpg" />
    <meta property="og:description" content="擇你所愛，選你所擇" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@7.28.4/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tocas-ui/2.3.3/tocas.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <title>中原大學資訊管理學系投票系統</title>

</head>

<body>

    <div class="ui container">
        <div id="app" v-cloak></div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/tocas-ui/2.3.3/tocas.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@7.28.4/dist/sweetalert2.all.min.js"></script>
    <script src="{{ asset('js/app.js') }}"></script>

    <script>
        window.Laravel = <?php echo json_encode(['csrfToken' => csrf_token()]); ?>
    </script>

</body>

</html>