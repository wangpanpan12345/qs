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
            padding: 0 20%;
            /*display: flex;*/
            /*align-items: center;*/
            /**/
            /*justify-content: center;*/
        }
        .list{
            margin: 10px 0;
        }

        .position-ref {
            position: relative;
        }
        .source{
            display: inline-block;
            width: 220px;
        }

        .top-right {
            position: absolute;
            right: 10px;
            top: 18px;
        }

        .content {
            text-align: center;
        }

        .title {
            font-size: 84px;
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
        }
    </style>
</head>
<body>
<div class="flex-center position-ref full-height">
    <div class="theme">
        <span>媒体类</span>
        @foreach($media as $k=>$v)
            <div class="list">
                <span class="source">{{$k}}</span>
                <span><a href="{{$v}}" target="_blank">{{$v}}</a></span>
            </div>
        @endforeach
    </div>
    <div class="theme">
        <span>期刊类</span>
        @foreach($journal as $k=>$v)
            <div class="list">
                <span class="source">{{$k}}</span>
                <span><a href="{{$v}}" target="_blank">{{$v}}</a></span>
            </div>
        @endforeach
    </div>
    <div class="theme">
        <span>政策类</span>
        @foreach($policy as $k=>$v)
            <div class="list">
                <span class="source">{{$k}}</span>
                <span><a href="{{$v}}" target="_blank">{{$v}}</a></span>
            </div>
        @endforeach
    </div>
    <div class="theme">
        <span>公众号</span>
        {{--@foreach($policy as $k=>$v)--}}
            {{--<div>--}}
                {{--<span>{{$k}}</span>--}}
                {{--<span><a href="{{$v}}" target="_blank">{{$v}}</a></span>--}}
            {{--</div>--}}
        {{--@endforeach--}}
    </div>
</div>
</body>
</html>
