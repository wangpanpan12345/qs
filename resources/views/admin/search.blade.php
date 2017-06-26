@extends('admin')

@section('title', 'Page Title')

@section('sidebar')
    @parent

@endsection
<style>
    body {
        font-family: "Pingfang SC", "Microsoft YaHei", Helvetica, STHeiti, Verdana, Arial, Tahoma, sans-serif !important;
    }

    .list {
        margin: 20px 1%;
        border: 1px solid #e2e2e2;
        padding: 10px;
        width: 22%;
        float: left;
        height: 200px;
        overflow: hidden;
    }

    img {
        /*border-radius: 50%;*/
    }

    .title {
        display: block;
        position: relative;
        top: -50%;
        text-align: center;
    }

    .des {
        display: block;
        position: relative;
        top: -50%;
        font-family: Baskerville-eText, Baskerville, Garamond, serif !important;
        font-size: 14px;
        text-align: justify;
    }

    .avatar {
        display: block;
        width: 100%;
        height: 50px;
        text-align: center;
    }

    .wap_title {
        display: block;
        position: relative;
        margin: 12px 0;
        vertical-align: middle;
        width: 100%;
        text-align: center;
    }

    .wap_des {
        display: block;
        position: relative;
        margin: 10px 0;
        vertical-align: middle;
        width: 100%;
        text-align: center;
    }

    .wap_score {
        display: inline-block;
        position: relative;
        margin-left: 15px;
        vertical-align: middle;
        top: 50%;
    }

    /*.knowledge {*/
    /*padding: 0 3%;*/
    /*max-height: 600px;*/
    /*overflow: hidden;*/
    /*}*/

    .company_container {
        display: inline-block;
        width: 100%;
    }

    .search_container {
        display: flex;
    }

    .cell {
        flex: 1;
    }

    .s_content {
        height: auto;
    }

    .s_type {
        flex: 0 0 360px;
    }

    .s_total {
        font-size: 14px;
        margin-bottom: 30px;
    }

    .s_total em {
        color: #1E88E5;
        font-size: 14px;
        font-style: normal;
    }

    .s_item {
        border-top: 1px solid #e2e2e2;
        /*margin-bottom: 16px;*/
        width: 100%;
    }

    .s_item:last-child {
        border-bottom: 1px solid #e2e2e2;
        margin-bottom: 16px;
        /*padding-bottom: 16px;*/
    }

    .s_main_item {
        display: flex;
        margin-top: 20px;
        margin-bottom: 16px;
        margin-right: 30px;
    }

    .s_main_item img {
        width: 76px;
        max-height: 76px;
        background: #fff;

        /*border-radius: 10px;*/
    }

    img.avatar_svg {
        padding: 10px;
        background: #48b3f6;
    }

    .s_avatar {
        margin-right: 16px;
        display: flex;
        align-items: center;
    }

    .s_title_ex {

    }

    .s_title_ex span {
        display: block;
        color: #777;

    }

    .s_tag {
        display: flex;
        flex-wrap: wrap;
    }

    .s_tag i {
        display: inline-flex;
        width: 5px;
        height: 5px;
        background: #90caf9;
        border-radius: 50%;
        vertical-align: middle;
        margin-right: 8px;
    }

    .s_tag span {
        display: inline-flex;
        background: #90caf9;
        margin-bottom: 16px;
        margin-right: 16px;
        padding: 0px 10px;
        /*border-radius: 3px;*/
        height: 24px;
        line-height: 24px;
        color: #fff;

    }

    .type_list {
        margin-left: 60px;
        width: 300px;
    }

    .type_list ul, .type_list li {
        list-style: none;
        margin: 0;
        padding: 0;
    }

    .type_list ul {
        border: 1px solid #e2e2e2;
        border-bottom: none;
    }

    .type_list li:first-child {
        border-top: none;
    }

    .type_list li {
        height: 50px;
        background: #ebeff0;
        line-height: 50px;
        border-bottom: 1px solid #e2e2e2;
        cursor: pointer;
    }

    .result_label {
        margin-left: 16px;
    }

    .result_num {
        float: right;
        margin-right: 26px;
    }

    .s_title {
        font-size: 18px;
        font-weight: 500;
    }
    .s_title a{
        color: #1E88E5;
    }

    .s_des {
        line-height: 25px;
        max-height: 55px;
        overflow: hidden;
        font-weight: lighter;
        text-align: justify;
        max-width: 100%;
        display: block;
    }

    .type_list li:before {
        content: "";
        /*background: #00b3ee;*/
        width: 10px;
        height: 49px;
        display: inline-block;
        float: left;
    }

    .active:before {
        background: #1e88e5;
        -webkit-transition: all .3s ease-in .1s;
        transition: all .3s ease-in .1s;
        -moz-transition: all .3s ease-in .1s;
        -o-transition: all .3s ease-in .1s;

    }

    .active {
        color: #1e88e5;
        -webkit-transition: all .3s ease-in .1s;
        transition: all .3s ease-in .1s;
        -moz-transition: all .3s ease-in .1s;
        -o-transition: all .3s ease-in .1s;
    }

    .mv_active:before {
        background: #ebeff0;
        -webkit-transition: all .3s ease-in .1s;
        transition: all .3s ease-in .1s;
        -moz-transition: all .3s ease-in .1s;
        -o-transition: all .3s ease-in .1s;
    }

    .mv_active {
        color: #ebeff0;
        -webkit-transition: all .3s ease-in .1s;
        transition: all .3s ease-in .1s;
        -moz-transition: all .3s ease-in .1s;
        -o-transition: all .3s ease-in .1s;
    }

    .person, .info, .knowledge {
        display: none;
    }

    @media (max-width: 748px) {
        .wap_des {
            display: block;
            width: 100%;
            text-align: justify;
            margin: 0 auto;
        }

        .wap_title {
            width: 60%;
        }

        .wap_score {
            width: 100%;
            margin: 0 auto;
            text-align: right;
        }
    }
</style>
@section('content')
            {{--{{dd($company["hits"][0])}}--}}
    {{--<h3>搜索到{{$company["total"]}}个"{{$k}}"相关的公司,{{$person["total"]}}个相关的人,{{$news["total"]}}个相关的产业科研信息.</h3>--}}
    {{--@foreach($result as $rk=>$rv)--}}
    {{--<p><img src="{{$rv["avatar"]}}" width="auto" height="50px"/>--}}
    {{--<a href="{{ URL::to('/qs-admin/detail/'.$rv["_id"]) }}">{{$rv["name"]}}</a>-{{$rv["des"]}}--}}
    {{--</p>--}}
    {{--@endforeach--}}

    {{--@foreach($person as $pk=>$pv)--}}
    {{--<p><img src="{{$pv["avatar"]}}" width="auto" height="50px"/>--}}
    {{--<a href="{{ URL::to('/qs-admin/founder/'.$pv["_id"]) }}">{{$pv["name"]}}</a>-{{$pv["des"]}}--}}
    {{--</p>--}}
    {{--@endforeach--}}
    {{--{{dd($result->total)}}--}}
    {{--    {{dd($knowledge)}}--}}
    <?php $all = $item["total"] + $company["total"] + $person["total"] + $news["total"] + $knowledge["total"] ?>
    <div class="search_container">
        <section class="cell s_content org">
            <h3 class="s_total">您正在搜索"<em>{{$k}}</em>",共找到"<em>{{$all}}</em>"个结果,已为您过滤掉部分相关度较低结果.</h3>
            @foreach($item["hits"] as $K => $V)
                @if($V["_score"]>10)
                    <div class="s_item">
                        <div class="s_main_item">
                            <div class="s_avatar"><a target="_blank"
                                                     href="{{ URL::to("/item/".$V["_source"]["name"]) }}"
                                                     style="margin: 0;">
                                    <img
                                            src="{{$V["_source"]["avatar"] or "/img/org.svg"}}" <?php
                                            if (!isset($V["_source"]["avatar"]) || $V["_source"]["avatar"] == "")
                                                echo "class=avatar_svg"
                                            ?> >
                                </a></div>
                            <div class="s_title_ex">
                       <span class="s_title"><a target="_blank" href="{{ URL::to("/item/".$V["_source"]["name"]) }}"
                                                style="margin: 0;">{{$V["_source"]["name"]}}</a></span>
                                <span class="s_des">{!!$V["_source"]["des"] or ""!!}</span>
                            </div>
                        </div>
                        <div class="s_tag">
                            @if(isset($V["_source"]["tags"])&&!empty($V["_source"]["tags"]))
                                @foreach($V["_source"]["tags"] as $tk=>$tv)
                                    <div><span>{{$tv}}</span></div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                @endif
            @endforeach
            @foreach($company["hits"] as $K => $V)
                <div class="s_item">
                    <div class="s_main_item">
                        <div class="s_avatar">
                            <a target="_blank" href="{{ URL::to('/qs-admin/detail/'.$V["_id"]) }}"
                               style="margin: 0;"><img
                                        src="{{$V["_source"]["avatar"] or "/img/org.svg"}}" <?php
                                        if (!isset($V["_source"]["avatar"]) || $V["_source"]["avatar"] == "")
                                            echo "class=avatar_svg"
                                        ?> >
                            </a>
                        </div>
                        <div class="s_title_ex">
                       <span class="s_title"><a target="_blank" href="{{ URL::to('/qs-admin/detail/'.$V["_id"]) }}"
                                                style="margin: 0;">{{$V["_source"]["name"]}}</a></span>
                            <span class="s_des">{{$V["_source"]["des"] or ""}}</span>
                        </div>
                    </div>
                    <div class="s_tag">
                        @if(isset($V["_source"]["tags"])&&!empty($V["_source"]["tags"]))
                            @foreach($V["_source"]["tags"] as $tk=>$tv)
                                <div><span>{{$tv}}</span></div>
                            @endforeach
                        @endif
                    </div>
                </div>
            @endforeach
        </section>
        <!-- person-->
        <section class="cell s_content person">
            <h3 class="s_total">你正在搜索"<em>{{$k}}</em>",共找到"<em>{{$all}}</em>"个结果</h3>
            @foreach($person["hits"] as $K => $V)
                <div class="s_item" {{"socre=".$V["_score"]}}>
                    <div class="s_main_item">
                        <div class="s_avatar"><img
                                    @if(!isset($V["_source"]["avatar"]) || $V["_source"]["avatar"] == "")
                                    src="/img/person.svg" class="avatar_svg"
                                    @else
                                    src="{{$V["_source"]["avatar"]}}"
                                    @endif ></div>
                        <div class="s_title_ex">
                       <span class="s_title"><a target="_blank" href="{{ URL::to('/qs-admin/founder/'.$V["_id"]) }}"
                                                style="margin: 0;">{{$V["_source"]["name"]}}</a></span>
                            <span class="s_des">{!!$V["_source"]["des"] or ""!!}</span>
                        </div>
                    </div>
                    <div class="s_tag">
                        @if(isset($V["_source"]["tags"])&&!empty($V["_source"]["tags"]))
                            @foreach($V["_source"]["tags"] as $tk=>$tv)
                                <div><span>{{$tv}}</span></div>
                            @endforeach
                        @endif
                    </div>
                </div>
            @endforeach
        </section>
        <!-- info-->
        <section class="cell s_content info">
            <h3 class="s_total">你正在搜索"<em>{{$k}}</em>",共找到"<em>{{$all}}</em>"个结果</h3>
            @foreach($news["hits"] as $K => $V)
                <div class="s_item">
                    <div class="s_main_item">
                        <div class="s_avatar"><img class="avatar_svg" src="{{"/img/info.svg"}}"></div>
                        <div class="s_title_ex">
                       <span class="s_title"><a target="_blank" href="{{ URL::to($V["_source"]["link"]) }}"
                                                style="margin: 0;">{{$V["_source"]["title"]}}</a></span>
                            <span class="s_des">{!!$V["_source"]["excerpt"] or ""!!}</span>
                        </div>
                    </div>
                    <div class="s_tag">
                        @if(isset($V["_source"]["tags"])&&!empty($V["_source"]["tags"]))
                            @foreach($V["_source"]["tags"] as $tk=>$tv)
                                <div><span>{{$tv}}</span></div>
                            @endforeach
                        @endif
                    </div>
                </div>
            @endforeach
        </section>
        <section class="cell s_content knowledge">
            <h3 class="s_total">你正在搜索"<em>{{$k}}</em>",共找到"<em>{{$all}}</em>"个结果</h3>
            @foreach($knowledge["hits"] as $K => $V)
                <div class="s_item">
                    <div class="s_main_item">
                        <div class="s_avatar"><img class="avatar_svg" src="{{"/img/knowledge.svg"}}"></div>
                        <div class="s_title_ex">
                       <span class="s_title"><a target="_blank"
                                                href="{{ URL::to("/timeline/tag/".$V["_source"]["tags"]) }}"
                                                style="margin: 0;">{{$V["_source"]["tags"]}}
                               的{{$V["_source"]["type"]}}</a></span>
                            <span class="s_des" style="width: 40vw;">{!!$V["_source"]["content"]!!}</span>
                        </div>
                    </div>
                    <div class="s_tag">
                        <div><span>{{$V["_source"]["tags"]}}</span></div>
                    </div>
                </div>
            @endforeach
        </section>
        <section class="cell s_type">
            <div class="type_list">
                <ul>
                    <li id="person"><span class="result_label">人物</span><span
                                class="result_num">{{$person["total"]}}</span></li>
                    <li id="org" class="active"><span class="result_label">机构</span><span
                                class="result_num">{{$company["total"]+$item["total"]}}</span></li>
                    <li id="info"><span class="result_label">资讯</span><span class="result_num">{{$news["total"]}}</span>
                    </li>
                    <li id="knowledge"><span class="result_label">知识</span><span
                                class="result_num">{{$knowledge["total"]}}</span></li>
                </ul>
            </div>
        </section>
    </div>




    {{--@foreach($knowledge["hits"] as $K => $V)--}}
    {{--@if($V["_score"]>100)--}}
    {{--<div class="knowledge">--}}
    {{--{!!$V["_source"]["content"]!!}--}}
    {{--</div>--}}
    {{--@endif--}}
    {{--@endforeach--}}
    {{--@foreach($news["hits"] as $K => $V)--}}
    {{--<div class="list">--}}
    {{--<span class="avatar"><img src="{{$V["avatar"] or ""}}" width="50px" height="50px"/></span>--}}

    {{--<div class="wap_title">--}}
    {{--<span class="title">{{\Carbon\Carbon::parse($V["_source"]["created_at"])}}</span>--}}
    {{--</div>--}}
    {{--<div class="wap_des">--}}
    {{--<span class="des"><a href="{{ URL::to($V["_source"]["link"]) }}"--}}
    {{--style="margin: 0;">{{$V["_source"]["excerpt"] or ""}}--}}
    {{--{{$V["_source"]["title"]}}</a></span>--}}
    {{--</div>--}}
    {{--<div class="wap_des">--}}
    {{--<span class="des">--}}
    {{--@if(!empty($V["_source"]["tags"])&&isset($V["_source"]["tags"]))--}}
    {{--@foreach($V["_source"]["tags"] as $tv=>$tk)--}}
    {{--<a href="">{{$tk}}</a>--}}
    {{--@endforeach--}}
    {{--@else--}}
    {{--<a href="">{{"资讯"}}</a>--}}
    {{--@endif--}}
    {{--</span>--}}
    {{--</div>--}}


    {{--<div class="wap_score">--}}
    {{--<span style="display: inline-block;width: 120px;height:22px;border: 1px solid #000;float: right">--}}

    {{--</span>--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--@endforeach--}}
    {{--    {{dd($item["hits"])}}--}}
    {{--@foreach($item["hits"] as $K => $V)--}}
    {{--@if($V["_score"]>10)--}}
    {{--<div class="list">--}}
    {{--<div class="wap_title">--}}
    {{--<span class="title">{{\Carbon\Carbon::parse($V["_source"]["created_at"])}}</span>--}}
    {{--</div>--}}
    {{--<div class="wap_des">--}}
    {{--<span class="des"><a href="{{ URL::to("/item/".$V["_source"]["name"]) }}"--}}
    {{--style="margin: 0;">{{$V["_source"]["name"]}}</a></span>--}}
    {{--</div>--}}
    {{--<div class="wap_des">--}}
    {{--<span class="des">--}}
    {{--@if(!empty($V["_source"]["tags"])&&isset($V["_source"]["tags"]))--}}
    {{--@foreach($V["_source"]["tags"] as $tv=>$tk)--}}
    {{--<a href="">{{$tk}}</a>--}}
    {{--@endforeach--}}
    {{--@endif--}}
    {{--</span>--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--@endif--}}
    {{--@endforeach--}}
    {{--<div class="company_container">--}}
    {{--@foreach($company["hits"] as $K => $V)--}}

    {{--<div class="list">--}}
    {{--{{$V["_score"]}}--}}
    {{--<span class="avatar"><img src="{{$V["_source"]["avatar"] or ""}}" width="50px" height="50px"/></span>--}}

    {{--<div class="wap_title">--}}
    {{--<span class="title"><a href="{{ URL::to('/qs-admin/detail/'.$V["_id"]) }}"--}}
    {{--style="margin: 0;">{{$V["_source"]["name"]}}</a></span>--}}
    {{--</div>--}}
    {{--<div class="wap_des">--}}
    {{--<span class="des">{{$V["_source"]["des"] or ""}}</span>--}}
    {{--</div>--}}
    {{--</div>--}}

    {{--@endforeach--}}
    {{--</div>--}}

    {{--@foreach($person["hits"] as $K => $V)--}}
    {{--@if($V["_score"]>99)--}}
    {{--<div class="list">--}}
    {{--<span class="avatar"><img src="{{$V["_source"]["avatar"] or ""}}" width="50px" height="50px"/></span>--}}

    {{--<div class="wap_title">--}}
    {{--<span class="title"><a href="{{ URL::to('/qs-admin/founder/'.$V["_id"]) }}"--}}
    {{--style="margin: 0;">{{$V["_source"]["name"]}}</a></span>--}}
    {{--</div>--}}
    {{--<div class="wap_des">--}}
    {{--<span class="des">{!!isset($V["_source"]["des"])?$V["_source"]["des"]:""!!}</span>--}}
    {{--</div>--}}

    {{--</div>--}}
    {{--@endif--}}
    {{--@endforeach--}}
    {{--{{dd()}}--}}


    {{--@foreach($funds["hits"] as $K => $V)--}}
    {{--@if($V["_score"]>50)--}}
    {{--<div class="list">--}}
    {{--<span class="avatar"><img src="{{$V["_source"]["avatar"] or ""}}" width="50px" height="50px"/></span>--}}

    {{--<div class="wap_title">--}}
    {{--<span class="title"><a href="{{ URL::to('#') }}"--}}
    {{--style="margin: 0;">{{$V["_source"]["company_df"]}}</a></span>--}}
    {{--</div>--}}
    {{--<div class="wap_des">--}}
    {{--<span class="des">{{explode(" ",$V["_source"]["company_df"])[0]}}--}}
    {{--完成{{$V["_source"]["round"]}}{{$V["_source"]["amount"]}}融资</span>--}}
    {{--</div>--}}

    {{--</div>--}}
    {{--@endif--}}
    {{--@endforeach--}}

    {{--    {{dd($result)}}--}}
    <script src="//cdn.bootcss.com/jquery/3.1.1/jquery.min.js"></script>
    <script>
        $(function () {
            $("#person").click(function () {
                $(".s_content").hide();
                $(".person").show();
                $(".type_list li").addClass("mv_active");
                $(".type_list li").removeClass("active");
                $(".type_list li").removeClass("mv_active");
                $(this).addClass("active");
            });
            $("#org").click(function () {
                $(".s_content").hide();
                $(".org").show();
                $(".type_list li").addClass("mv_active");
                $(".type_list li").removeClass("active");
                $(".type_list li").removeClass("mv_active");
                $(this).addClass("active");
            });
            $("#info").click(function () {
                $(".s_content").hide();
                $(".info").show();
                $(".type_list li").addClass("mv_active");
                $(".type_list li").removeClass("active");
                $(".type_list li").removeClass("mv_active");
                $(this).addClass("active");
            });
            $("#knowledge").click(function () {
                $(".s_content").hide();
                $(".knowledge").show();
                $(".type_list li").addClass("mv_active");
                $(".type_list li").removeClass("active");
                $(".type_list li").removeClass("mv_active");
                $(this).addClass("active");
            });
        });
    </script>

@endsection