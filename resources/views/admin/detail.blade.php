@extends('layouts.app')

{{--@section('title', 'Page Title')--}}

{{--@section('sidebar')--}}
{{--@parent--}}

{{--@endsection--}}
<style>
    body {
        font-family: "Pingfang SC", "Microsoft YaHei", Helvetica, STHeiti, Verdana, Arial, Tahoma, sans-serif !important;
    }

    h3 {
        height: 50px;
    }

    body p {
        margin-left: 5px;
    }

    table {
        border: 1px #b2b2b2 solid;
        text-align: center;
    }

    thead tr {
        height: 30px;
        border-bottom: 1px #b2b2b2 solid;
    }

    thead th {
        text-align: center;
        border-right: 1px #b2b2b2 solid;
    }

    tbody tr {
        height: 40px;
    }

    tbody tr:nth-child(2n+2) {
        background: #e9e9e9;
    }

    tbody td {
        border-right: 1px #b2b2b2 solid;
    }

    .overview {
        padding: 20px;
        border: 1px solid #e2e2e2;
        background: #fff;
    }

    .o_label {
        display: inline-block;
        width: 100px;
    }

    .team_wap {
        height: 100%;
    }

    .detail_wap {
        padding: 15px;
        background: #fff;
        border: 1px solid #e2e2e2;
        line-height: 28px;
        text-align: justify;
        text-indent: 2em;
    }

    .news_date {
        display: inline-block;
        width: 20%;

    }

    .news_title {
        display: inline-block;
        width: 75%;
    }

</style>
@section('content')
{{--    {{dd($detail->name)}}--}}
    <p style="text-align: center;" xmlns="http://www.w3.org/1999/html"><img width="auto" height="80px"
                                                                            src="{{isset($detail->avatar)?$detail->avatar:"#"}}"/></p>
    <h2 style="text-align: center"> {{$detail->name}}</h2>
{{--    {{dd($detail)}}--}}
    <div class="overview">
        @if(!empty($detail->founder)&&$detail->founder!="")
            <p><b class="o_label">创始人:</b>
                @if(is_array($detail->founder))
                    @foreach($detail->founder as $k=>$v)
                        <a href="{{'/qs-admin/founder/#'}}">{{$v}}</a>
                    @endforeach
                @else
                    <a href="{{'/qs-admin/founder/'.$detail->founder_id}}">{{$detail->founder}}</a>
                @endif
            </p>
        @endif

        <p><b class="o_label">简述:</b> <span>{{$detail->des}}</span></p>

        <p><b class="o_label">行业:</b> {{(!empty($detail->industry))?$detail->industry:"Health Care"}}</p>

        <p><b class="o_label">地址:</b> {{$detail->location}}</p>

        <p><b class="o_label">时间:</b> {{$detail->time}}</p>

        <p><b class="o_label">联系方式:</b> {{$detail->contactInfo}}</p>

        <p><b class="o_label">Office:</b>
            @if(isset($detail->offices)&&!empty($detail->offices))
                @foreach($detail->offices as $k=>$v)
                    <b>{{$v["name"]}}:</b>&nbsp;&nbsp;{{$v["address"]}}
                @endforeach
            @endif
        </p>
        {{--<p>融资:{{$detail->round}}</p>--}}
        <p><b class="o_label">网站:</b> <a href="{{$detail->website}}" target="_blank">{{$detail->website}}</a></p>

        <p><b class="o_label">规模: </b>{{explode("|",$detail->scale)[0]}}</p>

        <p><b class="o_label">标签:</b>
            @foreach($detail->tags as $k=>$v)
                <a href="{{'/qs-admin/tag/'.$v}}">{{$v}},</a>
            @endforeach
        </p>
    </div>

    @if(isset($detail->detail)&&!empty($detail->detail))
        <h3>公司详情</h3>
        <div class="detail_wap">
            <p style="text-align: justify">{!!$detail->detail!!}</p>
        </div>

    @endif

    @if(isset($detail->raiseFunds)&&!empty($detail->raiseFunds))
        <div style="margin-bottom: 20px;">
            <h3>融资详情</h3>
            <table style="margin: 0 auto;width: 100%;">
                <thead>
                <tr>
                    <th>时间</th>
                    <th>轮次</th>
                    <th>金额</th>
                    <th>投资机构</th>
                </tr>
                </thead>
                <tbody>
                @foreach($detail->raiseFunds as $rk =>$rv)
                    <tr>
                        <td>{{isset($rv["times"])?$rv["times"]:""}}</td>
                        <td>{{$rv["phase"]}}</td>
                        <td>
                            {{$rv["amount_o"]}}
{{--                            {{(is_float($rv["amount"])&&strlen($rv["amount"])>6)?$rv["amount"]*6.7."万元":"$ " .$rv["amount"]."万"}}</td>--}}
                        <td>@foreach($rv["organizations"] as $ok => $ov)
                                @if(is_array($ov))
                                    {{--{{dd($ov)}}--}}
                                    <p>
                                        {{--<a href="{{$ov["link"]}}"></a>--}}
                                        {{$ov["name"]}}</p>

                                @endif
                            @endforeach
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    @endif


    @if(isset($detail->investments)&&!empty($detail->investments))

        <div style="margin-bottom: 20px;">
            <h3>投资</h3>
            <table style="margin: 0 auto;width: 100%;">
                <thead>
                <tr>
                    <th>时间</th>
                    <th>轮次</th>
                    <th>公司</th>
                </tr>
                </thead>
                <tbody>
                @foreach($detail->investments as $rk =>$rv)
                    @if($rv["name"]=="")
                    @else
                        <tr>
                            <td>{{$rv["date"]}}</td>
                            <td>{{$rv["round"]}}</td>
                            <td><a href="#">{{$rv["name"]}}</a></td>
                        </tr>
                    @endif
                @endforeach
                </tbody>
            </table>
        </div>
    @endif


    @if(isset($detail->acquisitions)&&!empty($detail->acquisitions))
        <div style="margin-bottom: 20px;">
            <h3>并购</h3>
            <table style="margin: 0 auto;width: 100%;">
                <thead>
                <tr>
                    <th>时间</th>
                    <th>公司</th>
                    <th>数额</th>
                </tr>
                </thead>
                <tbody>
                @foreach($detail->acquisitions as $ak =>$av)
                    <tr>
                        <td>{{$av["date"]}}</td>
                        <td><a href="#">{{$av["name"]}}</a></td>
                        <td>{{$av["amount"]}}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    @endif

    @if(isset($detail->products)&&!empty($detail->products))
        <div style="width: 100%;float: left;">
            <h3>产品</h3>
            @foreach($detail->products as $ak =>$av)
                <p>
                <div class="news_date">
                    <a href="{{$av["link"]}}">{{$av["name"]}}</a>
                </div>
                <div class="news_title">
                    {{$av["desc"]}}
                </div>
                </p>
            @endforeach

        </div>

    @endif

    @if(isset($detail->members)&&!empty($detail->members))
        <div style="width: 100%;float: left;">
            <h3>团队</h3>
            @foreach($detail->members as $mk =>$mv)
                <div style="display: inline-block;width: 25%;height: 100px;float: left;margin-bottom: 20px">
                    <span>
                        <img src="{{isset($mv["avatar"])?$mv["avatar"]:"http://static-site.geekheal.net/qisu/image/avatar_default.jpg"}}"
                             style="width: 80px;height:auto;border-radius: 50%;"/>
                    </span>
                    <span style="display: inline-block;width: 70%;float:right;">
                        <a href="{{"#"}}">{{$mv["name"]}}</a><br/>{{$mv["title"]}}
                    </span>
                </div>
            @endforeach
        </div>
    @endif

    @if(isset($detail->news)&&!empty($detail->news))
        <div style="width: 100%;float: left;">
            <h3>新闻</h3>
            @foreach($detail->news as $ak =>$av)
                <p>
                <div class="news_date">
                    {{$av["date"]}}
                </div>
                <div class="news_title">
                    {{$av["source"]}}-<a href="{{$av["link"]}}">{{$av["title"]}}</a></p>
                </div>

            @endforeach

        </div>

    @endif


@endsection