<html>
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>奇速 - @yield('title')</title>
    {{--<link href="//cdn.bootcss.com/bootstrap/4.0.0-alpha.3/css/bootstrap.min.css" rel="stylesheet">--}}
    <link href="/css/app.css" rel="stylesheet">
    <link rel="shortcut icon" type="image/x-icon" href="https://oi7gusker.qnssl.com/qisu/logo/%20qisu%20logo-02.png">
    <style>
        .navbar-nav > li > a:hover {
            color: #1e88e5 !important;
            -webkit-transition: all .3s ease-in .1s;
            transition: all .3s ease-in .1s;
        }

        @media (max-width: 748px) {

        }
    </style>
</head>
<body>
@section('sidebar')
    <nav class="navbar navbar-default navbar-static-top">
        <div class="container">
            <div class="navbar-header">

                <!-- Collapsed Hamburger -->
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                        data-target="#app-navbar-collapse">
                    <span class="sr-only">Toggle Navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>

                <!-- Branding Image -->
                <a class="navbar-brand" href="{{ url('/qs-admin') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
            </div>

            <div class="collapse navbar-collapse" id="app-navbar-collapse">
                <!-- Left Side Of Navbar -->
                <ul class="nav navbar-nav">
                    <li><a href="/qs-admin/show">CompanyList</a></li>
                    <li><a href="/dailynews">DailyNews</a></li>
                    <li><a href="/qs-admin/keynews">Highlight</a></li>
                    <li><a href="/dailyfunds">DailyFunds</a></li>
                    <li><a href="/qs-admin/tag/manage">Tags(T)</a></li>
                    <li><a href="/qs-admin/tag/manage_p">Tags(P)</a></li>
                    <li><a href="/baike">百科人物采集</a></li>

                </ul>

                <!-- Right Side Of Navbar -->
                <ul class="nav navbar-nav navbar-right">
                    <li class="mobile_search" style="width: 100px;margin-right: 50px;">
                        <form style="position: relative;top: 10px;" method="POST" action="/qs-admin/search">
                            <input type="text" name="k" id="k" placeholder="search "/>
                            <?php //echo method_field('PUT'); ?>
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        </form>
                    </li>
                    <!-- Authentication Links -->
                    @if (Auth::guest())
                        <li><a href="{{ url('/login') }}">Login</a></li>
                        <li><a href="{{ url('/register') }}">Register</a></li>
                    @else
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                               aria-expanded="false">
                                {{ Auth::user()->name }} <span class="caret"></span>
                            </a>

                            <ul class="dropdown-menu" role="menu">
                                <li>
                                    <a href="{{ url('/logout') }}"
                                       onclick="event.preventDefault();
                                                 document.getElementById('logout-form').submit();">
                                        Logout
                                    </a>
                                    <a href="{{ url('/qs-admin/profile') }}">
                                        个人主页
                                    </a>

                                    <form id="logout-form" action="{{ url('/logout') }}" method="POST"
                                          style="display: none;">
                                        {{ csrf_field() }}
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @endif

                </ul>
            </div>
        </div>
    </nav>
    {{--<div class="navbar navbar-default navbar-static-top  sidbar">--}}
    {{--<div class="container">--}}
    {{--<span><a href="/qs-admin/">HOME</a></span>--}}
    {{--<span><a href="/qs-admin/show">CompanyList</a></span>--}}
    {{--<span><a href="/dailynews">DailyNews</a></span>--}}

    {{--<div class="search">--}}
    {{--<form method="POST" action="/qs-admin/search">--}}
    {{--<input type="text" name="k" id="k" placeholder="search company"/>--}}
    {{--<input type="hidden" name="_token" value="{{ csrf_token() }}">--}}
    {{--</form>--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--</div>--}}
@show

<div class="container">
    @yield('content')
</div>
</body>

<script>
    //    $(function(){
    //        $(".navbar-toggle").click(function(){
    //            $("#app-navbar-collapse").removeClass("collapse");
    //            $("#app-navbar-collapse").toggle();
    //        })
    //    })
</script>
@section("js")
    <script type="text/javascript" src="https://qs.geekheal.net:6001/socket.io/socket.io.js"></script>
    {{--<script src="https://cdn.socket.io/socket.io-1.4.5.js"></script>--}}
    <script src="/js/app.js"></script>
    <script>
        window.Echo.channel('dailynews.edit.1')
                .listen('EditNotify', function (data) {
                    if (data.user == "wang") {
                        $(".editing").hide();
                    } else {
                        $(".tags[data-id=" + data._id + "]").children(".pub_date").after("<label class='pubished editing'>" + data.user + " is Editing</label>");

                    }

                });
        window.Echo.channel('dailynews.pub.1')
                .listen('PubState', function (data) {
//                $(".tags[data-id=" + data._id + "]").children(".pub_date").after("<label class='pubished'>已发布</label>");
                });

    </script>
@show
</html>