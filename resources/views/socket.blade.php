<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>QiSu</title>
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
        <div class="title m-b-md">
            qisu
        </div>

        <div class="links">
            <input type="text" id="test"/>
        </div>
    </div>
</div>
<script type="text/javascript" src="https://qs.geekheal.net:6001/socket.io/socket.io.js"></script>
<script src="/js/app.js"></script>
<script>

</script>

</body>
</html>
