@extends('admin')

@section('title', 'qisu')
<meta name="csrf-token" content="{{ csrf_token() }}">
@section('sidebar')
    @parent
@endsection
<script src="//cdn.bootcss.com/jquery/3.1.1/jquery.min.js"></script>
<script src="/js/modernizr.js"></script> <!-- Modernizr -->
<link href="//cdn.bootcss.com/wangeditor/2.1.20/css/wangEditor.min.css" rel="stylesheet">
<style>
    body {
        margin: 0 !important;
        padding: 0 !important;
        font-family: "Pingfang SC", "Microsoft YaHei", Helvetica, STHeiti, Verdana, Arial, Tahoma, sans-serif !important;
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
        margin-top: 40px;
    }

    .m_info {
        padding: 5px 20px;
        font-size: 13px;
        text-align: justify;
        letter-spacing: 1px;
    }

    .card {
        list-style: none;
        margin: 10px 0;
        padding: 0;
        border: 1px solid #e2e2e2;
        width: 23%;
        margin-right: 1%;
        height: 250px;
        float: left;
        vertical-align: middle;

    }

    .collect_card {
        width: 90%;
        height: auto;
    }

    .card_avatar {

    }

    .card_avatar img {
        width: 80px;
        height: 80px;
        border-radius: 50%;
    }

    .m_card span {
        display: block;
        margin-top: 10px;
        text-align: center;
    }

    .collect_card span {
        display: block;
        margin: 10px 0;
        padding: 0 10px;
        text-align: justify;
    }

    #memory ul {
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
        max-height: 250px;
        overflow: hidden;
    }

    .memory_menu {
        margin: 20px 0;
    }

    .memory_menu span {
        width: 300px;
    }

    .memory_card span {
        display: block;
        margin: 10px;
    }

    .memory_editor {
        width: 58%;
        float: right;
        margin-top: 10px;
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
    }

    .m_tag i {
        background: #48b3f6;
        margin-bottom: 3px;
        padding: 3px 15px;
        color: #fff;
        border-radius: 3px;
        font-size: 12px;
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
        height: 80% !important;
        max-height: 1000px;
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

    #new_note {
        padding: 5px 20px;
        background: #48b3f6;
        color: #fff;
        border-radius: 5px;
    }

    @media (max-width: 748px) {

    }
</style>
@section('content')
    {{--{{dd($notes)}}--}}
    <div class="content_container">
        <div class="menu_panel">
            <ul>
                <li class="menu_label" data-id="mission">任务</li>
                <li class="menu_label" data-id="collect">收藏</li>
                <li class="menu_label memory_label" data-id="memory">笔记</li>
            </ul>
        </div>
        <div class="mission_panel" id="mission">
            <h4>本周主题</h4>
            <span class="progress"><span></span></span>
            <ul>
                @foreach($jobs as $k=>$v)
                    <li class="m_card card">
                        <span class="card_avatar"><img src="{{$v->company->avatar}}"></span>
                        <span><a href="{{"https://admin.geekheal.net/edit/company/".$v->company->id}}">{{$v->company->name}}</a></span>
                        <span class="m_info">{{str_limit($v->company->des, $limit = 100, $end = '...')}}</span>
                    </li>
                @endforeach
            </ul>
        </div>
        <div class="collect_panel" id="collect">
            <ul>
                @foreach($collects as $k=>$v)
                    <li class="card collect_card">
                        <span>{{$v->dailynews->excerpt}}</span>
                        <span><a href="{{url($v->dailynews->link)}}" target="_blank">{{$v->dailynews->title}}</a></span>
                        <span>发布于:{{$v->dailynews->created_at}}</span>
                        <span>收藏于:{{$v->created_at}}</span>
                    </li>
                @endforeach
            </ul>
        </div>
        <div class="memory_panel" id="memory">
            <div class="memory_menu">
                <span><input placeholder="Search" class="search_input" id="k"/></span>
                <span><input placeholder="topic"/></span>
                <span><input placeholder="knowledge canned"/></span>
                <span><input placeholder="from:@zhoulun"/></span>
                <span><input type="date"/></span>
                <span><a id="new_note" href="javascript:void(0);" style="float: right">新建笔记</a></span>
            </div>
            <div>
                <ul>
                    {{--<li class="card memory_card" data-id="0">--}}
                    {{--<span class="m_tag">--}}
                    {{--<i>{{'私密'}}</i>--}}
                    {{--</span>--}}
                    {{--<span class="m_content"><p></p></span>--}}
                    {{--<span class="m_date"></span>--}}
                    {{--</li>--}}
                </ul>

                <div class="editor memory_editor">
                    <textarea id='myEditor' edit_id=0></textarea>
                    <a class="m_submit" href="javascript:void(0);">提交</a>
                </div>
            </div>
        </div>
        <div class="clear"></div>
    </div>
    <script src="//cdn.bootcss.com/wangeditor/2.1.20/js/wangEditor.min.js"></script>
    <script>
        var editor = new wangEditor('myEditor');
        editor.create();
        $(function () {
            /**
             * create note
             */
            $("#new_note").click(function () {
                $("#myEditor").attr("edit_id", 0);
                $(".memory_card").css({"border": "1px solid #e2e2e2"});
                editor.$txt.html("<p><br></p>");
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
                get_notes("/qs-admin/notes/show?page=" + page, $("#memory").find('ul'), page);
            });

            var set_interval = _debounce(search_notes, 1000);
            var set_default = _debounce(get_notes, 1000);
            $('.search_input').on('input', function () {
                var param = {};
                param.k = $(this).val();
                if (param.k == "") {
                    var page = 1;
                    set_default("/qs-admin/notes/show?page=" + page, $("#memory").find('ul'), page);
                }
                set_interval("/qs-admin/notes/search", param, $("#memory").find('ul'));

            });
            /**
             * show the detail of the node
             */
            $("body").on("click", ".memory_card", function () {
                $(".memory_card").css({"border": "1px solid #e2e2e2"});
                $(this).css({"border": "2px solid #48b3f6"});
                var content = $(this).children(".m_content")[0].innerHTML;
                $("#myEditor").attr("edit_id", $(this).attr("data-id"));
                editor.$txt.html(content);
            });
            /**
             * edit or create a note
             */
            $("body").on("click", ".m_submit", function () {
                var param = {};
                var _id = $("#myEditor").attr("edit_id");
                var content = editor.$txt.html();
                var dom = $("li[data-id='" + _id + "']").find(".m_content");
                param._id = _id;
                param.content = content;
                if (content.replace(/\s+/g, "") == "<p><br></p>") {
                    alert("空内容!");
                    return false;
                }
                if (_id == 0) {
                    update_notes("/qs-admin/notes/create", param, dom, "create");
                    return false;
                }
                update_notes("/qs-admin/notes/update", param, dom, "update");
            });
            /**
             * load new page of notes
             * @type {*|jQuery}
             */
            var winH = $(window).height(); //页面可视区域高度
            console.log(winH);
            $("#memory ul").scroll(function () {
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
                                            '</li>';
                                    $("#memory ul").prepend(html);

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
                                        html = html +
                                                '<li class="card memory_card" data-id=' + e._id + '>' +
                                                '<span class="m_tag">';
                                        $.each(e.tags, function (t, g) {
                                            html = html + '<i>' + g + '</i>';
                                        });
                                        html = html + ' <i>私密</i> <i>@</i></span>' +
                                                '<span class="m_content"><p>' + e.content + '</p></span>' +
                                                '<span class="m_date">' + e.updated_at + '</span>' +
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

                                        html = html +
                                                '<li class="card memory_card" data-id=' + e._id + '>' +
                                                '<span class="m_tag">';
                                        $.each(e.tags, function (t, g) {
                                            html = html + '<i>' + g + '</i>';
                                        });
                                        html = html + ' <i>私密</i> <i>@</i></span>' +
                                                '<span class="m_content"><p>' + e.content + '</p></span>' +
                                                '<span class="m_date">' + e.updated_at + '</span>' +
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