@extends('admin')

@section('title', 'qisu')
<meta name="csrf-token" content="{{ csrf_token() }}">
@section('sidebar')
    @parent
@endsection
<script src="//cdn.bootcss.com/jquery/3.1.1/jquery.min.js"></script>
<script src="/js/modernizr.js"></script> <!-- Modernizr -->
<script src="//cdn.bootcss.com/sisyphus.js/1.1.103/sisyphus.min.js"></script>
{{--<link href="//cdn.bootcss.com/wangeditor/2.1.20/css/wangEditor.min.css" rel="stylesheet">--}}

<style>
    body {
        margin: 0 !important;
        padding: 0 !important;
        font-family: "Pingfang SC", "Microsoft YaHei", Helvetica, STHeiti, Verdana, Arial, Tahoma, sans-serif !important;
    }

    ul {
        margin-left: 0;
        padding-left: 0;
    }

    .content_container {
        position: relative;
        border: 1px solid #e2e2e2;
        background: #fff;
        width: 100%;
        min-height: 600px;
        margin-bottom: 50px;
    }

    .clear {
        clear: both
    }

    .menu_panel {
        position: relative;
        height: 50px;
        line-height: 50px;
    }

    .menu_panel ul {
        margin: 0;
        padding: 0;
        border: 0;
        -webkit-box-sizing: border-box;
        -moz-box-sizing: border-box;
    }

    .menu_panel li {
        margin: 0;
        padding: 0;
        border: 0;
        display: inline-block;
        list-style: none;
        width: 20%;
        float: left;
        height: 50px;
        line-height: 50px;
        text-align: center;
        background: #fff;
        border-right: 1px solid #e2e2e2;
        cursor: pointer;
        color: #383838;
        background: #f5f5f5;
    }

    .progress {
        width: 75%;
        display: inline-block;
        vertical-align: middle;
        margin-bottom: 8px !important;
        display: none;
    }

    .menu_panel li:nth-child(1) {
        background: #fff;
        border-top: 2px solid #fe4b55;
    }

    .mission_panel, .collect_panel, .memory_panel {
        padding: 20px;
        display: none;
    }

    .mission_panel h4 {
        width: 20%;
        display: inline-block;

    }

    .mission_panel {
        display: block;
    }

    .mission_panel ul {
        margin-top: 20px;
        display: flex;
        flex-wrap: wrap;
        max-height: 800px;
        overflow-y: scroll;
        width: 100%;
    }

    .m_info {
        padding: 5px 20px;
        font-size: 13px;
        text-align: justify;
        letter-spacing: 1px;
    }

    .card {
        margin: 10px 0;
        list-style: none;
        padding: 10px;
        border: 1px solid #e2e2e2;
        width: 100%;
        height: auto;
        float: left;
        vertical-align: middle;
        position: relative;
    }

    .collect_card {
        width: 90%;
        height: auto;
    }

    .card_avatar {
        /*padding: 10px;*/
        text-align: justify;
        font-size: 12px;
        font-weight: bold;
        letter-spacing: 1px;
    }

    .card_avatar img {
        width: 80px;
        height: 80px;
        border-radius: 50%;
    }

    /*.m_card span {*/
    /*display: block;*/
    /*margin: 10px auto;*/
    /*text-align: center;*/
    /*padding: 5px 10px;*/
    /*text-align: justify;*/
    /*color: #757575;*/
    /*}*/
    .job_flex {
        display: flex;
        justify-content: space-between;
    }

    .job_bottom {
        position: relative;
        display: inline-block;
        bottom: 5px;
    }

    .job_info {
        display: inline-block;
        margin: 15px 0;
    }

    .job_opr {
        display: inline-block;
        font-size: 12px;
    }

    .collect_card span {
        display: block;
        margin: 10px 0;
        padding: 0 10px;
        text-align: justify;
    }

    #memory .note_all {
        margin: 0;
        padding: 0;
        float: left;
        width: 40%;
        max-height: 800px;
        overflow-y: scroll;
    }

    #memory li {
        cursor: pointer;
    }

    #memory .memory_card {
        width: 49%;
        height: 250px;
        margin-right: 2px;
        overflow: hidden;
    }

    .memory_menu {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin: 20px 0;
    }

    .memory_menu span {
        width: 300px;
    }

    .share_source {
    }

    .share_source ul {
        display: none;
        width: 150px;
        position: absolute;
        z-index: 10;
        background: #fff;
        width: 150px;
        border: 1px solid #d8d8d8;
        text-align: center;
    }

    .share_source ul li {
        margin: 0;
        padding: 0;
        list-style: none;
        text-align: center;
    }

    .share_source ul li:hover {
        background: #1d87e5;
        color: #fff;
    }

    .memory_card span {
        display: block;
        margin: 10px;
        min-height: 30px;
    }

    .memory_editor, .job_editor {
        width: 58%;
        float: right;
        margin-top: 10px;
    }

    .job_content {
        flex: 0 0 40%;
    }

    .job_editor {
        flex: 0 0 55%;
        height: 100%;
        margin-top: 30px;
        z-index: 5;
    }

    #cke_1_contents, #cke_2_contents {
        min-height: 600px;
    }

    .memory_editor #myEditor {
        width: 100%;
        height: 80%;

    }

    .memory_editor .m_submit {
        display: block;
        padding: 10px 30px;
        background: #48b3f6;
        color: #fff;
        border-radius: 5px;
        margin: 20px 0;
        text-align: center;
    }

    .m_tag {
        border-bottom: 1px solid #e2e2e2;
        padding: 5px 0;
        font-size: 12px;
    }

    .m_tag i {
        /*background: #48b3f6;*/
        margin-bottom: 3px;
        padding: 3px 5px;
        color: #48b3f6;
        border-radius: 3px;
        font-size: 12px;
        margin-right: 3px;
    }

    .m_tag i a {
        color: #48b3f6;
    }

    .m_content {
        max-height: 144px;
        overflow: hidden;
        font-size: 13px;
        letter-spacing: 1px;
    }

    .m_date {
        font-size: 13px;
        letter-spacing: 1px;
    }

    .wangEditor-container, .wangEditor-txt {

        min-height: 100vh !important;
        max-height: 1000px;
        position: relative;
    }

    .wangEditor-menu-container {
        position: static !important;
        width: 100% !important;
    }

    .memory_menu input {
        border: none;
        outline: none;
        border-bottom: 2px solid #e2e2e2;
    }

    #new_note, #share_note {
        padding: 5px 20px;
        background: #48b3f6;
        color: #fff;
        border-radius: 5px;
        margin: 0 5px;
    }

    .headline-list {
        background: #48b3f6;
        color: #fff;
        border-radius: 2px;
        margin: 10px 0;
        cursor: pointer;
        min-height: 30px;
        line-height: 30px;
        padding: 0 10px;
        font-size: 12px;
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

    .job_topic_submit {
        /*border: 2px solid #fe4b55;*/
        color: #fff;
        margin: 30px 0;
        height: 40px;
        display: block;
        width: 60px;
        line-height: 40px;
        text-align: center;
        border-radius: 5px;
        background: #48b3f6;
    }

    .score {
        float: right;
        margin-right: 20px;
    }

    .close {
        position: absolute;
        top: 0;
        right: 0;
    }
    .container{
        width: 100%!important;
    }

    @media (max-width: 748px) {

    }
</style>
<div class="background"></div>
@section('content')
    {{--{{dd($notes)}}--}}

    <div class="content_container">
        <div class="menu_panel">
            <ul>
                <li class="menu_label" data-id="mission">任务</li>
                <li class="menu_label" data-id="collect">收藏</li>
                <li class="menu_label memory_label" data-id="memory">笔记</li>
                <span class="score">本月得分:{{$score}}</span>
            </ul>
        </div>
        <div class="mission_panel" id="mission">
            <h4>我的选题</h4>
            <span class="progress"><span></span></span>
            @if(Auth::user()->id=="5847b487e9c046107b05fa81"||Auth::user()->id=="58477050c3666e96381c5399")
                <label>完成状态: </label><select id="job_topic_status">
                    <option value="2" {{($status=="2")?"selected":""}}>All</option>
                    <option value="1" {{($status=="1")?"selected":""}}>已完成</option>
                    <option value="0" {{($status=="0")?"selected":""}}>待完成</option>
                </select>
                <select id="job_topic_author">
                    <option>All</option>
                    <option value="5847b487e9c046107b05fa81">周伦</option>
                    <option value="58b6354fe9c0461d8f46d542">王新凯</option>
                    <option value="58e6f811e9c046049346ef82">朱爽爽</option>
                    <option value="5858ec53e9c0460445718d62">应语妍</option>
                    <option value="58e6f820e9c046733142c1d1">王鑫英</option>
                    <option value="59195f7ee9c0460b7f1c202e">李芙蓉</option>
                </select>
            @endif
            {{--<ul>--}}
            {{--@foreach($jobs as $k=>$v)--}}
            {{--@if(count($v->company)>0)--}}
            {{--<li class="m_card card">--}}
            {{--<span class="card_avatar"><img src="{{$v->company->avatar}}"></span>--}}
            {{--<span><a href="{{"https://admin.geekheal.net/edit/company/".$v->company->id}}">{{$v->company->name or ""}}</a></span>--}}
            {{--<span class="m_info">{{str_limit($v->company->des, $limit = 100, $end = '...')}}</span>--}}
            {{--</li>--}}
            {{--@endif--}}
            {{--@endforeach--}}
            {{--</ul>--}}
            <div class="job_flex">
                <div class="job_content">
                    <ul>
                        @foreach($jobt as $k=>$v)
                            <li class="m_card card" data-id="{{$v->id}}">
                                <span class="card_avatar">{{$v->topic}}</span>
                                @if(isset($v->headline)&&!empty($v->headline))
                                    @foreach($v->headline as $H=>$L)
                                        <div class="headline-list" data-id="{{$L["id"]}}">{{$L["title"]}}</div>
                                    @endforeach
                                @endif

                                <div class="job_bottom ">
                                    <div class="job_info">
                                        @if($v->fromuser ==  Auth::user()->id)
                                            <span>@if(!empty($v->user())){{$v->user()[0]->name}}@endif</span>
                                        @endif
                                    </div>
                                    <div class="job_opr">
                                        <select>
                                            @if($v->status ==1)
                                                <option value="0">待完成</option>
                                                <option value="1" selected>已完成</option>
                                            @else
                                                <option value="0">待完成</option>
                                                <option value="1">已完成</option>
                                            @endif
                                        </select>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <div class="editor job_editor ">
                    <textarea id="jobEditor" cols="20" rows="2" class="ckeditor" data-id=0></textarea>
                    <a class="job_topic_submit" href="javascript:void(0);">提交</a>
                </div>
            </div>

        </div>
        <div class="collect_panel" id="collect">
            <ul>
                @foreach($collects as $k=>$v)
                    <li class="card collect_card">
                        <span>{{$v->dailynews->excerpt}}</span>
                        <span><a href="{{url($v->dailynews->link)}}" target="_blank">{{$v->dailynews->title}}</a></span>
                        <span>发布于:{{$v->dailynews->created_at}}</span>
                        <span>收藏于:{{$v->created_at}}</span>
                        <span><a class="cancel_collect" href="javascript:void(0);"
                                 data-id="{{$v->dailynews->_id}}">取消收藏</a></span>
                    </li>
                @endforeach
            </ul>
        </div>
        <div class="memory_panel" id="memory">
            <div class="memory_menu">
                <span class="memory_menu_item">
                    <input placeholder="Search" class="search_input" id="k"/>
                </span>
                <span class="memory_menu_item">
                    <input placeholder="topic"/>
                </span>
                <span class="memory_menu_item">
                    <input placeholder="knowledge canned"/>
                </span>
                <span class="memory_menu_item share_source">
                    <input placeholder="from:@zhoulun"/>
                    <ul>
                        @foreach($user as $u=>$v)
                            <li data-id="{{$v->_id}}">{{$v->name}}</li>
                        @endforeach
                    </ul>

                </span>
                <span class="memory_menu_item"><input type="date"/></span>
                <span class="memory_menu_item">
                    <a id="new_note" href="javascript:void(0);" style="float: right">新建笔记</a>
                </span>
                <span class="memory_menu_item"><a id="share_note" href="javascript:void(0);"
                                                  style="float: right">分享笔记</a></span>
            </div>
            <div>
                <ul class="note_all">
                    {{--<li class="card memory_card" data-id="0">--}}
                    {{--<span class="m_tag">--}}
                    {{--<i>{{'私密'}}</i>--}}
                    {{--</span>--}}
                    {{--<span class="m_content"><p></p></span>--}}
                    {{--<span class="m_date"></span>--}}
                    {{--</li>--}}
                </ul>

                <div class="editor memory_editor">
                    {{--<div class="memory_opr">--}}
                    {{--<span><a>删除</a></span>--}}
                    {{--<span><a>设为共享</a></span>--}}
                    {{--</div>--}}
                    <textarea id='myEditor' edit_id=0></textarea>
                    <a class="m_submit" href="javascript:void(0);">提交</a>
                </div>
            </div>
        </div>
        <div class="clear"></div>
    </div>

    <script src="//cdn.bootcss.com/ckeditor/4.7.0/ckeditor.js"></script>
    <script src="//cdn.bootcss.com/ckeditor/4.7.0/config.js"></script>
    {{--<script src="//cdn.bootcss.com/wangeditor/2.1.20/js/wangEditor.min.js"></script>--}}
    <script>
        CKEDITOR.editorConfig = function( config ) {
            // Define changes to default configuration here.
            // For complete reference see:
            // http://docs.ckeditor.com/#!/api/CKEDITOR.config

            // The toolbar groups arrangement, optimized for two toolbar rows.
            config.toolbarGroups = [
                { name: 'clipboard',   groups: [ 'clipboard', 'undo' ] },
                { name: 'editing',     groups: [ 'find', 'selection', 'spellchecker' ] },
                { name: 'links' },
                { name: 'insert' },
                { name: 'forms' },
                { name: 'tools' },
                { name: 'document',	   groups: [ 'mode', 'document', 'doctools' ] },
                { name: 'others' },
                '/',
                { name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
                { name: 'paragraph',   groups: [ 'list', 'indent', 'blocks', 'align', 'bidi' ] },
                { name: 'styles' },
                { name: 'colors' },
                { name: 'about' }

            ];

            // Remove some buttons provided by the standard plugins, which are
            // not needed in the Standard(s) toolbar.
            config.removeButtons = 'Underline,Subscript,Superscript';

            // Set the most common block elements.
            config.format_tags = 'p;h1;h2;h3;pre';

            // Simplify the dialog windows.
            config.removeDialogTabs = 'image:advanced;link:advanced';
        };
        var jobe = CKEDITOR.replace('jobEditor');
        var editor = CKEDITOR.replace('myEditor');

        //        new wangEditor('myEditor');
        //        editor.create();
        //        var jobeditor = new wangEditor('jobEditor');
        //        jobeditor.create();
        $(function () {
            /**
             * create note
             */
            $("#new_note").click(function () {
                $("#myEditor").attr("edit_id", 0);
                $(".memory_card").css({"border": "1px solid #e2e2e2"});
                editor.setData("");
//                editor.$txt.html("<p><br></p>");
            });
            /**
             * tab style definition
             */
            $(".menu_label").click(function () {
                var did = $(this).attr("data-id");
                $(".mission_panel, .collect_panel ,.memory_panel").hide();
                $("#" + did).show();
                $(".menu_panel li").css({"background": "#f5f5f5", "border-top": "0"});
                $(this).css({"background": "#fff", "border-top": "2px solid #fe4b55"});
            });
            /**
             * note panel show and load data
             */
            $(".memory_label").click(function () {
                var page = 1;
                get_notes("/qs-admin/notes/show?page=" + page, $("#memory").find('.note_all'), page);
            });

            var set_interval = _debounce(search_notes, 500);
            var set_default = _debounce(get_notes, 1000);
            $('.search_input').on('input', function () {
                var param = {};
                param.k = $(this).val();
                if (param.k == "") {
                    var page = 1;
                    set_default("/qs-admin/notes/show?page=" + page, $("#memory").find('.note_all'), page);
                }
                set_interval("/qs-admin/notes/search", param, $("#memory").find('.note_all'));

            });
            /**
             * cancel the user collect
             */
            $(".cancel_collect").click(function () {

                var param = {};
                var cid = $(this).attr("data-id");
                param.cid = cid;
                var dom = $(this);
                if (confirm("确定移除?")) {
                    cancel_collect('/dailynews/cancel_collect', param, dom);
                }


            });
            /**
             * show the detail of the node
             */
            $("body").on("click", ".memory_card", function () {
                $(".memory_card").css({"border": "1px solid #e2e2e2"});
                $(this).css({"border": "2px solid #48b3f6"});
                var content = $(this).children(".m_content")[0].innerHTML;
                $("#myEditor").attr("edit_id", $(this).attr("data-id"));
//                editor.$txt.html(content);
                editor.setData(content);

            });
            /**
             * edit or create a note
             */
            $("body").on("click", ".m_submit", function () {
                var param = {};
                var _id = $("#myEditor").attr("edit_id");
                var content = editor.getData();
//                var content = editor.$txt.html();
                var dom = $("li[data-id='" + _id + "']").find(".m_content");
                param._id = _id;
                param.content = content;
                if (content.replace(/\s+/g, "") == "<p><br></p>" || content.replace(/\s+/g, "") == "") {
                    alert("空内容!");
                    return false;
                }
                if (_id == 0) {
                    update_notes("/qs-admin/notes/create", param, dom, "create");
                    return false;
                }
                update_notes("/qs-admin/notes/update", param, dom, "update");
            });

            $(".note_all").on("click", ".close", function () {
                if (confirm("确定要删除?")) {
                    var _id = $(this).parent().attr("data-id");
                    var param = {};
                    param._id = _id;
                    delete_note("/qs-admin/notes/delete", param, $(this), "");
                }
            });

            $("#myEditor").sisyphus();


            $(".headline-list").click(function () {
//                $(".job_editor").show();
//                $(".background").show();
                var tid = $(this).parents(".m_card").attr("data-id");
                var id = $(this).attr("data-id");
                var param = {};
                var dom = $(this);
                param.tid = tid;
                param.id = id;
                $('#jobEditor').attr("edit_id", tid);
                $('#jobEditor').attr("data-id", id);

                get_headline(param, "/qs-admin/topic/headline-get", dom);
            });
            $(".job_topic_submit").click(function () {
//                $(".job_editor").show();
//                alert($('#jobEditor').val());
//                return false;
                var tid = $('#jobEditor').attr("edit_id");
                var id = $('#jobEditor').attr("data-id");
                var content = jobe.getData();
                var param = {};
                var dom = $(this);
                param.tid = tid;
                param.id = id;
                param.content = content;
//                console.log(param);
                edit_headline(param, "/qs-admin/topic/headline-edit", dom);
            });
            $(".background").click(function () {
//                $(".job_editor").hide();
//                $(".background").hide();
            });

            $('.job_opr select').change(function () {
                var param = {};
                var _id = $(this).parent(".job_opr").parent(".job_bottom").parent("li").attr("data-id");
                var status = $(this).val();
                param._id = _id;
                param.status = status;
                job_status('/qs-admin/topic/status', param, $(this));
            });

            $("#job_topic_status").change(function () {
                var status = $(this).val();
                window.location.href = "/qs-admin/profile?type=topic&status=" + status;
            });

            $(".share_source input").click(function () {
                $('.share_source ul').show();
            });
            $("#memory").on("click", ".share_source ul li", function () {
                var param = {};
                param._id = $(this).attr("data-id");
                $(this).parent().hide();
                $(".share_source input").val("From:" + $(this).text());
                search_notes("/qs-admin/notes/author", param, $("#memory").find('.note_all'));

            });

            /**
             * load new page of notes
             * @type {*|jQuery}
             */
            var winH = $(window).height(); //页面可视区域高度
//            console.log(winH);
            $("#memory .note_all").scroll(function () {
                        var pageH = $(document.body).height();
                        var scrollT = $(window).scrollTop(); //滚动条top
                        var aa = (pageH - winH - scrollT) / winH;
                        if (aa < 0.02) {
//                            alert("loding");
                        }
                    }
            );
            /**
             * update or create notes ajax
             * @param url
             * @param param
             * @param dom
             * @param opr
             */
            function update_notes(url, param, dom, opr) {
                $.ajax(
                        {
                            method: "POST",
                            url: url,
                            dataType: "json",
                            data: param,
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function (data) {
                                if (data.error == 0 && opr == "update") {
                                    dom[0].innerHTML = param.content;
                                    dom.siblings(".m_date")[0].innerHTML = new Date();
                                } else if (data.error == 0 && opr == "create") {
                                    var html =
                                            '<li class="card memory_card" data-id=' + data.result.toString() + '>' +
                                            '<span class="m_tag"><i>default</i> <i>私密</i> <i>@</i></span>' +
                                            '<span class="m_content">' + param.content + '</span>' +
                                            '<span class="m_date">' + new Date() + '</span>' +
                                            '<span class="close">X</span>' +
                                            '</li>';
                                    $("#memory .note_all").prepend(html);

                                } else {
                                    console.log(data);
                                }
                            },
                            error: function (data) {
                                console.log(data);
                            }
                        }
                )
            }

            function delete_note(url, param, dom, opr) {
                $.ajax(
                        {
                            method: "POST",
                            url: url,
                            dataType: "json",
                            data: param,
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function (data) {
                                if (data.error == 0) {
                                    dom.parent().remove();

                                } else {
                                    console.log(data);
                                }
                            },
                            error: function (data) {
                                console.log(data);
                            }
                        }
                )
            }

            /**
             * get notes data by page and build dom
             * @param url
             * @param dom
             * @param page
             */
            function get_notes(url, dom, page) {
                $.ajax(
                        {
                            method: "GET",
                            url: url,
                            dataType: "json",
                            data: 1,
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function (data) {
                                if (data.error == 0) {
                                    var html = "";
                                    if (page == 1) {
                                        dom[0].innerHTML = "";
                                    }
                                    $.each(data.notes.data, function (i, e) {
                                        var url = '';
                                        if (e.url != "" && e.url != "undefined" && e.url != null) {
                                            url = ' <a class="ref" target="_blank" href="' + e.url + '">查看原文</a>';
                                        }
                                        ;
                                        html = html +
                                                '<li class="card memory_card" data-id=' + e._id + '>' +
                                                '<span class="m_tag">';
                                        $.each(e.tags, function (t, g) {
                                            html = html + '<i>' + g + '</i>';
                                        });

                                        html = html + '' + url + '</span>' +
                                                '<span class="m_content"><p>' + e.content + '</p></span>' +
                                                '<span class="m_date">' + e.updated_at + '</span>' +
                                                '<span class="close">X</span>' +
                                                '</li>';
                                    });
                                    dom.append(html);
                                } else {
                                    console.log(data);
                                }
                            },
                            error: function (data) {
                                console.log(data);
                            }
                        }
                )

            }

            function search_notes(url, param, dom) {
                $.ajax(
                        {
                            method: "POST",
                            url: url,
                            dataType: "json",
                            data: param,
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function (data) {
                                if (data.error == 0) {
                                    console.log(data);
                                    dom[0].innerHTML = "";
                                    var html = "";
                                    $.each(data.notes, function (i, e) {
                                        var url = '';
                                        if (e.url != "" && e.url != "undefined" && e.url != null) {
                                            url = ' <a class="ref" target="_blank" href="' + e.url + '">查看原文</a>';
                                        }
                                        ;

                                        html = html +
                                                '<li class="card memory_card" data-id=' + e._id + '>' +
                                                '<span class="m_tag">';
                                        $.each(e.tags, function (t, g) {
                                            html = html + '<i>' + g + '</i>';
                                        });
                                        html = html + '<i>' + url + '</i> </span> ' +
                                                '<span class="m_content"><p>' + e.content + '</p></span>' +
                                                '<span class="m_date">' + e.updated_at + '</span>' +
                                                '<span class="close">X</span>' +
                                                '</li>';
                                    });
                                    dom.append(html);
                                } else {
                                    console.log(data);
                                }
                            },
                            error: function (data) {
                                console.log(data);
                            }
                        }
                )

            }

            function get_headline(param, url, dom) {
                $.ajax(
                        {
                            method: "POST",
                            url: url,
                            dataType: "json",
                            data: param,
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function (data) {
                                if (data.error == 0) {
                                    jobe.setData(data.result);
//                                    jobeditor.$txt.html(data.result);
                                } else {
                                    console.log(data);
                                }
                            },
                            error: function (data) {
                                console.log(data);
                            }
                        }
                )
            }

            function edit_headline(param, url, dom) {
                $.ajax(
                        {
                            method: "POST",
                            url: url,
                            dataType: "json",
                            data: param,
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function (data) {
                                if (data.error == 0) {
//                                    $(".job_editor").hide();
//                                    $(".background").hide();
                                } else {
                                    console.log(data);
                                }
                            },
                            error: function (data) {
                                console.log(data);
                            }
                        }
                )
            }

            function cancel_collect(url, param, dom) {
                $.ajax(
                        {
                            method: "POST",
                            url: url,
                            dataType: "json",
                            data: param,
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function (data) {
                                if (data.error == 0) {
                                    dom.parent().parent(".collect_card").remove();
                                } else {
                                    console.log(data);
                                }
                            },
                            error: function (data) {
                                console.log(data);
                            }
                        }
                )
            }

            function job_status(url, param, dom) {
                $.ajax(
                        {
                            method: "POST",
                            url: url,
                            dataType: "json",
                            data: param,
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function (data) {
                                if (data.error == 0) {
                                    dom.parent().parent(".collect_card").remove();
                                } else {
                                    console.log(data);
                                }
                            },
                            error: function (data) {
                                console.log(data);
                            }
                        }
                )
            }


            function _debounce(func, wait, immediate) {
                var timeout, args, context, timestamp, result;

                var later = function () {
                    // 据上一次触发时间间隔
                    var last = Date.now() - timestamp;

                    // 上次被包装函数被调用时间间隔last小于设定时间间隔wait
                    if (last < wait && last > 0) {
                        timeout = setTimeout(later, wait - last);
                    } else {
                        timeout = null;
                        // 如果设定为immediate===true，因为开始边界已经调用过了此处无需调用
                        if (!immediate) {
                            result = func.apply(context, args);
                            if (!timeout) context = args = null;
                        }
                    }
                };

                return function () {
                    context = this;
                    args = arguments;
                    timestamp = Date.now();
                    var callNow = immediate && !timeout;
                    // 如果延时不存在，重新设定延时
                    if (!timeout) timeout = setTimeout(later, wait);
                    if (callNow) {
                        result = func.apply(context, args);
                        context = args = null;
                    }

                    return result;
                };
            };
        })
    </script>

@endsection