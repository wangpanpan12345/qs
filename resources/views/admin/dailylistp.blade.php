@extends('admin')

@section('title', 'qisu-Dailynews')
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

    a {
        margin: 0;
    }

    .list {
        margin: 20px 0;
        border: 1px solid #e2e2e2;
        padding: 10px;
        position: relative;
    }

    img {
        border-radius: 50%;
    }

    .title {
        display: inline-block;
        width: 60%;
        font-family: Baskerville-eText, Baskerville, Garamond, serif !important;
        font-size: 16px;
        font-weight: 600;
    }

    .title a, .source a, .company_relate a {
        color: #323232;
    }

    .source {
        display: inline-block;
        float: right;
        color: #b2b2b2;
        font-weight: 500;
    }

    .opr_group {
        margin: 10px auto;
    }

    .opr {
        border: 1px #c8c8c8 solid;
        border-radius: 5px;
        text-align: center;
        padding: 4px 6px;
    }

    .opr a {
        color: #888;
    }

    .opr_company, .opr_person {
        display: none;
    }

    .search_r, .search_r_p {
        margin: 0;
        padding: 0;
        position: absolute;
        list-style: none;
        width: 200px;
        z-index: 3;
    }

    .search_r li, .search_r_p li {
        border: 1px #e2e2e2 solid;
        background: #fff;
        cursor: pointer;
        line-height: 30px;
        border-top: 0;
        text-align: center;
    }

    .cp_input, .cp_input_p {
        outline: none;
    }

    input[type=button] {
        background: none;
        border: 1px solid #e2e2e2;
        color: #a2a2a2;
        outline: none;
    }

    .selector input {
        border: 1px solid #e2e2e2;
        color: #b2b2b2;
    }

    .selector input[type=submit] {
        background: none;
    }

    .pub_at {
        font-size: 12px;
        color: #d2d2d2;
    }

    .company_relate {
        display: block;
        margin-bottom: 3px;
        font-size: 12px;
        color: #d2d2d2;
        border: 1px solid #e2e2e2;
        border-radius: 3px;
        padding: 1px;
        text-shadow: 1px 1px 1px rgba(255, 255, 255, .22);
    }

    .select2-results__options {
        width: 300px;
    }

    .select2-selection {
        width: 300px;
        border: #e2e2e2;
        border-radius: 0;
    }

    .select2 {
        width: 300px;
    }

    .tags_in {
        position: relative;
    }

    .opr_tags {
        display: inline-block;
        display: none;;
    }

    .opr_excerpt {
        display: none;
    }

    .cp_textarea {
        width: 300px;
        height: 200px;
    }

    .source_show span {
        display: inline-block;
        border: 1px solid #e2e2e2;
        padding: 2px;
        margin: 3px;
        height: 20px;
    }

    .selector input {
        outline: none;
    }

    .labels {
        position: absolute;
        top: 0px;
        display: block;
        z-index: 2;
        float: left;
        /*border: 1px solid #e2e2e2;*/
        /*font-size: 12px;*/
        left: -1px;
        color: #fff;
        /*background: #fe4b55;*/
        /*height: 17px;*/
        /*line-height: 13px;*/
        /*padding: 1px 15px;*/
    }

    .latest {
        position: relative;
        top: -18px;
        display: block;
        z-index: 2;
        float: left;
        border: 1px solid #e2e2e2;
        font-size: 12px;
        /*left: -1px;*/
        color: #fff;
        background: #fe4b55;
        height: 17px;
        line-height: 13px;
        padding: 1px 15px;
    }

    .important {
        position: relative;
        top: -18px;
        display: block;
        z-index: 2;
        float: left;
        border: 1px solid #e2e2e2;
        font-size: 12px;
        /*left: 110px;*/
        color: #fff;
        background: #000;
        height: 17px;
        line-height: 13px;
        padding: 1px 15px;
    }

    .pubished {
        position: relative;
        top: -18px;
        display: block;
        z-index: 2;
        float: left;
        border: 1px solid #e2e2e2;
        font-size: 12px;
        /*left: 55px;*/
        color: #fff;
        background: #75ce66;
        height: 17px;
        line-height: 13px;
        padding: 1px 15px;
    }

    .add_dailynews {
        display: none;
    }

    .add_dailynews span {
        display: block;
    }

    .add_dailynews input {
        outline: none;
        width: 300px;
        margin: 10px 0;
    }

    .add_dailynews textarea {
        outline: none;
        width: 300px;
    }

    .search_form {
        display: inline-block;
    }

    .process_report {
        position: relative;
        border: 1px solid #e2e2e2;
        width: 100%;
        margin-bottom: 33px;
        padding: 0;

    }

    .remover_container {
        display: none;
        position: fixed;
        width: 300px;
        height: 200px;
        line-height: 70px;
        left: 50%;
        margin-left: -150px;
        top: 50%;
        margin-top: -50px;
        z-index: 10;
        border: 2px solid #48b3f6;
        background: #48b3f6;
        color: #fff;
        vertical-align: middle;
        text-align: center;
        border-radius: 10px;
    }

    .remover_container a {
        display: block;
        width: 100px;
        color: #fff;
        border: 2px solid #fff;
        margin: 0 90px;
        height: 40px;
        line-height: 40px;

    }

    .remover_container a:hover {
        color: #48b3f6;
        background: #fff;
    }

    .remover_container label {
        margin: 15px 10px;
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

    .deal {
        width: 0%;
        border: 1px solid #fe4b55;
        display: block;
        margin: 0;
        line-height: 10px;
        padding: 0;
        height: 20px;
        background: #fe4b55;
    }

    .delete {
        float: right;
    }

    @media (max-width: 748px) {
        .add_relation {
            display: none;
        }

        .delete a {
            display: none;
        }

        .delete {
            position: absolute;
            bottom: 0;
            right: 0;
            width: 20px;
            height: 20px;

        }

        .title {
            display: block;
            width: 100%;
            text-align: justify;
        }

        .source {
            float: none;
        }

        .company_relate {
            display: block;
        }
    }

</style>
@section('content')

    <div class="remover_container">
        <label><input name="no" type="radio" value="-2"/>内容不相关 </label>
        <label><input name="no" type="radio" value="-1"/>内容不重要 </label>
        <span class="close_s">X</span>
        <input type="text" style="display: none" id="delete_id_tmp"/>
        <a href="javascript:void(0);" id="delete_selector">提交</a>
    </div>

    <div class="selector">
        <form action="/dailynews/key" method="get" class="search_form">
            <input type="text" name="key" placeholder="keywords"/>
            <input class="selector_ok" type="submit" value="Search"/>
        </form>
        <select class="source_select">
            <option><span></span></option>
            <option><span>All</span></option>
            {{--<option><span>丁香园</span></option>--}}
            {{--<option><span>保险文化</span></option>--}}
            {{--<option><span>上海医药</span></option>--}}
            {{--<option><span>健康浙江</span></option>--}}
            {{--<option><span>中国政府网</span></option>--}}
            {{--<option><span>健识局</span></option>--}}
            {{--<option><span>健康点</span></option>--}}
            {{--<option><span>刘晔医法研究</span></option>--}}
            {{--<option><span>医药魔方数据</span></option>--}}
            {{--<option><span>医药经济报</span></option>--}}
            {{--<option><span>中国医药物资协会</span></option>--}}
            {{--<option><span>医药云端工作室</span></option>--}}
            {{--<option><span>看医界</span></option>--}}
            {{--<option><span>财政部</span></option>--}}
            {{--<option><span>国家发改委</span></option>--}}
            {{--<option><span>健康报医生频道</span></option>--}}
            {{--<option><span>医学界智库</span></option>--}}
            {{--<option><span>兰世亭</span></option>--}}
            {{--<option><span>E药经理人</span></option>--}}
            {{--<option><span>健康北京</span></option>--}}
            {{--<option><span>健康中国</span></option>--}}
            {{--<option><span>卫生经济研究</span></option>--}}
            {{--<option><span>方庄社区卫生服务中心</span></option>--}}
            {{--<option><span>世界卫生组织</span></option>--}}
            <option><span>新华网</span></option>
            <option><span>人民健康网</span></option>
            <option><span>卫计委</span></option>
            <option><span>卫计委政务公开</span></option>
            <option><span>卫计委医政医管局最新信息</span></option>
            <option><span>卫计委医政医管局政策文件</span></option>
            <option><span>卫计委医政医管局工作动态</span></option>
            <option><span>卫计委体制改革司最新消息</span></option>
            <option><span>卫计委体制改革司政策文件</span></option>
            <option><span>卫计委体制改革司工作动态</span></option>
            <option><span>卫计委基层卫生司政策文件</span></option>
            <option><span>卫计委基层卫生司最新消息</span></option>
            <option><span>卫计委基层卫生司工作动态</span></option>
            <option><span>深圳卫计委微资讯</span></option>
            <option><span>深圳卫计委统计数据</span></option>
            <option><span>深圳卫计委工作动态</span></option>
            <option><span>深圳卫计委工作简报</span></option>
            <option><span>深圳卫计委医疗监督</span></option>
            <option><span>深圳卫计委政策解读</span></option>
            <option><span>深圳卫计委发展规划</span></option>
            <option><span>深圳卫计委专项规划</span></option>
            <option><span>深圳卫计委年度规划</span></option>
            <option><span>上海卫计委</span></option>
            <option><span>中国网直播</span></option>
            <option><span>中国网国新办直播</span></option>
            <option><span>中国网吹风会直播</span></option>
            <option><span>中国网部委办直播</span></option>
            <option><span>中国网全国人大直播</span></option>
            <option><span>中国网会议活动直播</span></option>
            <option><span>食药监局</span></option>
            <option><span>中华医学会</span></option>
            <option><span>medscape</span></option>
            <option><span>ama_assn</span></option>
        </select>
        <input class="add_show" type="submit" value="添加一条"/>

        {{--<a style="float: right" href="/qs-admin/keynews">每日精选</a>--}}
        <a class="add_relation" style="float: right;margin-right: 10px" target="_blank"
           href="https://admin.geekheal.net/search">缺少关联?去创建</a>
    </div>

    <div class="add_dailynews">
        <span><input type="text" id="add_title" placeholder="原文标题"/></span>
        <span><input type="text" id="add_link" placeholder="原文链接"/></span>
        <span><input type="text" id="add_source" placeholder="来源"/></span>
        <span><textarea placeholder="简述" id="add_excerpt"></textarea></span>
        <span><input type="text" id="add_created" placeholder="发布时间(2017-01-01)"/></span>
        <select class="tags_select" multiple="multiple" style="width: 300px">

        </select>
        <span>
            <input type="text" id="add_company" placeholder="所属公司" class="cp_input"/>
        <ul class="search_r">

        </ul>
        </span>
        <span class="person_add_">
             <select class="person_select" multiple="multiple" style="width: 300px">

             </select>
        </span>
        <span><input type="submit" id="add_add" value="提交"/></span>
    </div>
    <div class="process_report">
        <span class="deal"></span>
    </div>
    {{--{{dd($dn)}}--}}
    <?php $total = 0;$deal = 0?>
    @foreach($dn->items() as $K => $V)

        <div class="list" data-id="{{$V->_id}}">
            <div class="labels">
                @if($V["created_at"]>Carbon\Carbon::today()->subHours(6))
                    <?php $total++;?>
                    @if(!empty($V["tags"]||!empty($V["company"])||$V["is_pub"]=='1'||$V["flag"]=='0'))
                        <?php $deal++;?>
                    @endif
                    <label class="latest">today</label>
                @endif
                @if(isset($V["priority"])&&$V["priority"]=='1')
                    <label class="important">{{$V["source"]}}</label>
                @endif
                @if(isset($V["is_pub"])&&$V["is_pub"]=='1')
                    <label class="pubished">{{"已发布"}}</label>
                @endif
            </div>
            <span class="title">
                <a style="margin: 0" target="_blank"
                   href="{{ URL::to($V["link"]) }}">{{$V["title"]}}</a>
            </span>
            <span class="pub_at">@if($V["pub_date"]!="")pub_at:{{$V["pub_date"]}}@endif</span>
            <span class="source">
                From:<a href="/dailynews_p/source/{{$V["source"]}}">{{$V["source"]}}</a>
            </span>
            @if($V["company"]!="")
                <span class="company_relate">Related:
                    @foreach($V["company"] as $c=>$n)
                        <a href="/qs-admin/detail/{{$n["_id"]}}">{{$n["name"]}}</a>
                    @endforeach
                </span>
            @endif
            @if($V["person"]!="")
                <span class="company_relate">Related person:
                    @foreach($V["person"] as $t=>$g)
                        <a href="#">{{$g["name"]}}</a>
                    @endforeach
                </span>
            @endif
            @if(!empty($V["tags"]))
                <span class="company_relate">tags:
                    @foreach($V["tags"] as $t=>$g)
                        <a href="/timeline/tag/{{$g}}">{{$g}}</a>
                    @endforeach
                </span>
            @endif


            <div class="opr_group">
                <span class="opr company"><a style="margin: 0" href="javascript:void(0);">关联公司</a></span>
                <span class="opr person"><a style="margin: 0" href="javascript:void(0);">关联人</a></span>
                <span class="opr tags"><a style="margin: 0" href="javascript:void(0);">打标签</a></span>
                <span class="opr pub_news"><a style="margin: 0" href="javascript:void(0);">列为精选</a></span>
                <span class="opr delete" data-id="{{$V->_id}}"><a style="margin: 0" href="javascript:void(0);">移入回收站</a></span>

                <span class="opr_company">
                    <select class="company_select" multiple="multiple" style="width: 300px">
                        @if(!empty($V->company))
                            @foreach($V->company as $kt=>$kv)
                                <option value="{{$kv["_id"]}}" selected="selected">{{$kv["name"]}}</option>
                            @endforeach
                        @endif
                    </select>
                    <input data-id="{{$V->_id}}" class="company_in" type="button" value="commit"/>
                </span>

                <span class="opr_person">

                    <select class="person_select" multiple="multiple" style="width: 300px">
                        @if(!empty($V->person))
                            {{--@foreach($V->person as $kt=>$kv)--}}
                            {{--<option value="{{$kv["_id"]}}" selected="selected">{{$kv["name"]}}</option>--}}
                            {{--@endforeach--}}
                        @endif
                    </select>
                    <input data-id="{{$V->_id}}" class="person_in" type="button" value="commit"/>
                </span>

                <span class="opr_tags">
                    <select class="tags_select" multiple="multiple" style="width: 300px">
                        @if(!empty($V->tags))
                            @foreach($V->tags as $kt=>$kv)
                                <option value="{{$kv}}" selected="selected">{{$kv}}</option>
                            @endforeach
                        @endif
                    </select>
                    <input data-id="{{$V->_id}}" class="tags_in" type="button" value="commit"/>
                </span>
                <span class="opr_excerpt">
                    <div class="excerpt_wap" style="display: inline-block">
                        <textarea class="cp_textarea">{{$V["excerpt"]}}</textarea>
                    </div>
                    <input data-id="{{$V->_id}}" class="excerpt_in" type="button" value="commit"/>
                </span>
            </div>

        </div>

    @endforeach
    {{$dn->links()}}

    @if($total==0)
        {{$total = 1}}
    @endif

    <script>
        $(function () {
            $(".deal").css({"width": "<?php echo ($deal*100/$total)."%"; ?>"});
            $(".deal").attr({"title": "<?php echo $deal; ?>"});
            $(".company").click(function () {
                var company = $(this).siblings(".opr_company");
                company.toggle();
//                console.log($(this).siblings(".opr_company"));
            });
            $(".person").click(function () {
                var person = $(this).siblings(".opr_person");
                person.toggle();
//                console.log($(this).siblings(".opr_company"));
            });
            $(".tags").click(function () {
                var tags = $(this).siblings(".opr_tags");
                tags.toggle();
            });
            $(".pub_news").click(function () {
                var excerpt = $(this).siblings(".opr_excerpt");
                excerpt.toggle();
            });
            $(".add_show").click(function () {
                $(".add_dailynews").toggle();
            });
            $(".close_s").click(function () {
                $(this).parent().hide();
            });
            $(".source_select").change(function () {
                var source = $(this).children('option:selected').text();
                if (source == "All") {
                    window.location.href = "/dailynews_p";
                } else {
                    window.location.href = "/dailynews_p/source/" + source;
                }

            });

            $("#add_add").click(function () {
                var param = {};
                param.title = $("#add_title").val();
                param.link = $("#add_link").val();
                var person = $(this).parent().siblings(".person_add_").children(".person_select").children('option');
                var persons = [];
                $.each(person, function (n, value) {
                    var data = {};
                    data["_id"] = $(value).attr("value");
                    data["name"] = $(value).text();
                    persons.push(data);
                });
                var company = $(this).parent().siblings(".company_add_").children(".company_select").children('option');
                var companys = [];
                var _id = $(this).attr("data-id");
                $.each(company, function (n, value) {
                    var data = {};
                    data["_id"] = $(value).attr("value");
                    data["name"] = $(value).text();
                    companys.push(data);
                });
                param.company = companys;
                param.person = persons;
                param.source = $("#add_source").val();
                param.excerpt = $("#add_excerpt").val();
                param.created_at = $("#add_created").val();
                param.flag = 2;
                var name = $(this).parent().siblings(".select2").find(".select2-selection__choice");
                var tags = [];
                $.each(name, function (n, value) {
                    tags.push($(value).attr("title"));

                });
                param.tags = tags;
                if (param.created_at == "" || param.link == "" || param.excerpt == "") {
                    alert("时间/链接/简述不为空!");
                    return false;
                }
                console.log(param);
//                return false;
                add_dailynews("/dailynews/add", param, $(this).parent().parent());
//                console.log(param);
            });
            var set_interval = _debounce(search_company, 1000);
            $('.cp_input').on('input', function () {
                var param = {};
                param.name = $(this).val();
                if (param.name == "")
                    return false;
                set_interval("/api/company/name/dy", param, $(this));

            });
            var set_interval_p = _debounce(search_person, 1000);
            $('.cp_input_p').on('input', function () {
                var param = {};
                param.name = $(this).val();
                if (param.name == "")
                    return false;
                set_interval_p("/api/person/name/dy", param, $(this));

            });
            $(".search_r").on("click", '.result_item', function () {
                var v = $(this).text();
                $(this).parent(".search_r").siblings(".cp_input").val(v);
                $(this).parent(".search_r").hide();
            });
            $(".search_r_p").on("click", '.result_item', function () {
                var v = $(this).text();
                $(this).parent(".search_r_p").siblings(".cp_input_p").val(v);
                $(this).parent(".search_r_p").hide();
            });
            $(".company_in").click(function () {
                var param = {};
                var company = $(this).siblings(".company_select").children('option');
                var tags = [];
                var _id = $(this).attr("data-id");
                $.each(company, function (n, value) {
                    var data = {};
                    data["_id"] = $(value).attr("value");
                    data["name"] = $(value).text();
                    tags.push(data);
                });
                param.company = tags;
                param._id = _id;
                console.log(param);
//                return false;
                update_company("/api/dailynews/company", param, $(this));
            });
            $(".person_in").click(function () {
                var param = {};
                var person = $(this).siblings(".person_select").children('option');
                var tags = [];
                var _id = $(this).attr("data-id");
                $.each(person, function (n, value) {
                    var data = {};
                    data["_id"] = $(value).attr("value");
                    data["name"] = $(value).text();
                    tags.push(data);
                });
                param.person = tags;
                param._id = _id;
                console.log(param.person);
                update_person("/api/dailynews/person", param, $(this));
                return false;
            });

            $(".excerpt_in").click(function () {
                var param = {};
                var name = $(this).siblings(".excerpt_wap").children(".cp_textarea").val();
                var _id = $(this).attr("data-id");
                param.excerpt = name;
                param._id = _id;

                update_excerpt("/dailynews/excerpt", param, $(this));
            });
            $(".opr_tags").on("click", ".tags_in", function () {
                var param = {};
                var name = $(this).siblings(".select2").find(".select2-selection__choice");
                var _id = $(this).attr("data-id");
                var tags = [];
                $.each(name, function (n, value) {
                    tags.push($(value).attr("title"));

                });
                param.tags = tags;
                param._id = _id;

                update_tags("/api/dailynews/tags/update", param, $(this));
            });

            $(".delete").click(function () {
                var _id = $(this).attr("data-id");
                $("#delete_id_tmp").val(_id);
                $(".remover_container").show();
            });
            $("#delete_selector").click(function () {
                var d_flag = $("input[name='no']:checked").val();
                var _id = $("#delete_id_tmp").val();
                if (d_flag == "undefined" || typeof (d_flag) == "undefined") {
                    alert("请选择移除原因!");
                    return false;
                }
                var param = {};
                param._id = _id;
                param.flag = d_flag;
                delete_this("/dailynews/delete", param, $(this));

            });


            function search_company(url, param, dom) {
                $.ajax({
                    method: "POST",
                    url: url,
                    dataType: "json",
                    data: param,
                    success: function (data) {
                        if (data.error == 0) {
                            var html = "";
                            $.each(data.result, function (n, value) {
                                html += "<li class='result_item'>" + value['name'] + "</li>";
                            });
                            dom.siblings(".search_r").html(html);
//                            console.log(data);
                        } else {
                            console.log(data);
                        }
                    },
                    error: function (data) {
                        console.log(data);
                    }
                })

            }

            function search_person(url, param, dom) {
                $.ajax({
                    method: "POST",
                    url: url,
                    dataType: "json",
                    data: param,
                    success: function (data) {
                        if (data.error == 0) {
                            var html = "";
                            $.each(data.result, function (n, value) {
                                html += "<li class='result_item'>" + value['name'] + "</li>";
                            });
                            dom.siblings(".search_r_p").html(html);
//                            console.log(data);
                        } else {
                            console.log(data);
                        }
                    },
                    error: function (data) {
                        console.log(data);
                    }
                })

            }

            function update_company(url, param, dom) {
                $.ajax({
                    method: "POST",
                    url: url,
                    dataType: "json",
                    data: param,

                    success: function (data) {
                        if (data.error == 0) {
                            alert("success update!");
                            dom.parent(".opr_company").hide();
                        } else {
                            console.log(data);
                        }
                    },
                    error: function (data) {
                        console.log(data);
                    }
                })

            }

            function update_person(url, param, dom) {
                $.ajax({
                    method: "POST",
                    url: url,
                    dataType: "json",
                    data: param,

                    success: function (data) {
                        if (data.error == 0) {
                            alert("success update!");
                            dom.parent(".opr_person").hide();
                            location.reload();
                        } else {
                            console.log(data);
                        }
                    },
                    error: function (data) {
                        console.log(data);
                    }
                })

            }

            function update_excerpt(url, param, dom) {
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
//                            console.log(data);
                            alert("success update!");
                            dom.parent(".opr_excerpt").parent(".opr_group").siblings(".labels").append('<label class="pubished">"已发布"</label>');
                            dom.parent(".opr_excerpt").hide();
//                            window.location.reload();
                        } else {
                            console.log(data);
                        }
                    },
                    error: function (data) {
                        console.log(data);
                    }
                })

            }

            function delete_this(url, param, dom) {
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
                            $(".list[data-id='" + param._id + "']").remove();
                            dom.parent().hide();
//                            dom.parent().parent().remove();
                        } else {
                            console.log(data);
                            alert("移除失败!")
                            console.log(data);
                        }
                    },
                    error: function (data) {
                        alert("网络错误!")
                        console.log(data);
                    }
                })

            }


            function add_dailynews(url, param, dom) {
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
//                            console.log(data);
                            alert("success created!");
                            dom.find("input").val("");
                            dom.find("textarea").text("");
//                            dom.parent(".opr_excerpt").hide();
                        } else if (data.error == 6) {
                            alert("existed!");
                            dom.find("input").val("");
                            dom.find("textarea").val("");
                        } else {
                            console.log(data);
                        }
                    },
                    error: function (data) {
                        console.log(data);
                    }
                })
            }

            function update_tags(url, param, dom) {
                $.ajax({
                    method: "POST",
                    url: url,
                    dataType: "json",
                    data: param,
                    success: function (data) {
                        if (data.error == 0) {
                            alert("success update!");
                            dom.parent(".opr_tags").hide();
                        } else {
                            console.log(data);
                        }
                    },
                    error: function (data) {
                        console.log(data);
                    }
                })

            }

            function formatRepo(repo) {
                if (repo.loading) return repo.text;

                markup += "<div class='select2-result-repository__statistics'>" +
                        "<div class='select2-result-repository__forks'><i class='fa fa-flash'></i> " + repo.id + " Forks</div>" +
                        "<div class='select2-result-repository__stargazers'><i class='fa fa-star'></i> " + repo.text + " </div>" +
                        "</div>" +
                        "</div></div>";

                return markup;
            }

            function formatRepoSelection(repo) {
                return repo.id || repo.text;
            }

            $(".tags_select").select2({
                placeholder: 'input tags',
                ajax: {
                    url: "/api/dailynews/tags/",
                    cache: true,
                    delay: 250,
                    processResults: function (data) {
//                        console.log(data);
                        var rs = [];
                        $.each(data.result, function (n, value) {
                            var param = {};
                            param.id = value["_id"];
                            param.text = value["name"];
                            rs.push(param);
                        });
//                        console.log(rs);
                        return {results: rs}
                    },
//                    escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
//                    minimumInputLength: 1,
//                    templateResult: formatRepo, // omitted for brevity, see the source of this page
//                    templateSelection: formatRepoSelection
                }
            });

            $(".person_select").select2({
                placeholder: 'input persons',
                ajax: {
                    url: "/api/person/name/dy",
                    cache: true,
                    delay: 250,
                    processResults: function (data) {
//                        console.log(data);
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
            $(".company_select").select2({
                placeholder: 'input companies',
                ajax: {
                    url: "/api/company/name/dy",
                    cache: true,
                    delay: 250,
                    processResults: function (data) {
//                        console.log(data);
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
        });
    </script>
    <script type="text/javascript">
        $(".tags_select").select2();
    </script>


@endsection