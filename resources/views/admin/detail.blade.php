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

    h1 a {
        border: 2px solid;
        padding: 5px 10px;
        font-size: 16px;
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

    .menu_title {
        display: inline-block;
        color: #48b3f6;
        font-size: 16px;
        margin: 30px 0 10px 0;
    }

    .list {
        position: relative;
        margin: 20px 0;
        border: 1px solid #e2e2e2;
        padding: 10px;
        box-shadow: 0 3px 0 #d7e4ed;
        border-radius: .25em;
        background: #fff;
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
        width: 78%;
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

    .source {
        display: block;
        text-align: right;
        color: #d2d2d2;
    }

    .source a {
        color: #323232;
    }

    .source {
        position: relative;
        display: block;
        background: #89cafc;
        width: 100px;
        height: 30px;
        line-height: 30px;
        color: #fff;
        font-size: 16px;
        text-align: center;
        font-weight: 600;
        border-radius: 5px;
        left: 100%;
        margin-left: -100px;
        margin-top: 10px;
        cursor: pointer;
    }

    .source_i {
        display: block;
        width: 100%;
        text-align: right;
        color: #e2e2e2;

    }
    .key_edit{
        float: right;
        color: #fe4b55;
        border: 2px solid #fe4b55;
        padding: 3px 5px;
        cursor: pointer;
        border-radius: 5px;
    }

</style>
@section('content')
    <h1><a href="{{env("EDIT_URL","https://admin.geekheal.net")}}/edit/company/{{$detail->id}}"
           target="_blank">编辑该词条-{{$detail->qi_score}}</a></h1>
    @if(count($merge)>0) Merged by : <a href="{{Url('/qs-admin/detail/'.$merge->_id)}}">{{$merge->name}}</a> @endif
    <p style="text-align: center;" xmlns="http://www.w3.org/1999/html"><img width="auto" height="80px"
                                                                            src="{{isset($detail->avatar)?$detail->avatar:"#"}}"/>
    </p>
    <h2 style="text-align: center"> {{$detail->name}}{{isset($detail->status)&&$detail->status=="已关闭"?"(已关闭)":""}}</h2>
    {{--{{dd(is_array($detail->founder))}}--}}
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

        {{--<p><b class="o_label">行业:</b> {{(!empty($detail->industry))?$detail->industry:"Health Care"}}</p>--}}

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
            @if(isset($detail->tags)&&!empty($detail->tags))
                @foreach($detail->tags as $k=>$v)
                    <a href="{{'/qs-admin/tag/'.$v}}">{{$v}},</a>
                @endforeach
            @endif
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
                        @if($rv["amount_o"]=="$0M"||$rv["amount_o"]=="$0K")
                            {{"未透露"}}
                        @else
                            {{$rv["amount_o"]}}
                        @endif

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
                        <td><a href="{{route('company',["name"=>$av["name"]])}}">{{$av["name"]}}</a></td>
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
                    <a href="{{isset($av["link"])?$av["link"]:"#"}}">{{$av["name"]}}</a>
                </div>
                <div class="news_title">
                    {{$av["desc"]}}
                </div>
                </p>
            @endforeach

        </div>

    @endif
    {{--    {{dd($detail->founders())}}--}}
    @if(!empty($detail->founders()))
        <div style="width: 100%;float: left;">
            <h3>团队</h3>
            <?php $member_title = $detail->members; ?>
            @foreach($detail->founders() as $mk =>$mv)
                <div style="display: inline-block;width: 25%;height: 100px;float: left;margin-bottom: 20px">
                    <span>
                        <img src="{{isset($mv["avatar"])?$mv["avatar"]:"http://static-site.geekheal.net/qisu/image/avatar_default.jpg"}}"
                             style="width: 80px;height:auto;border-radius: 50%;"/>
                    </span>
                    <span style="display: inline-block;width: 70%;float:right;">
                        <a href="{{"/qs-admin/founder/".$mv["_id"]}}">{{$mv["name"]}}</a><br/>{{$mv["title"]}}
                        @foreach($member_title as $K=>$V)
                            @if(isset($V["founder_id"])&&$V["founder_id"]==$mv["_id"])
                                {{$V["title"]}}
                                <?php unset($member_title[$K]);?>
                            @endif
                        @endforeach
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
    @if(!empty($timeline)&&count($timeline)>0)
        <span class="menu_title">Timeline</span>

        @foreach($timeline as $t=>$V)
            <div class="list">

                <div class="date_wrap">
                    <span class="">{{$V->created_at}}</span>
                </div>

                <div class="content_wrap">
                    <span class="">{{$V->excerpt}}</span>
                <span class="title">Origin:<a href="{{$V->link}}" target="_blank">{{$V->title}}</a>
                    <span style="float: right;color: #d2d2d2">From:{{$V->source}}</span>
                </span>
                <span class="source_i">
                    @if(!empty($V->tags))Tags:
                    @foreach($V->tags as $k=>$v)
                        <a href="/timeline/tag/{{$v}}">{{$v}}</a>
                    @endforeach
                    @endif
                </span>
                    <span class="source_i">Editor:{{$V->users["name"]}}</span>
                    <div class="key_edit"><span>编辑</span></div>
                    {{--<span class="source">Editor:{{$V->companies["name"]}}</span>--}}
                </div>


            </div>
        @endforeach
    @endif
    <script src="//cdn.bootcss.com/jquery/3.1.1/jquery.min.js"></script>
    <script>
        $(function(){
            $(".key_edit").click(function () {
                var title = $(this).parent(".content_wrap").find(".title a")[0].innerHTML;
                window.location.href = encodeURI("/dailynews/key?key=" + title);
            });
        })

    </script>
@endsection