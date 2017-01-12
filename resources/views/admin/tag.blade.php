@extends('admin')

@section('title', '奇速公司数据')

@section('sidebar')
    @parent

@endsection

@section('content')
    <h2>奇速公司数据列表</h2>
{{--    {{dd($cs)}}--}}
    @foreach($c_tag->items() as $K => $V)

        <div class="list">
            <span class="avatar"><img src="{{$V["avatar"] or ""}}" width="50px" height="50px"/></span>

            <div class="wap_title">
                <span class="title"><a href="{{ URL::to('/qs-admin/detail/'.$V["_id"]) }}"
                                       style="margin: 0;">{{$V["name"]}}</a></span>
            </div>
            <div class="wap_des">
                <span class="des">{{$V["des"]}}</span>
            </div>
            <?php $width = $V["complete_score"] or 0 ?>
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
    {{$c_tag->links()}}
@endsection