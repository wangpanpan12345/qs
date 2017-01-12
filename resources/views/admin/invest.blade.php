@extends('admin')

@section('title', 'Page Title')

@section('sidebar')
    @parent

@endsection

@section('content')
    <h2>奇速公司数据列表</h2>
    @foreach($invests as $K => $V)
        <p>
            <img src="{{$V["avatar"] or ""}}" width="50px" height="50px"/>
            <a href="{{ URL::to('/qs-admin/invest/detail/'.$V["_id"]) }}">{{$V["name"]}}</a>

        </p>
    @endforeach
    <a href="{{$invests->previousPageUrl()}}">前一页</a>
    <a href="{{$invests->nextPageUrl()}}">后一页</a>
@endsection
