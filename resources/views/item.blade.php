@extends('admin')

@section('title', 'Page Title')

@section('sidebar')
    @parent

@endsection
<style>
    .person_avatar {
        display: block;
        text-align: center;
        width: 100%;
    }

    h1 a {
        border: 2px solid;
        padding: 5px 10px;
        font-size: 16px;
    }

    .person_des {
        display: block;
        text-align: justify;
        width: 100%;
        background: #fff;
        border: 1px solid #e2e2e2;
        padding: 15px 3%;
        line-height: 25px;
        margin: 10px;
    }

    .person_des img {
        display: none;
    }

    .person_avatar img {
        width: 80px;
        height: 80px;
        border-radius: 50%;
    }

    .person_name {
        text-align: center;
    }

    .menu_label {
        width: 100%;
        padding: 2px;
        border-top: 2px solid #48b3f6;
        border-bottom: 2px solid #48b3f6;

    }

    .time_list, .title_list, .company_list {
        display: inline-block;
        width: 20%;
        text-align: center;
    }

    .des_list {
        display: block;
        width: 100%;
        background: #fff;
        border: 1px solid #e2e2e2;
        padding: 15px;
        line-height: 25px;
        margin: 10px;
    }

    .des_list label {
        background: #48b3f6;
        color: #fff;
        padding: 3px 15px;
        margin-right: 10px;
        min-width: 60px;
        text-align: center;
        border-radius: 3px;
    }

    .tag_list {
        display: inline-block;
        width: 100%;
        text-align: center;
    }

    .tag_list a {
        display: inline-block;
        text-align: center;
        padding: 4px 13px;
        background: #48b3f6;
        color: #fff;
        text-underline: none;
        border-radius: 3px;
    }

    .tag_list a:hover {
        color: #48b3f6;
        background: #fff;
        text-decoration: none;
    }

    .des {
        display: inline-block;
        width: 30%;
        text-align: center;
    }

    .content_list {
        display: inline-block;
        width: 100%;
        margin: 10px 0;
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

    .lemma-map, .header, .list {
        display: none;
    }

    .lemma-picture {
        display: none !important;
    }

    .title-text {
        font-size: 16px;
        color: #48b3f6;
    }

    .title-prefix {
        display: none;
    }

    .para {
        font-size: 14px;
        margin: 15px 0;
        word-wrap: break-word;
        text-indent: 2em;
        line-height: 24px;
        zoom: 1;
    }

    .basic_title {
        display: inline-block;
        font-size: 14px;
        color: #48b3f6;
        min-width: 100px;
    }

    .basic_list {
        display: inline-block;
        float: left;
        width: 45%;
    }

    table.table-view {
        margin: 5px 0;
        border-collapse: collapse;
        word-wrap: break-word;
        font-size: 12px;
        line-height: 22px;
        color: #000;

    }

    table th {
        border-bottom: 1px solid #e6e6e6;
        text-align: center;
        font-weight: 700;
        height: 23px;
        background-color: #f9f9f9;
    }

    table td {
        padding: 2px 10px;
        font-size: 12px;
        line-height: 22px;
        height: 22px;
        border: 1px solid #e6e6e6
    }

    .j-edit-link {
        display: none !important;
    }

    .basic_list span {
        display: inline-block;
    }

    .mw-editsection {
        display: none;
    }
</style>

@section('content')
    <?php if ($i["total"] != 0) {
        $detail = $i["hits"][0]["_source"];
        $label = "item";
    } elseif ($p["total"] != 0) {
        $detail = $p["hits"][0]["_source"];
        $label = "person";
    } elseif ($c["total"] != 0) {
        $detail = $c["hits"][0]["_source"];
        $label = "company";
    } else {

    }
    ?>

    {{--<h1><a href="https://admin.geekheal.net/edit/founder/{{$detail->id}}" target="_blank">编辑该词条</a></h1>--}}
{{--        {{dd($detail)}}--}}
    <div>
        <span class="person_avatar"><img
                    src="{{($detail["avatar"]!="")?$detail["avatar"]:"https://oi7gusker.qnssl.com/qisu/image/avatar_default.jpg"}}"/></span>

        <h2 class="person_name">{{$detail["name"]}}</h2>
        <span class="tag_list">
            @if(isset($detail["tags"])&&!empty($detail["tags"]))
                Tags:
                @foreach($detail["tags"] as $K =>$V)
                    <a href="/timeline/tag/{{$V}}">{{$V}}</a>
                @endforeach
            @endif
        </span>
        <span class="person_des">{!! $detail["des"] !!}</span>

    </div>
    @if(isset($detail["basic_info"])&&!empty($detail["basic_info"]))
        <span class="menu_title">基本信息</span>
        <span class="person_des" style="float: left">
            @foreach($detail["basic_info"] as $k =>$v)
                <div class="basic_list">
                    <span class="basic_title">{!!$v[0]!!}</span>
                    <span>{!! $v[1] !!}</span>
                </div>
            @endforeach
        </span>
        <div style="clear: both;"></div>
    @endif
    @if(isset($detail["extra_info"])&&!empty($detail["extra_info"]))
        @foreach($detail["extra_info"] as $k =>$v)
            <span class="menu_title">{!! $v[0] !!}</span>
            <span class="person_des">{!! $v[1] !!}</span>
        @endforeach
    @endif


    @if($label=="person")
        <div>

            @if(isset($detail->s_position)&&$detail->s_position!="")
                <span class="des_list">
                <label>Social Position</label>{{$detail->s_position}}
            </span>
            @endif

            @if(isset($detail->mail)&&$detail->mail!="")
                <span class="des_list">
                <label>Email</label>{{$detail->mail}}
            </span>
            @endif


            @if(isset($detail->tel)&&$detail->tel!="")
                <span class="des_list">
                <label>Tel</label>{{$detail->tel}}
            </span>
            @endif


        </div>

        @if(isset($detail->founderCases)&&!empty($detail->founderCases))
            <span class="menu_title">创业经历</span>
            <div class="menu_label">
                <span class="time_list">时间</span>
                <span class="company_list">公司</span>
                <span class="title_list">职位</span>
                <span class="des">简述</span>
            </div>
            @foreach($detail->founderCases as $fK =>$fV)
                <div class="content_list">
                    <span class="time_list">{{isset($fV["time"])?$fV["time"]:""}}</span>
                    <span class="company_list">{{$fV["name"]}}</span>
                    <span class="title_list">{{isset($fV["title"])?$fV["title"]:""}}</span>
                    <span class="des">{{isset($fV["des"])?$fV["des"]:""}}</span>
                </div>
            @endforeach
        @endif
        @if(isset($detail->workedCases)&&!empty($detail->workedCases))
            <span class="menu_title">工作经历</span>
            <div class="menu_label">
                <span class="time_list">时间</span>
                <span class="company_list">公司</span>
                <span class="title_list">职位</span>
                <span class="des">简述</span>
            </div>
            @foreach($detail->workedCases as $wK =>$wV)
                <div class="content_list">

                    <span class="time_list">{{isset($wV["time"])?$wV["time"]:""}}</span>
                    <span class="company_list"><a href="/qs-admin/company/{{$wV["name"]}}">{{$wV["name"]}}</a></span>
                    <span class="title_list">{{isset($wV["title"])?$wV["title"]:""}}</span>
                    <span class="des">{{isset($wV["des"])?$wV["des"]:""}}</span>

                </div>
            @endforeach
        @endif

        @if(isset($detail->edu_background)&&!empty($detail->edu_background))
            <span class="menu_title">教育经历</span>
            <div class="menu_label">
                <span class="time_list">学校</span>
                <span class="company_list">专业</span>
                <span class="title_list">学位</span>
            </div>
            @foreach($detail->edu_background as $wK =>$wV)
                <div class="content_list">

                    <span class="time_list">{{isset($wV["name"])?$wV["name"]:""}}</span>
                    <span class="company_list">{{$wV["major"]}}</span>
                    <span class="title_list">{{isset($wV["degree"])?$wV["degree"]:""}}</span>

                </div>
            @endforeach
        @endif

        @if(isset($detail->patent)&&!empty($detail->patent))
            <span class="menu_title">专利</span>
            <div class="menu_label">
                <span class="time_list">专利名</span>
                <span class="company_list">专利号</span>
                <span class="title_list">简述</span>
            </div>
            @foreach($detail->patent as $wK =>$wV)
                <div class="content_list">

                    <span class="time_list">{{isset($wV["name"])?$wV["name"]:""}}</span>
                    <span class="company_list">{{$wV["number"]}}</span>
                    <span class="title_list">{{isset($wV["des"])?$wV["des"]:""}}</span>

                </div>
            @endforeach
        @endif

        @if(isset($detail->projects)&&!empty($detail->projects))
            <span class="menu_title">参与项目</span>

            @foreach($detail->projects as $wK =>$wV)
                <div class="content_list">
                    <li>{{isset($wV["name"])?$wV["name"]:""}}</li>

                </div>
            @endforeach
        @endif

        @if(isset($detail->paper)&&!empty($detail->paper))
            <span class="menu_title">发表论文</span>

            @foreach($detail->paper as $wK =>$wV)
                <div class="content_list">
                    <li>{{isset($wV["name"])?$wV["name"]:""}}</li>

                </div>
            @endforeach
        @endif
    @endif

    {{--@if(!empty($timeline))--}}
    {{--<span class="menu_title">Timeline</span>--}}

    {{--@foreach($timeline as $t=>$V)--}}
    {{--<div class="list">--}}

    {{--<div class="date_wrap">--}}
    {{--<span class="news_date">{{$V->created_at}}</span>--}}
    {{--</div>--}}

    {{--<div class="content_wrap">--}}
    {{--<span class="">{{$V->excerpt}}</span>--}}
    {{--<span class="title">Origin:<a href="{{$V->link}}" target="_blank">{{$V->title}}</a>--}}
    {{--<span style="float: right;color: #d2d2d2">From:{{$V->source}}</span>--}}
    {{--</span>--}}
    {{--<span class="source_i">--}}
    {{--@if(!empty($V->tags))Tags:--}}
    {{--@foreach($V->tags as $k=>$v)--}}
    {{--<a href="/timeline/tag/{{$v}}">{{$v}}</a>--}}
    {{--@endforeach--}}
    {{--@endif--}}
    {{--</span>--}}
    {{--<span class="source_i">Editor:{{$V->users["name"]}}</span>--}}
    {{--<span class="source">Editor:{{$V->companies["name"]}}</span>--}}
    {{--</div>--}}


    {{--</div>--}}
    {{--@endforeach--}}
    {{--@endif--}}

@endsection