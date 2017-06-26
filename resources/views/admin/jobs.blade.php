@extends('admin')

@section('title', 'qisu')
<meta name="csrf-token" content="{{ csrf_token() }}">
@section('sidebar')
    @parent
@endsection
<script src="//cdn.bootcss.com/jquery/3.1.1/jquery.min.js"></script>
<link href="//cdn.bootcss.com/select2/4.0.3/css/select2.min.css" rel="stylesheet">
<script src="//cdn.bootcss.com/select2/4.0.3/js/select2.min.js"></script>

<style>
    body {
        font-family: "Pingfang SC", "Microsoft YaHei", Helvetica, STHeiti, Verdana, Arial, Tahoma, sans-serif !important;
    }

    .job_block {
        margin: 20px 0;
    }

    .job_block input {
        width: 200px;
        outline: none;
    }

    .tags, .uid {
        padding: 5px 10px;
        background: #48b3f6;
        color: #fff;
        margin: 0 10px;
    }

    .users ul {
        display: none;
        margin: 0;
        padding: 0;
    }

    .users li {
        margin: 0;
        padding: 0;
        list-style: none;
        background: #48b3f6;
        color: #fff;
        width: 200px;
        text-align: center;
        margin: 3px 0;
        height: 30px;
        line-height: 30px;
        cursor: pointer;
    }

    .tag_c {
        display: inline-block;
    }

    .assign {
        padding: 8px 20px;
        background: #48b3f6;
        color: #fff;
    }

    .list-wrapper {
        width: 43%;
        height: auto;
        min-height: 30px;
        padding: 4px;
        display: inline-block;
        margin: 2% 2%;
        float: left;
        border-radius: 3px;
        background-color: #E2E4E6;
        -webkit-transition: background 85ms ease-in, opacity 40ms ease-in, border-color 85ms ease-in;
        transition: background 85ms ease-in, opacity 40ms ease-in, border-color 85ms ease-in
    }

    .list-wrapper.mod-add.fade {
        opacity: 0
    }

    .list-name-input {
        border: 1px;
        border-radius: 2px;
        background: rgba(0, 0, 0, .05);
        border-color: #aaa;
        display: none;
        outline: none;
        margin: 0;
        -webkit-transition: margin 85ms ease-in, background 85ms ease-in;
        transition: margin 85ms ease-in, background 85ms ease-in;
        width: 100%;
        height: 40px;
        border-radius: 4px;
    }

    .js-list-name-input {
        border: 1px;
        border-radius: 2px;
        background: rgba(0, 0, 0, .05);
        border-color: #aaa;
        outline: none;
        margin: 0;
        width: 100%;
        height: 40px;
        border-radius: 4px;
    }

    .list-wrapper.mod-add .placeholder {
        display: block;
        width: 100%;
        padding: 7px;
        color: rgba(255, 255, 255, .7);
        cursor: pointer;
        -webkit-transition: color 85ms ease-in;
        transition: color 85ms ease-in;

    }

    .list-wrapper.mod-add .list-add-controls {
        height: 3pc;
        -webkit-transition: margin 85ms ease-in, height 85ms ease-in;
        transition: margin 85ms ease-in, height 85ms ease-in;
        overflow: hidden;
        margin: 4px 0 0
    }

    input.mod-list-add-button {
        float: left;
        min-height: 30px;
        height: 30px;
        margin-top: 15px;
        padding-top: 4px;
        padding-bottom: 4px;
        background: #48b3f6;
        border: 0px;
        border-radius: 3px;
        width: 70px;
        color: #fff;
    }

    .list-header h2 {
        font-size: 20px;
        color: #4d4d4d;
    }

    .open-card-composer, .headline-add {
        color: #4d4d4d;
        margin: 10px 3px;
    }

    .headline-add {
        padding: 0 10px;
        height: 30px;
        display: block;
        background: #48b3f6;
        width: 60px;
        line-height: 30px;
        text-align: center;
        color: #fff;
        border-radius: 3px;
    }

    .headline-list {
        background: #fff;
        min-height: 40px;
        border-radius: 3px;
        line-height: 30px;
        margin: 10px 5px;
    }

    .topic-list {
        margin: 20px 0;
    }

    .headline-edit {
        display: none;
    }

    .to-user-list {
        position: absolute;
        width: 200px;
        left: 50%;
        margin-left: -100px;
        height: auto;
        background: #48b3f6;
        display: none;
        z-index: 10;
    }

    .to-user-list a {
        display: block;
        color: #fff;
        cursor: pointer;
        height: 40px;
        line-height: 40px;
        margin: 0 auto;
        border-bottom: 2px solid #fff;
        text-align: center;

    }

    .background {
        position: fixed;
        width: 100%;
        height: 100vh;
        z-index: 2;
        background: #000;
        opacity: .5;
        display: none;
    }

    .to-user-list a:hover {
        color: #48b3f6;
        background: #fff;
        text-underline: none;
    }
    .topic-add{
        display: block;
        width: 100%;
        float: left;
    }

</style>
<div class="background"></div>
@section('content')
    <h3>标签整理任务</h3>

    <div>
        <div class="job_block subject">
            <span><input type="text" class="s_input" placeholder="主题"/></span>
        </div>
        <div class="job_block selection">
            <select class="tags_select" multiple="multiple" style="width: 300px">

            </select>
        </div>
        <div class="job_block users">

            <span>
                <input class="u_input" type="text" placeholder="指派给谁"/>
            </span>
            <span class="tag_c tag_c_u"></span>
            <ul class="author">
                <li>zhoulun</li>
                <li>yingyuyan</li>
                <li>wangxinkai</li>
                <li>wangren</li>
                <li>chenyahui</li>
            </ul>

        </div>
        <div class="job_block">
            <a href="javascript:void(0);" class="assign">开始分配</a>
        </div>
    </div>

    <h3>选题任务</h3>

    <div>
        <div class="topic-add">
            <div class="js-add-list list-wrapper mod-add">
                <span class="placeholder js-open-add-list">添加一个任务…</span>
                <input class="list-name-input" type="text" name="name" placeholder="添加一个任务主题…" autocomplete="off"
                       dir="auto" maxlength="512">

                <div class="list-add-controls u-clearfix">
                    <input class="primary mod-list-add-button js-save-edit" type="submit" value="保存">
                    <a class="icon-lg icon-close dark-hover js-cancel-edit" href="javascript:void(0);"></a>
                </div>
            </div>
        </div>

        <div class="topic-list">
            @foreach($jobt as $k=>$v)
                <div class="js-list list-wrapper" data-id="{{$v->_id}}">
                    <div class="list-header js-list-header">
                        <h2>{{$v->topic}}</h2>
                        @if(isset($v->headline)&&!empty($v->headline))
                            @foreach($v->headline as $H=>$L)
                                <div class="headline-list" data-id="{{$L["id"]}}">{{$L["title"]}}</div>
                            @endforeach
                        @endif
                    </div>
                    <div class="headline-edit">
                    <textarea
                            class="js-list-name-input" spellcheck="false" dir="auto"
                            maxlength="512"
                            style="overflow: hidden; word-wrap: break-word; height: 84px;"></textarea>
                        <a class="headline-add" href="javascript:void(0);">添加</a>
                    </div>

                    <a class="open-card-composer js-open-card-composer" href="javascript:void(0);">添加大纲…</a>
                    <a class="open-card-composer js-topic-to-user" href="javascript:void(0);">分配选题…</a>
                    <a class="user-now">{{!empty($v->user())?$v->user()[0]->name:""}}</a>
                </div>
            @endforeach
        </div>
        <div class="to-user-list" data-id=0>
            <a data-id="5847b487e9c046107b05fa81">周伦</a>
            <a data-id="5858ec53e9c0460445718d62">应雨妍</a>
            <a data-id="58b6354fe9c0461d8f46d542">王新凯</a>
            <a data-id="58e6f820e9c046733142c1d1">王鑫英</a>
            <a data-id="58e6f811e9c046049346ef82">朱爽爽</a>
            <a data-id="59195f7ee9c0460b7f1c202e">李芙蓉</a>
        </div>

    </div>




    <script>
        $(function () {
            $(".tags_select").select2();
            $(".u_input").on("input", function () {
                $(".author").show();
            });
            $(".author li").click(function () {
                var html = "";
                html = '<i class="uid">' + $(this).text() + '</i>';
                $(".tag_c_u").append(html);
                $(this).parent("ul").hide();
                $(".u_input").val("");
            });
            $(".tags_select").select2({
                placeholder: '选择疑似标签',
                ajax: {
                    url: "/api/job/tags/",
                    cache: true,
                    delay: 250,
                    processResults: function (data) {
                        var rs = [];
                        $.each(data.result, function (n, value) {
                            var param = {};
                            param.id = value["_id"];
                            param.text = value["name"];
                            rs.push(param);
                        });
                        return {results: rs}
                    },
                }
            });
            $("body").on("click", ".assign", function () {
                var param = {};
                var name = $(".tags_select").siblings(".select2").find(".select2-selection__choice");
                var tags = [];
                $.each(name, function (n, value) {
                    tags.push($(value).attr("title"));

                });
                var users = [];
                var user_tag = $(".tag_c_u .uid");
                $.each(user_tag, function (n, value) {
                    users.push($(value).text());

                });
                var subject = $(".s_input").val();
                param.subject = subject;
                param.selection = tags;
                param.user = users;
                if (tags.length == 0 || users.length == 1 || subject == "") {
                    console.log(param);
                    alert("条件不足,不予分配!");
                    return false;
                }
                assign_jobs("'/jobs/create/", param);
                console.log(param);

            });

            $('.js-open-add-list').click(function () {
                $(this).hide();
                $(".list-name-input").show();
            });
            $(".topic-list").on("click", ".js-topic-to-user", function () {
                var id = $(this).parents(".js-list").attr("data-id");
                $(".to-user-list").attr("data-id", id);
                $(".to-user-list").show();
                $(".background").show();
            });
            $(".background").click(function () {
                $(".to-user-list").hide();
                $(".background").hide();
            });
            $(".to-user-list a").click(function () {
                $(".to-user-list").hide();
                $(".background").hide();
                var id = $(this).parents(".to-user-list").attr("data-id");
                var touser = $(this).attr("data-id");
                var param = {};
                param.id = id;
                param.touser = touser;
                topic_touser(param, "/qs-admin/topic/touser");
            });

            $(".js-save-edit").click(function () {
                var topic = $(".list-name-input").val();
                var param = {};
                param.topic = topic;
                if (topic == "") {
                    alert("空提交!")
                    return false;
                }
                create_topic(param, "/qs-admin/topic/create");
            });
            $('body').on('click', '.js-open-card-composer', function () {
                $(this).siblings(".headline-edit").show();
                $(this).hide();
            });
            $(".topic-list").on("click", ".headline-add", function () {
                var headline = $(this).siblings("textarea").val();
                var id = $(this).parents(".headline-edit").parents(".js-list").attr("data-id");
                var param = {};
                param.headline = headline;
                param.id = id;
                if (headline == "") {
                    alert("空提交!")
                    return false;
                }

                var dom = $(this).parents(".headline-edit").siblings(".js-list-header");
                create_headline(param, "/qs-admin/topic/headline-create", dom);
                $(this).siblings("textarea").val("");
                $(this).parents(".headline-edit").hide();
                $(this).parents(".headline-edit").siblings(".js-open-card-composer").show();
            });
            function create_topic(param, url) {
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
                            var html = '<div class="js-list list-wrapper" data-id="' + data.result.id + '">' +
                                    '<div class="list-header js-list-header">' +
                                    '<h2>' + data.result.topic + '</h2>' +
//                                    '<div class="headline-list"></div>' +
                                    '</div>' +
                                    '<div class="headline-edit">' +
                                    '<textarea class="js-list-name-input" spellcheck="false" dir="auto" maxlength="512"' +
                                    'style="overflow: hidden; word-wrap: break-word; height: 84px;"></textarea>' +
                                    '<a class="headline-add" href="javascript:void(0);">添加</a>' +
                                    '</div>' +
                                    '<a class="open-card-composer js-open-card-composer" href="javascript:void(0);">添加大纲…</a>' +
                                    '<a class="open-card-composer js-topic-to-user" href="javascript:void(0);">分配选题…</a>' +
                                    '</div>';
                            $(".topic-list").prepend(html);

                        } else {
                            console.log(data);
                        }
                    },
                    error: function (data) {
                        console.log(data);
                    }
                })
            }

            function create_headline(param, url, dom) {
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
                            var html = '<div class="headline-list" data-id="' + data.result.id + '">' +
                                    data.result.title + '</div>';
                            dom.append(html);

                        } else {
                            console.log(data);
                        }
                    },
                    error: function (data) {
                        console.log(data);
                    }
                })
            }

            function topic_touser(param, url, dom) {
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
                            alert("OK");
                        } else {
                            console.log(data);
                        }
                    },
                    error: function (data) {
                        console.log(data);
                    }
                })
            }


        })
    </script>

@endsection