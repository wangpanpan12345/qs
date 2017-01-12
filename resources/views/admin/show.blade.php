@extends('admin')

@section('title', 'qisu')

@section('sidebar')
    @parent
@endsection
<script src="//cdn.bootcss.com/jquery/3.1.1/jquery.min.js"></script>
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
    @media (max-width: 748px){
        .wap_des{
            display: block;
            width: 100%;
            text-align: justify;
            margin: 0 auto;
        }
        .wap_title{
            width: 60%;
        }
        .wap_score{
            width: 100%;
            margin: 0 auto;
            text-align: right;
        }
    }
</style>
@section('content')
    {{--<h2>奇速公司数据列表</h2>--}}
    {{--        {{dd($cs)}}--}}
    @foreach($cs->items() as $K => $V)

        <div class="list">
            <span class="avatar"><img src="{{$V["avatar"] or ""}}" width="50px" height="50px"/></span>

            <div class="wap_title">
                <span class="title"><a href="{{ URL::to('/qs-admin/detail/'.$V["_id"]) }}"
                                       style="margin: 0;">{{$V["name"]}}</a></span>
            </div>
            <div class="wap_des">
                <span class="des">{{$V["des"]}}</span>
            </div>
            <?php $width = isset($V["complete_score"])?$V["complete_score"]:0 ?>
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
    {{--<a href="{{$cs->previousPageUrl()}}">前一页</a>--}}
    {{--<a href="{{$cs->nextPageUrl()}}">后一页</a>--}}
    {{$cs->links()}}
@endsection