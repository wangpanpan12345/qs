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
        margin: 20px 0;
        border: 1px solid #e2e2e2;
        padding: 10px;
    }

    img {
        border-radius: 50%;
    }

    .title {
        display: inline-block;
        position: relative;
        top: -50%;
    }

    .des {
        display: inline-block;
        position: relative;
        top: -50%;
        font-family: Baskerville-eText, Baskerville, Garamond, serif !important;
        font-size: 14px;
        text-align: justify;
    }

    .avatar {
        display: inline-block;
        width: 50px;
        height: 50px;
    }

    .wap_title {
        display: inline-block;
        position: relative;
        margin-left: 15px;
        vertical-align: middle;
        top: 50%;
        width: 20%;
    }

    .wap_des {
        display: inline-block;
        position: relative;
        margin-left: 15px;
        vertical-align: middle;
        top: 50%;
        width: 55%;
    }

    .wap_score {
        display: inline-block;
        position: relative;
        margin-left: 15px;
        vertical-align: middle;
        top: 50%;
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
{{--    {{dd($company)}}--}}
    <h3>搜索到{{$company["total"]}}个"{{$k}}"相关的公司,{{$person["total"]}}个相关的人,{{$news["total"]}}个相关的产业科研信息.</h3>
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
    @foreach($company["hits"] as $K => $V)
        <div class="list">
            <span class="avatar"><img src="{{$V["_source"]["avatar"] or ""}}" width="50px" height="50px"/></span>

            <div class="wap_title">
                <span class="title"><a href="{{ URL::to('/qs-admin/detail/'.$V["_id"]) }}"
                                       style="margin: 0;">{{$V["_source"]["name"]}}</a></span>
            </div>
            <div class="wap_des">
                <span class="des">{{$V["_source"]["des"] or ""}}</span>
            </div>
            <?php $width = isset($V["_source"]["complete_score"]) ? $V["_source"]["complete_score"] : 0 ?>
            @if((int)$width>80 || (int)$width==80)
                <?php $color = '#3AC815' ?>
            @elseif((int)$width>60 || (int)$width==60)
                <?php $color = '#FCFE1B' ?>
            @else
                <?php $color = '#FE4B55' ?>
            @endif
            <div class="wap_score">
            <span style="display: inline-block;width: 120px;height:22px;border: 1px solid #000;float: right">

                <i style="display:inline-block;height: 20px;background:{{$color}};width:{{$width}}px"></i>
            </span>
            </div>
        </div>

    @endforeach
{{--{{dd()}}--}}
    @foreach($news["hits"] as $K => $V)
        <div class="list">
            {{--<span class="avatar"><img src="{{$V["avatar"] or ""}}" width="50px" height="50px"/></span>--}}

            <div class="wap_title">
                <span class="title">{{$V["_source"]["created_at"]}}</span>
            </div>
            <div class="wap_des">
                <span class="des"><a href="{{ URL::to($V["_source"]["link"]) }}"
                                     style="margin: 0;">{{$V["_source"]["excerpt"] or ""}}
                        -{{$V["_source"]["title"]}}</a></span>
            </div>

            {{--<div class="wap_score">--}}
            {{--<span style="display: inline-block;width: 120px;height:22px;border: 1px solid #000;float: right">--}}

            {{--</span>--}}
            {{--</div>--}}
        </div>
    @endforeach

    @foreach($person["hits"] as $K => $V)
        <div class="list">
            <span class="avatar"><img src="{{$V["_source"]["avatar"] or ""}}" width="50px" height="50px"/></span>

            <div class="wap_title">
                <span class="title"><a href="{{ URL::to('/qs-admin/founder/'.$V["_id"]) }}"
                                       style="margin: 0;">{{$V["_source"]["name"]}}</a></span>
            </div>
            <div class="wap_des">
                <span class="des">{!!isset($V["_source"]["des"])?str_limit($V["_source"]["des"],100,'...'):""!!}</span>
            </div>

            <div class="wap_score">
            <span style="display: inline-block;width: 120px;height:22px;border: 1px solid #000;float: right">

            </span>
            </div>
        </div>
    @endforeach

    {{--    {{dd($result)}}--}}

@endsection