@extends('admin')

@section('title', '每日投融资')

@section('sidebar')
    @parent
@endsection

{{--<link href="https://fonts.css.network/css?family=Raleway:100,600" rel="stylesheet" type="text/css">--}}
<script src="//cdn.bootcss.com/jquery/3.1.1/jquery.min.js"></script>
<script src="//cdn.bootcss.com/jquery-mousewheel/3.1.13/jquery.mousewheel.min.js"></script>
<style>
    body {
        font-family: Raleway, "Pingfang SC", "Microsoft YaHei", Helvetica, STHeiti, Verdana, Arial, Tahoma, sans-serif !important;
    }

    .menu_title {
        position: sticky;
        display: flex;
        align-items: center;
        justify-content: space-around;
        width: 1140px;
        padding-right: 30px;
        margin: 0 auto;
        margin-bottom: 40px;
        background: #fff;
        border: 1px solid #dde0e4;
        z-index: 5;
        height: 50px;
    }

    .fund_list {
        position: relative;
        margin-top: 20px;
    }

    .fund_basic {
        display: flex;
        align-items: center;
        height: 50px;
        justify-content: space-around;
        background: #fff;
    }

    .fund_story {
        padding: 14px 12px;
        background: #f4f4f4;
        color: #a3a3a6;
    }

    .fund_story a {
        color: #a3a3a6;
    }

    .menu_title span, .fund_basic span {
        flex: 1;
        border-left: 0;
        text-align: center;
        color: #4a494a;
    }

    .fund_basic a {
        color: #4a494a;
    }

    .list {
        position: relative;
        margin: 8px 0;
        border: 1px solid #e2e2e2;
        border-left: 10px solid #d8d8d8;
    }

    .today {
        border-left: 10px solid #1e88e5;
    }

    .title a {
        color: #a2a2a2;
    }

    .title a:hover {
        color: #fe4b55;
    }

    .source a {
        color: #a2a2a2;
    }

    .source a:hover {
        color: #fe4b55;
    }

    .source {
        display: inline-block;
        text-align: left;
        /*width: 20%;*/
        padding: 3px;
        margin-right: 20px;
        border-radius: 3px;
    }

    .time {
        margin-right: 20px;
    }

    .story {
        display: block;
        width: 100%;
        margin-top: 15px;
        border-top: 1px solid #e2e2e2;
        color: #d2d2d2;
    }

    .story_title {
        width: 53%;
        display: inline-block;
    }

    .story_title em {
        font-style: normal;
    }

    @media (max-width: 748px) {
        .date_wrap {
            display: block;
            width: 100%;
        }

        .content_wrap {
            display: block;
            width: 100%;
            text-align: justify;
        }

        .title {
            width: 100%;
        }

        .story_title {
            width: auto;
            display: inline-block;
        }

        .source {
            width: auto;
        }

        .time {
            margin: 0 auto;
            display: inline-block;
        }
    }

</style>
@section('content')

    <section class="menu_title">
        <span>时间</span>
        <span>机构</span>
        <span>阶段</span>
        <span>金额</span>
        <span>投资机构</span>
    </section>
    {{--    {{Carbon\Carbon::today()->format("Y-m-d")}}--}}

    <section class="fund_list">
        @foreach($dailyfunds->items() as $K => $V)
            <div class="list @if($V->updated_at > Carbon\Carbon::today()->subHours(6)) today @endif">
                <div class="fund_basic">
                    <span>{{ Carbon\Carbon::parse($V->pub_date_f["date"])->toDateString()}}</span>
                <span>
                    <a href="{{$V->link}}" target="_blank">{{$V->company_df}}</a>
                </span>
                    <span>{{$V->round}}</span>
                    <span>{{$V->amount}}</span>
                <span style="height: 40px;overflow: hidden;padding-right: 5px">
                    @foreach($V->invest as $k=>$v)
                        <span>{{$v}}</span>
                    @endforeach
                </span>
                </div>
                @if(isset($V->fund_story)&&!empty($V->fund_story))
                    <div class="fund_story">
                        @foreach($V->fund_story as $k=>$v)
                            <span class="time">{{$v["time"]}}</span>
                            <span class="story_title"><a target="_blank"
                                                         href="{{$v["link"]}}">{!! $v["title"] !!}    </a></span>
                            <span class="source">{{$v["source"]}}</span>
                            <br/>
                        @endforeach
                    </div>
                @endif

            </div>
        @endforeach
    </section>
    {{--@foreach($dailyfunds->items() as $K => $V)--}}

    {{--<div class="list">--}}
    {{--@if($V->updated_at > Carbon\Carbon::today()->subHours(6))--}}
    {{--<label class="latest">today</label>--}}
    {{--@endif--}}
    {{--<div class="date_wrap">--}}
    {{--<span class="news_date">{{ Carbon\Carbon::parse($V->pub_date_f["date"])->toDateString()}}</span>--}}
    {{--</div>--}}

    {{--<div class="content_wrap">--}}
    {{--<span class="title">--}}
    {{--<a href="{{$V->link}}" target="_blank">{{$V->company_df}}</a>--}}
    {{--</span>--}}
    {{--</div>--}}
    {{--<div class="content_wrap">--}}
    {{--<span class="title">--}}
    {{--{{$V->round}}--}}
    {{--</span>--}}
    {{--</div>--}}
    {{--<div class="content_wrap">--}}
    {{--<span class="title">--}}
    {{--{{$V->amount}}--}}
    {{--</span>--}}
    {{--</div>--}}
    {{--<div class="content_wrap">--}}
    {{--<span class="title">--}}
    {{--@foreach($V->invest as $k=>$v)--}}
    {{--<span>{{$v}}</span>--}}
    {{--@endforeach--}}
    {{--</span>--}}
    {{--</div>--}}
    {{--<div class="story content_wrap">--}}
    {{--<span class="title">--}}
    {{--@if(isset($V->fund_story)&&!empty($V->fund_story))--}}
    {{--@foreach($V->fund_story as $k=>$v)--}}
    {{--<span class="time">{{$v["time"]}}</span>--}}
    {{--<span class="story_title"><a target="_blank"--}}
    {{--href="{{$v["link"]}}">{!! $v["title"] !!}    </a></span>--}}
    {{--<span class="source">{{$v["source"]}}</span>--}}
    {{--<br/>--}}
    {{--@endforeach--}}
    {{--@endif--}}
    {{--</span>--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--@endforeach--}}
    {{$dailyfunds->links()}}
    <script>
        $('body')
                .bind('mousewheel', function (event, delta) {
                    var dir = delta > 0 ? 'Up' : 'Down',
                            vel = Math.abs(delta);

                    var h = $(window).scrollTop();

                    var topmenu = $(".menu_title");
                    var topmenu_top = topmenu.offset().top;
                    console.log(h, topmenu_top);
                    if (h > 73) {
                        console.log(h);
                        topmenu.css({'top': 0});
                    }

                });


    </script>
@endsection