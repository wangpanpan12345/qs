@extends('admin')

@section('title', 'Page Title')

@section('sidebar')
    @parent

@endsection

@section('content')
    <h2>搜索"{{$k}}"</h2>
    @foreach($result as $rk=>$rv)
        <p><img src="{{$rv["avatar"]}}" width="auto" height="50px"/>
            <a href="{{ URL::to('/qs-admin/detail/'.$rv["_id"]) }}">{{$rv["name"]}}</a>-{{$rv["des"]}}
        </p>
    @endforeach

{{--    {{dd($result)}}--}}

@endsection