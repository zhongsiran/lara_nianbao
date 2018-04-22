<!DOCTYPE html>
<html lang="zh_CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{csrf_token()}}">
    <meta name="update-link" content="{{ route('corp.update', $corp->RegNum)}}">
    <link rel="stylesheet" href="/css/app.css">
    <title>年报系统</title>
</head>
<body>
    <div class="container" id="app">
        @yield('content')
    </div>
</body>
<script src="/js/app.js"></script>
</html>