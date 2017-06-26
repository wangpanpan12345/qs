@extends('admin')

@section('title', '每日精选')
<meta name="csrf-token" content="{{ csrf_token() }}">
@section('sidebar')
    @parent
@endsection

{{--<link href="https://fonts.css.network/css?family=Raleway:100,600" rel="stylesheet" type="text/css">--}}
<script src="//cdn.bootcss.com/jquery/3.1.1/jquery.min.js"></script>
<style>
    body {
        font-family: Raleway, "Pingfang SC", "Microsoft YaHei", Helvetica, STHeiti, Verdana, Arial, Tahoma, sans-serif !important;
    }

    .key_menu {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 20px;
    }

    .preview {
    }

    .preview a {
        display: inline-block;
        padding: 5px 12px;
        color: #fff;
        text-underline: none;
        background: #1E88E5;
        border-radius: 2px;
        border: 1px solid #1E88E5;
        -webkit-transition: all .3s ease-in .1s;
        transition: all .3s ease-in .1s;
        -moz-transition: all .3s ease-in .1s;
        -o-transition: all .3s ease-in .1s;
    }

    .preview a:hover {
        text-decoration: none;
        color: #1E88E5 !important;
        background: #fff;
        border: 1px solid #1E88E5;
        -webkit-transition: all .3s ease-in .1s;
        transition: all .3s ease-in .1s;
        -moz-transition: all .3s ease-in .1s;
        -o-transition: all .3s ease-in .1s;
    }

    .list {
        position: relative;
        /*margin: 20px 0;*/
        border: 1px solid #e2e2e2;
        border-left: 10px solid #d8d8d8;
        /*padding: 10px;*/
    }

    .list:before {
        content: ' ';
        width: 10px;
    }

    .main_opr {
        display: flex;
        background: #fff;
        padding: 10px;
    }

    .content_wrap {
        flex: 1;
        /*display: inline-block;*/
        /*width: 78%;*/
        /*vertical-align: middle;*/
        /*top: 50%;*/
        margin-right: 20px;
    }

    .opr_wrap {
        flex-direction: column;
        flex: 0 0 100px;
        align-self: center;
        display: flex;
        text-align: center;
        align-items: flex-end;
    }

    .opr_wrap a {
        display: block;
        color: #fff;
        background: #1E88E5;
        width: 60px;
        height: 30px;
        line-height: 30px;
        border-radius: 2px;
        margin: 2px 0;
        cursor: pointer;
        text-decoration: none;
    }

    .tags {
        display: flex;
        justify-content: space-between;
        padding: 10px;
        height: 40px;
        font-size: 12px;
        background: #f2f2f2;

    }

    .tags label {
        padding: 0 12px;
        background: #48b3f6;
        color: #fff;
        border-radius: 2px;
        margin-right: 6px;
    }

    .tags span {
        margin-right: 16px;
        color: #9b9a9b;
    }

    .today {
        border-left: 10px solid #1d87e5;
    }

    .author_label {
        width: 130px;
        text-align: left;
    }

    .latest {
        position: absolute;
        top: -18px;
        display: block;
        z-index: 2;
        float: left;
        border: 1px solid #e2e2e2;
        font-size: 12px;
        left: -2px;
        color: #fff;
        background: #fe4b55;
        height: 17px;
        line-height: 13px;
        padding: 1px 15px;
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

    .source a {
        color: #a2a2a2;
    }

    .source a:hover {
        color: #fe4b55;
    }

    .source {
        display: block;
        text-align: right;
        color: #d2d2d2;
    }

    .author {
        text-align: right;
    }

    .author b {
        color: #1E88E5;
    }

    .key_edit, .key_collect, .key_share {
        /*text-align: right;*/
    }

    .key_edit span, .key_collect span, .key_share span {
        /*background: #fe4b55;*/
        /*color: #fff;*/
        /*margin: 0 18px;*/
        /*padding: 0 10px;*/
        /*border-radius: 3px;*/
        /*cursor: pointer;*/
    }

    .key_edit a img, .key_collect img, .key_share img {
        width: 60px;
        height: 20px;
        margin: 5px 0;
    }

    .key_collect a {
        font-size: 20px;
    }

    .key_collect a:hover {
        color: #fff;
        text-underline: none;
        text-decoration: none;
    }

    .collected, .shared {
        color: #f8e71c !important;
        background: #fff !important;
        border: 1px solid #1d87e5;
    }

    .share {
        display: none;
        position: fixed;
        width: 600px;
        height: auto;
        left: 50%;
        margin-left: -300px;
        top: 50%;
        margin-top: -250px;
        padding: 60px;
        z-index: 10;
        border: 2px solid #48b3f6;
        background: #fff;
        color: #000;
        vertical-align: middle;
        /*text-align: center;*/
        border-radius: 10px;
        font-size: 20px;
        overflow: hidden;
        font-weight: 600;
    }

    .share .share_content {
        text-align: left;
        line-height: 30px;
    }

    #share_info {
        margin: 10px 0;
        font-size: 16px;
        display: block;
        text-align: center;
    }

    .close_s {
        position: absolute;
        display: block;
        top: 10px;
        width: 10px;
        height: 10px;
        right: 10px;
        line-height: 10px;
        cursor: pointer;
    }

    .share_ok_wrap {
        text-align: right;
    }

    .share_ok_wrap a {
        display: block;
        margin-top: 20px;
    }

    .title_wrap {
        display: flex;
    }

    .title_wrap label {
        flex: 0 0 100px;
    }

    .title_wrap #share_title {
        width: 100%;

    }

    @media (max-width: 748px) {
        .date_wrap {
            display: block;
            width: 100%;
        }

        .content_wrap {
            display: block;
            width: 100%;
            text-align: justify;
        }

        .title {
            width: 100%;
        }

        .share {
            width: 92%;
            margin-left: 0px;
            left: 4%;
        }

        .key_edit span, .key_collect span, .key_share span {
            float: right;
            margin: 10px 18px;
        }

        .list {
            padding-bottom: 40px;
        }

        .main_opr {
            flex-direction: column;
        }

        .opr_wrap {
            justify-content: space-between;
            align-self: auto;
            flex-direction: row;
            flex: 0 0 50px;
        }
    }

</style>
@section('content')
    <?php
    $author = isset($author) ? $author : "All";
    $scoring = $keynews->total();
    $level = ($author == "All") ? "" : "Lv" . (intval($scoring / 100) + 1);
    ?>
    <div class="share">
        <div class="title_wrap"><label>分享标题:</label><input id="share_title" type="text"/></div>
        <div class="share_ok_wrap"><a class="share_ok" href="javascript:void(0);">确认</a></div>
        <span class="close_s">X</span>
    </div>
    <section class="key_menu">
        <div class="preview">
            <a target="_blank" href="/member/{{md5(Carbon\Carbon::today()."geekqs")."_".\Carbon\Carbon::today()}}">预览今日分享</a>
        </div>
        <div class="author"><b>作者:{{$level}}</b>
            <select class="source_select">
                <option value="All">All</option>
                <option value="5847b487e9c046107b05fa81" {{($author=="5847b487e9c046107b05fa81")?"selected":""}}>周伦
                </option>
                <option value="5858ec53e9c0460445718d62" {{($author=="5858ec53e9c0460445718d62")?"selected":""}}>应雨妍
                </option>
                <option value="5847c54de9c046107c26a381" {{($author=="5847c54de9c046107c26a381")?"selected":""}}>陈亚慧
                </option>
                <option value="58b6354fe9c0461d8f46d542" {{($author=="58b6354fe9c0461d8f46d542")?"selected":""}}>王新凯
                </option>
                <option value="58e6f811e9c046049346ef82" {{($author=="58e6f811e9c046049346ef82")?"selected":""}}>朱爽
                </option>
                <option value="58e6f820e9c046733142c1d1" {{($author=="58e6f820e9c046733142c1d1")?"selected":""}}>王鑫英
                </option>
                <option value="59195f7ee9c0460b7f1c202e" {{($author=="59195f7ee9c0460b7f1c202e")?"selected":""}}>李芙蓉
                </option>
            </select>
        </div>
    </section>
    <section>
        @foreach($keynews->items() as $K => $V)
            {{--{{$V->updated_at }}--}}
            <div class="list @if($V->updated_at > Carbon\Carbon::today()->subHours(6)) today @endif">
                <div class="main_opr">
                    <div class="content_wrap">
                        <span class="des">{{$V->excerpt}}</span>
                        <span class="title">
                            origin:<a href="{{$V->link}}" target="_blank">{{$V->title}}</a>
                        </span>
                    </div>
                    <div class="opr_wrap" data-id="{{$V->id}}">
                        <span class="key_edit"><a><img src="/img/Edit.svg" alt="编辑"></a></span>
                        <span class="key_share">
                            @if(isset($V->shared)&&$V->shared=="1")
                                <a class="shared"><img src="/img/Share_02.svg" alt="分享"></a>
                                <input class="share_title" value="{{$V->share_title}}" style="display: none"/>
                            @else
                                <a><img src="/img/Share.svg"></a>
                            @endif
                        </span>
                        <span class="key_collect">
                            @if(isset($V->cuser)&&in_array(Auth::user()->id,$V->cuser))
                                <a class="collected">★</a>
                            @else
                                <a>★</a>
                            @endif

                        </span>
                    </div>
                </div>
                <div class="tags">
                    <div>
                        <span><label>来源</label>{{$V->source}}</span>
                        <span><label>时间</label>{{$V->created_at}}</span>
                        <span><label>tag</label>
                            @if(!empty($V->tags))
                                @foreach($V->tags as $k=>$v)
                                    <a href="/timeline/tag/{{$v}}">{{$v}}</a>
                                @endforeach
                            @endif
                        </span>
                    </div>
                    <div class="author_label">
                        <span><label>Editor</label>
                            {{$V->users["name"]}}
                        </span>
                    </div>
                </div>
            </div>
        @endforeach
    </section>



    {{--@foreach($keynews->items() as $K => $V)--}}

    {{--<div class="list" data-id="{{$V->id}}">--}}
    {{--@if($V->updated_at > Carbon\Carbon::today()->subHours(6))--}}
    {{--<label class="latest">today</label>--}}
    {{--@endif--}}
    {{--<div class="date_wrap">--}}
    {{--<span class="news_date">{{$V->created_at}}</span>--}}
    {{--</div>--}}

    {{--<div class="content_wrap">--}}
    {{--<span class="des">{{$V->excerpt}}</span>--}}
    {{--<span class="title">Origin:<a href="{{$V->link}}" target="_blank">{{$V->title}}</a>--}}
    {{--<span style="display:block;text-align: right;color: #d2d2d2">From:{{$V->source}}</span>--}}
    {{--</span>--}}
    {{--<span class="source">--}}
    {{--@if(!empty($V->company))--}}
    {{--Company:--}}
    {{--@foreach($V->companies() as $k=>$v)--}}
    {{--@if($v["_id"]!==""&&$v["name"]!=="")--}}
    {{--<a href="/qs-admin/detail/{{$v["_id"]}}">{{$v["name"]}}</a>--}}
    {{--@endif;--}}
    {{--@endforeach--}}
    {{--@endif--}}
    {{--</span>--}}
    {{--<span class="source">--}}
    {{--@if(!empty($V->person))Person:--}}
    {{--@foreach($V->person as $k=>$v)--}}
    {{--<a href="/qs-admin/founder/{{$v["_id"]}}">{{$v["name"]}}</a>--}}
    {{--@endforeach--}}
    {{--@endif--}}
    {{--</span>--}}
    {{--<span class="source">--}}
    {{--@if(!empty($V->tags))Tags:--}}
    {{--@foreach($V->tags as $k=>$v)--}}
    {{--<a href="/timeline/tag/{{$v}}">{{$v}}</a>--}}
    {{--@endforeach--}}
    {{--@endif--}}
    {{--</span>--}}
    {{--<span class="source">Editor:{{$V->users["name"]}}</span>--}}
    {{--</div>--}}
    {{--<div class="key_edit"><span>编辑</span></div>--}}

    {{--@if(isset($V->cuser)&&in_array(Auth::user()->id,$V->cuser))--}}
    {{--<div class="key_collect" disabled="true"><span>已收藏</span></div>--}}
    {{--@else--}}
    {{--<div class="key_collect"><span>收藏</span></div>--}}
    {{--@endif--}}

    {{--<div class="key_share">--}}
    {{--<span>--}}
    {{--@if(isset($V->shared)&&$V->shared=="1")--}}
    {{--已分享--}}
    {{--<input class="share_title" value="{{$V->share_title}}" style="display: none"/>--}}
    {{--@else--}}
    {{--加入分享--}}
    {{--@endif--}}
    {{--</span>--}}

    {{--</div>--}}

    {{--</div>--}}

    {{--@endforeach--}}
    {{$keynews->links()}}
    <script>
        $(function () {
            $(".source_select").change(function () {
                var source = $(this).children('option:selected').val();
                if (source == "All") {
                    window.location.href = "/qs-admin/keynews";
                } else {
                    window.location.href = "/qs-admin/keynews/author/" + source;
                }
            });
            $(".key_edit").click(function () {
                var title = $(this).parent(".opr_wrap").siblings(".content_wrap").find(".title a")[0].innerHTML;
                window.location.href = encodeURI("/dailynews/key?key=" + title);
            });
            $(".key_collect").click(function () {
                var param = {};
                var _id = $(this).parent(".opr_wrap").attr("data-id");
                var type = "dailynews";
                param._id = _id;
                param.type = type;
                _collect("/dailynews/collect", param, $(this));
            });
            $(".key_share").click(function () {
                var _id = $(this).parent(".opr_wrap").attr("data-id");
                $(".share").attr("data-id", _id);
                $("#share_title").val($(this).children(".share_title").val());
                $(".share").show();
            });
            $(".close_s").click(function () {
                $(this).parent(".share").hide();
            });
            $(".share_ok").click(function () {
                var title = $("#share_title").val();
                var _id = $(".share").attr("data-id");
                if (title == "") {
                    alert("不能为空!");
                    return false;
                }
                var param = {};
                param.title = title;
                param._id = _id;
                var dom = $("[data-id='" + _id + "']").find(".key_share a");
//                console.log(dom);
                _shared("/qs-admin/keynews/shared", param, dom);
            });

            function _collect(url, param, dom) {
                $.ajax({
                    method: "POST",
                    url: url,
                    dataType: "json",
                    data: param,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (data) {
                        if (data.error == 0) {
                            dom.children("a").addClass("collected");
//                            dom.children("a")[0].innerHTML = "<a class="collected">★</a>";
                            dom.children("a").css("diabled");
                            console.log(data);
                        } else if (data.error == 5) {
                            alert("您已收藏");
                        } else {
                            alert("收藏失败!")
                            console.log(data);
                        }
                    },
                    error: function (data) {
                        alert("网络错误!")
                        console.log(data);
                    }
                })
            }

            function _shared(url, param, dom) {
                $.ajax({
                    method: "POST",
                    url: url,
                    dataType: "json",
                    data: param,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (data) {
                        if (data.error == 0) {
                            dom[0].innerHTML = '<img src="/img/Share_02.svg"><input class="share_title" ' + param.title + '" style="display: none"/>';
                            dom.css("diabled");
                            $(".share").hide();
                        } else if (data.error == 5) {
                            alert("已分享");
                        } else {
                            alert("分享失败!")
                            console.log(data);
                        }
                    },
                    error: function (data) {
                        alert("网络错误!")
                        console.log(data);
                    }
                })
            }
        })
    </script>
@endsection