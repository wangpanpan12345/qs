@extends('admin')

@section('title', 'qisu')

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
</style>
@section('content')

    @foreach($namecards->items() as $K => $V)
        <div class="list">
            <span>{{$V->name}}</span>
            <span>{{$V->company}}</span>
            <span>{{$V->jobtitle}}</span>
        </div>
    @endforeach
    {{$namecards->links()}}
@endsection