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

    .latest {
        position: absolute;
        top: -18px;
        display: block;
        z-index: 2;
        float: left;
        border: 1px solid #e2e2e2;
        font-size: 12px;
        left: -1px;
        color: #fff;
        background: #fe4b55;
        height: 17px;
        line-height: 13px;
        padding: 1px 15px;
    }

    .important {
        position: absolute;
        top: -18px;
        display: block;
        z-index: 2;
        float: left;
        border: 1px solid #e2e2e2;
        font-size: 12px;
        left: 55px;
        color: #fff;
        background: #000;
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

    @media (max-width: 748px) {
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
    {{--<h2>DailyNews</h2>--}}
    {{--<span style="text-align: right;width: 100%;display: block">Last updated at {{$dn->items()[0]->updated_at}}</span>--}}
    {{--<div class="source_show">Source from :--}}

    {{--</div>--}}
    <div class="selector">
        <form action="/dailynews/key" method="get" class="search_form">
            <input type="text" name="key" placeholder="keywords"/>
            <input class="selector_ok" type="submit" value="Search"/>
        </form>
        <select class="source_select">
            <option><span></span></option>
            <option><span>All</span></option>
            <option><span>newswise</span></option>
            <option><span>fiercebiotech</span></option>
            <option><span>fiercepharma</span></option>
            <option><span>fiercemedicaldevices</span></option>
            <option><span>statnews</span></option>
            <option><span>genengnews</span></option>
            <option><span>genomeweb</span></option>
            <option><span>spectrum</span></option>
            <option><span>technologyreview</span></option>
            <option><span>nytimes</span></option>
            <option><span>theatlantic</span></option>
            <option><span>wsj</span></option>
            <option><span>vox</span></option>
            <option><span>scientificamerican</span></option>
            <option><span>the_scientist</span></option>
            <option><span>sciencenews_gen</span></option>
            <option><span>theverge</span></option>
            <option><span>livescience</span></option>
            <option><span>asianscientist_pharma</span></option>
            <option><span>mdtmag</span></option>
            <option><span>sciencealert</span></option>
            <option><span>npr</span></option>
            <option><span>popsci</span></option>
            <option><span>eurekalert</span></option>
            <option><span>sciencedaily</span></option>
            <option><span>techcrunch</span></option>
            <option><span>mobihealthnews</span></option>
            <option><span>fastcompany</span></option>
            <option><span>nature_news</span></option>
            <option><span>nature_biological</span></option>
            <option><span>nature_nbt_research</span></option>
            <option><span>nature_nbt_news</span></option>
            <option><span>nature_ng_research</span></option>
            <option><span>nature_ng_news</span></option>
            <option><span>nature_ni_research</span></option>
            <option><span>nature_ni_news</span></option>
            <option><span>nature_nm_research</span></option>
            <option><span>nature_nm_news</span></option>
            <option><span>nature_nmicrobiol_news</span></option>
            <option><span>nature_nmicrobiol_research</span></option>
            <option><span>nature_nnano_news</span></option>
            <option><span>nature_nnano_research</span></option>
            <option><span>nature_neuro_research</span></option>
            <option><span>nature_neuro_news</span></option>
            <option><span>cell</span></option>
            <option><span>sciencemag_news</span></option>
            <option><span>sciencemag_advances</span></option>
            <option><span>sciencemag_robotics</span></option>
            <option><span>sciencemag_stm</span></option>
            <option><span>nejm</span></option>
            <option><span>thelancet_news</span></option>
            <option><span>jamanetwork</span></option>
            <option><span>elifesciences</span></option>
            <option><span>bmj_news</span></option>
            <option><span>bmj_research</span></option>
            <option><span>bmj_research_news</span></option>
            <option><span>thelancet_research</span></option>
            <option><span>singularityhub_health</span></option>
            <option><span>singularityhub_science</span></option>
            <option><span>singularityhub_technology</span></option>
            <option><span>deepmind</span></option>

        </select>
        <input class="add_show" type="submit" value="添加一条"/>

        <a style="float: right" href="/qs-admin/keynews">每日精选</a>
        <a style="float: right;margin-right: 10px" target="_blank"
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
        <span>
             <input class="cp_input_p" id="add_person" placeholder="input person" type="text"/>
             <ul class="search_r_p">
             </ul>
        </span>
        <span><input type="submit" id="add_add" value="提交"/></span>
    </div>
    {{--{{dd($dn)}}--}}
    {{--    {{dd($dn->items())}}--}}
    @foreach($dn->items() as $K => $V)

        <div class="list" data-id="{{$V->_id}}">
            @if($V["created_at"]>Carbon\Carbon::today()->subHours(6))
                <label class="latest">today</label>
            @endif
            @if(isset($V["priority"])&&$V["priority"]=='1')
                <label class="important">{{$V["source"]}}</label>
            @endif
            <span class="title">
                <a style="margin: 0" target="_blank"
                   href="{{ URL::to($V["link"]) }}">{{$V["title"]}}</a>
            </span>
            <span class="pub_at">@if($V["pub_date"]!="")pub_at:{{$V["pub_date"]}}@endif</span>
            <span class="source">
                From:<a href="/dailynews/source/{{$V["source"]}}">{{$V["source"]}}</a>
            </span>
            @if($V["company"]!="")
                <span class="company_relate">Related:{{$V["company"]}}</span>
            @endif
            @if($V["person"]!="")
                <span class="company_relate">Related:{{$V["person"]}}</span>
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
                <span class="opr pub_news"><a style="margin: 0" href="javascript:void(0);">生成快讯</a></span>

                <span class="opr_company">
                    <div class="company_wap" style="display: inline-block">
                        <input class="cp_input" type="text" placeholder="input company" value="{{$V["company"]}}"/>
                        <ul class="search_r">

                        </ul>
                    </div>
                    <input data-id="{{$V->_id}}" class="company_in" type="button" value="commit"/>
                </span>
                <span class="opr_person">
                    <div class="person_wap" style="display: inline-block">
                        <input class="cp_input_p" placeholder="input person" type="text" value="{{$V["person"]}}"/>
                        <ul class="search_r_p">
                        </ul>
                    </div>
                    <input data-id="{{$V->_id}}" class="person_in" type="button" value="commit"/>
                </span>

                <span class="opr_tags">
                    <select class="tags_select" multiple="multiple" style="width: 300px">

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

    <script>
        $(function () {
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
            $(".source_select").change(function () {
                var source = $(this).children('option:selected').text();
                if (source == "All") {
                    window.location.href = "/dailynews";
                } else {
                    window.location.href = "/dailynews/source/" + source;
                }

            });

            $("#add_add").click(function () {
                var param = {};
                param.title = $("#add_title").val();
                param.link = $("#add_link").val();
                param.company = $("#add_company").val();
                param.person = $("#add_person").val();
                param.source = $("#add_source").val();
                param.excerpt = $("#add_excerpt").val();
                param.created_at = $("#add_created").val();
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
                var name = $(this).siblings(".company_wap").children(".cp_input").val();
                var _id = $(this).attr("data-id");
                param.company = name;
                param._id = _id;
//                if(param.company=="")
//                    return false;
                update_company("/api/dailynews/company", param, $(this));
            });
            $(".person_in").click(function () {
                var param = {};
                var name = $(this).siblings(".person_wap").children(".cp_input_p").val();
                var _id = $(this).attr("data-id");
                param.person = name;
                param._id = _id;
//                if(param.company=="")
//                    return false;
                update_person("/api/dailynews/person", param, $(this));
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
                            dom.parent(".opr_excerpt").hide();
                        } else {
                            console.log(data);
                        }
                    },
                    error: function (data) {
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