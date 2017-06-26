<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>QiSu</title>

    <!-- Fonts -->
    <link href="https://fonts.css.network/css?family=Raleway:100,600" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <style>
        html, body {
            background-color: #fff;
            color: #636b6f;
            font-family: 'Raleway';
            font-weight: 100;
            height: 100vh;
            margin: 0;
        }

        .full-height {
            height: 100vh;
        }

        .flex-center {
            align-items: center;
            display: flex;
            justify-content: center;
        }

        .position-ref {
            position: relative;
        }

        .content {
            text-align: center;
        }

        .title {
            font-size: 84px;
        }

        .m-b-md {
            margin-bottom: 30px;
        }
        .title input{
            width: 500px;
            height: 50px;
            line-height: 50px;
        }
    </style>
</head>
<body>
<div class="flex-center position-ref full-height">
    @if (Route::has('login'))
        {{--<div class="top-right links">--}}
        {{--<a href="{{ url('/login') }}">Login</a>--}}
        {{--<a href="{{ url('/register') }}">Register</a>--}}
        {{--</div>--}}
    @endif

    <div class="content">
        <div>输入该人物的百度百科地址,敲回车就可以了</div>
        <div class="title m-b-md">
            <form method="POST" action="/baike/indb">
                {{ csrf_field() }}
                <input name="url" placeholder="输入网址(限定人维度的数据)"/>
            </form>
        </div>

    </div>
</div>
</body>
</html>
