@extends('admin')

@section('title', '每日投融资')

@section('sidebar')
    @parent
@endsection

{{--<link href="https://fonts.css.network/css?family=Raleway:100,600" rel="stylesheet" type="text/css">--}}
<script src="//cdn.bootcss.com/jquery/3.1.1/jquery.min.js"></script>
<style>
    body {
        font-family: Raleway, "Pingfang SC", "Microsoft YaHei", Helvetica, STHeiti, Verdana, Arial, Tahoma, sans-serif !important;
    }

    .list {
        position: relative;
        margin: 20px 0;
        border: 1px solid #e2e2e2;
        padding: 10px;
    }

    .latest {
        position: absolute;
        top: -18px;
        display: block;
        z-index: 2;
        float: left;
        border: 1px solid #e2e2e2;
        font-size: 12px;
        left: -2px;
        color: #fff;
        background: #fe4b55;
        height: 17px;
        line-height: 13px;
        padding: 1px 15px;
    }

    .date_wrap {

        display: inline-block;
        position: relative;
        vertical-align: middle;
        top: 50%;
        width: 20%;

    }

    .news_date {
        display: inline-block;
        position: relative;
        top: -50%;
    }

    .content_wrap {
        display: inline-block;
        width: 15%;
        vertical-align: middle;
        top: 50%;
    }

    .title {
        display: block;
        /*width: 80%;*/
        line-height: 28px;
        color: #a2a2a2;
        text-align: justify;
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
        display: block;
        text-align: right;
        color: #d2d2d2;
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
    }

</style>
@section('content')
    {{--    {{Carbon\Carbon::today()->format("Y-m-d")}}--}}
    @foreach($dailyfunds->items() as $K => $V)

        <div class="list">
            @if($V->updated_at > Carbon\Carbon::today()->subHours(6))
                <label class="latest">today</label>
            @endif
            <div class="date_wrap">
                <span class="news_date">{{ Carbon\Carbon::parse($V->pub_date_f["date"])->toDateString()}}</span>
            </div>

            <div class="content_wrap">
                <span class="title">
                    <a href="{{$V->link}}" target="_blank">{{$V->company_df}}</a>
                </span>
            </div>
            <div class="content_wrap">
                <span class="title">
                    {{$V->round}}
                </span>
            </div>
            <div class="content_wrap">
                <span class="title">
                    {{$V->amount}}
                </span>
            </div>
            <div class="content_wrap">
                <span class="title">
                   @foreach($V->invest as $k=>$v)
                        {{$v}}<br/>
                    @endforeach
                </span>
            </div>
        </div>
    @endforeach
    {{$dailyfunds->links()}}
@endsection