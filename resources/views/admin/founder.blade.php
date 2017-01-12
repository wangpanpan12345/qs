@extends('admin')

@section('title', 'Page Title')

@section('sidebar')
    @parent

@endsection

@section('content')
    <p style="text-align: center;"><img width="80px" height="80px" src="{{$detail->avatar}}" /></p>
    <h2>{{$detail->name}}</h2>

    <p>简述:{{$detail->des}}</p>
    <p>行业:@foreach($detail->industry as $K =>$V)
        {{$V}}@endforeach</p>
    <h3>创业经历</h3>
    @foreach($detail->founderCases as $fK =>$fV)
    <p>时间:{{$fV["time"]}}</p>
    <p>职位:{{$fV["title"]}}</p>
    <p>工作:{{$fV["name"]}}</p>
    <p><img src="{{$fV["avatar"]}}" /></p>
    <p>简述:{{$fV["des"]}}</p>
    @endforeach
    <h3>工作经历</h3>
    @foreach($detail->workedCases as $wK =>$wV)
        <p>时间:{{$wV["time"]}}</p>
        <p>职位:{{$wV["title"]}}</p>
        <p>工作:{{$wV["name"]}}</p>
        {{--<p><img src="{{$fV["avatar"]}}" /></p>--}}
        <p>简述:{{$wV["des"]}}</p>

    @endforeach

@endsection