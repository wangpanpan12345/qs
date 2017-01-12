@extends('layouts.app')

{{--@section('title', 'Page Title')--}}

{{--@section('sidebar')--}}
{{--@parent--}}

{{--@endsection--}}

@section('content')
    {{dd($invest)}}
    <p style="text-align: center;"><img width="80px" height="80px" src="{{$invest->avatar}}"/></p>
    <h2>{{$invest->name}}</h2>



    <p>地址:{{$invest->location}}</p>
    <p>时间:{{$invest->time}}</p>
    <p>网站:<a href="{{$invest->website}}">{{$invest->website}}</a></p>
    <p>标签:
        @if(isset($invest->tags)&&!empty($invest->tags))
            @foreach($invest->tags as $k=>$v)
                {{$v}}
            @endforeach
        @endif
    </p>
    <h3>融资详情</h3>
    @if(isset($invest->raiseFunds)&&!empty($invest->raiseFunds))

        @foreach($invest->raiseFunds as $rk =>$rv)
            {{--<p>轮次:{{$rv["phace"]}}</p>--}}
            {{--<p>时间:{{$rv["times"]}}</p>--}}
            {{--<p>金额:{{$rv["amount"]}}</p>--}}

            {{--@foreach($rv["organizations"] as $ok => $ov)--}}
            {{--<p>投资机构:{{$ov["name"]}}</p>--}}
            {{--@endforeach--}}
        @endforeach
    @endif
    <p>{!!$invest->detail!!}</p>

@endsection