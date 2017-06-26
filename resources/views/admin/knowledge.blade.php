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
            /*height: 100vh;*/
        }

        .flex-center {
            align-items: center;
            /*display: flex;*/
            justify-content: center;
        }

        .position-ref {
            position: relative;
        }

        .top-right {
            position: absolute;
            right: 10px;
            top: 18px;
        }

        .content {
            /*padding: 20px 10%;*/
            margin: 0 auto;
            word-break: break-all;
            font-size: 16px;
            font-weight: 400;
            line-height: 1.7;
            width: 720px;
        }

        .title {
            margin: 20px 0 0;
            font-size: 34px;
            text-align: center;
            font-family: -apple-system, SF UI Display, Arial, PingFang SC, Hiragino Sans GB, Microsoft YaHei, WenQuanYi Micro Hei, sans-serif;
            font-weight: 700;
            line-height: 1.3;
            color: #333;
        }

        .links > a {
            color: #636b6f;
            padding: 0 25px;
            font-size: 12px;
            font-weight: 600;
            letter-spacing: .1rem;
            text-decoration: none;
            text-transform: uppercase;
        }

        .m-b-md {
            margin-bottom: 30px;
            text-align: left;
            font-size: 24px;
        }
    </style>
</head>
<body>
<div class="flex-center position-ref full-height">
    @if (Route::has('login'))
    @endif
    @if(!empty($knowledge))
        <div class="title">
            {{$knowledge[0]->tags}}
        </div>
        @foreach($knowledge as $k=>$v)
            <div class="content">
                <div class="title m-b-md">
                    {{$v->type}}
                </div>

                <div class="links">
                    {!! $v->content !!}
                </div>
            </div>
        @endforeach
    @endif
</div>
</body>
</html>
