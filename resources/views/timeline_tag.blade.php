@extends('admin')
<meta name="csrf-token" content="{{ csrf_token() }}">
@section('title', '每日精选')

@section('sidebar')
    @parent
@endsection

{{--<link href="https://fonts.css.network/css?family=Raleway:100,600" rel="stylesheet" type="text/css">--}}
{{--<link rel="stylesheet" href="/css/reset.css"> <!-- CSS reset -->--}}
<link rel="stylesheet" href="/css/style.css"> <!-- Resource style -->
<script src="/js/modernizr.js"></script> <!-- Modernizr -->
<link href="//cdn.bootcss.com/wangeditor/2.1.20/css/wangEditor.min.css" rel="stylesheet">

<style>
    body {
        font-family: Raleway, "Pingfang SC", "Microsoft YaHei", Helvetica, STHeiti, Verdana, Arial, Tahoma, sans-serif !important;
    }

    .type_label {
        display: block;
        background: #48b3f6;
        color: #fff;
        font-size: 22px;
        margin: 30px 0;
        height: 45px;
        line-height: 45px;
        padding: 0 10px;
        font-weight: 600;
    }

    .type_label_border {
        border-left-width: 10px;
        border-left-color: #0299f8;
        border-top-left-radius: 10px;
        border-bottom-left-radius: 10px;
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

    .source {
        display: block;
        text-align: right;
        color: #d2d2d2;
    }

    .source a {
        color: #323232;
    }

    .r_c_list img {
        width: auto;
        height: 50px;
        border-radius: 50%;
    }

    .r_c_list {
        margin: 0 10px;
    }

    .c_des {

    }

    body {
        height: 100%;
    }

    .knowledge_back {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: #000;
        opacity: .5;
        z-index: 9;
        display: none;
    }

    .knowledge_container {
        position: relative;
        width: 60%;
        height: 60%;
        position: fixed;
        top: 50%;
        margin-top: -15%;
        left: 50%;
        margin-left: -30%;
        z-index: 20;
        padding: 30px;
        border: 1px solid #e2e2e2;
        background: #eceff0;
        border-radius: 10px;
        display: none;
    }

    .knowledge_list {
        margin: 10px 0;
    }

    #knowledge_content {
        height: 60% !important;
    }

    .knowledge_add {
        position: relative;
        padding: 0 10px;
        font-size: 18px;
        cursor: pointer;
        background: #89cafc;
        color: #fff;
        width: 150px;
        left: 50%;
        margin-left: -75px;
        text-align: center;
        border-radius: 5px;
        font-weight: 500;
    }

    .close_k {
        position: absolute;
        top: 0;
        right: 0;
        width: 20px;
        height: 20px;
        background: #fe4b55;
        color: #fff;
        text-align: center;
        line-height: 20px;
        font-size: 18px;
        border-radius: 10px;
        margin: 2px;
        cursor: pointer;

    }

    .knowledge_submit {
        width: 100px;
        background: #89cafc;
        border: 1px solid #89cafc;
        border-radius: 2px;
        color: #fff;
        outline: none;
    }

    .knowledge_list select {
        width: 100px;
        outline: none;
    }

    .knowledge_type {
        position: absolute !important;
        top: -10px !important;
        left: 70px !important;
        width: 30px !important;
        height: auto !important;
        border-radius: 0 !important;
    }

    .source {
        position: relative;
        display: block;
        background: #89cafc;
        width: 100px;
        height: 30px;
        line-height: 30px;
        color: #fff;
        font-size: 16px;
        text-align: center;
        font-weight: 600;
        border-radius: 5px;
        left: 100%;
        margin-left: -100px;
        margin-top: 10px;
        cursor: pointer;
    }

    .source_i {
        display: block;
        width: 100%;
        text-align: right;
        color: #e2e2e2;

    }

    .knowledge_edit, .knowledge_delete {
        background: #f34b55;
    }
    .content p{
        line-height: 28px;
        letter-spacing: 1px;
        padding: 3px 20px;
    }

    .morecon {
        cursor: pointer;
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

        .knowledge_container {
            width: 100%;
            height: 80%;
            margin-left: -50%;
            margin-top: -50%;
            padding: 30px 0;

        }
    }

</style>
@section('content')

            {{--{{dd($tag)}}--}}
    {{--    {{Carbon\Carbon::today()->format("Y-m-d")}}--}}
    <?php
    $person = [];
    $wuxu = ["合作", "科研", "融资", "并购", "临床", "I期", "II期", "III期", "FDA", "CE", "CFDA", "预临床", "IPO"];
    ?>
    <div class="knowledge_container">
        <div id="knowledge_content">
        </div>
        <div class="knowledge_list">
            <select>
                @if(!empty($knowledge_tag))
                    @foreach($knowledge_tag as $k=>$v)
                        <option id="{{$v["_id"]}}">{{$v["name"]}}</option>
                    @endforeach
                @endif

            </select>
        </div>
        <div class="knowledge_list" style="text-align: right">
            <input tags="{{$tag->name}}" class="knowledge_submit" type="submit" value="提交"/>
            <input style="display: none" class="edit_flag" _id="0"/>
        </div>

        <div class="close_k">
            X
        </div>
    </div>
    <div class="knowledge_back"></div>

    <div class=""><h2 style="text-align: center">{{$tag->name}}</h2></div>
    <div class="knowledge_add">添加知识罐头</div>
    {{--<div class="">添加整理箱</div>--}}
    {{--<div class="">添加关系链</div>--}}
    <div class="type_label">Knowledge Canned</div>
    @if(!empty($knowledge))
        @foreach($knowledge as $k=>$v)
            <div class="list knowledge_show_list" data-id= {{$v["_id"]}}>
                <span class="content">{!! $v["content"]!!}</span>
                <span class="knowledge_type source">{{$v["type"]}}</span>
                <span class="source">By:{{$v->users["name"]}}</span>
                <span class="knowledge_edit source">Edit</span>
                <span class="knowledge_delete source">Delete</span>
            </div>

        @endforeach
    @endif

    <div class="type_label">Related Research</div>

    <section id="cd-timeline" class="cd-container">
        @foreach($timeline as $K => $V)
            @if(in_array("科研",$V->tags))
                @if(isset($V->person)&&$V->person!="")
                    <?php $person[] = $V->person; ?>
                @endif
                <div class="cd-timeline-block">
                    <div class="cd-timeline-img cd-picture">
                        <img src="/img/cd-icon-picture.svg" alt="Picture">
                    </div> <!-- cd-timeline-img -->

                    <div class="cd-timeline-content">
                        <h2></h2>

                        <p class="excerpt">
                        {{$V->excerpt}}
                        <div class="gradient"></div>

                        </p>

                        <p>{{$V->title}}</p>

                        <p>
                            @if(!empty($V->tags))Tags:
                            @foreach($V->tags as $k=>$v)
                                <a href="/timeline/tag/{{$v}}">{{$v}}</a>
                            @endforeach
                            @endif
                            &nbsp;By:{{$V->users["name"]}}
                        </p>
                        <a href="{{$V->link}}" target="_blank" class="cd-read-more">Read more</a>
                        <span class="cd-date">{{Carbon\Carbon::parse($V->created_at)->format("M,d,Y")}}</span>
                    </div> <!-- cd-timeline-content -->
                </div> <!-- cd-timeline-block -->
                <?php  unset($timeline[$K]);?>

            @endif
        @endforeach
    </section>
    <div class="type_label">Related Corporate</div>
    @foreach($timeline as $K => $V)
        @if(in_array("合作",$V->tags))
            @if(isset($V->person)&&$V->person!="")
                <?php $person[] = $V->person; ?>
            @endif
            <?php $is_wuxu = array_intersect($wuxu, $V->tags); ?>

            <div class="list">
                {{--            @if($V->updated_at > Carbon\Carbon::today()->subHours(6))--}}
                {{--<label class="latest">today</label>--}}
                @if(count($is_wuxu)>0)
                    <label class="latest">
                        @foreach($is_wuxu as $a=>$b)
                            {{$b}}
                        @endforeach
                    </label>
                    {{--@endif--}}
                @endif
                <div class="date_wrap">
                    <span class="news_date">{{$V->created_at}}</span>
                </div>

                <div class="content_wrap">
                    <span class="des">{{$V->excerpt}}</span>
                <span class="title">Origin:<a href="{{$V->link}}" target="_blank">{{$V->title}}</a>
                    <span style="float: right;color: #d2d2d2">From:{{$V->source}}</span>
                </span>
                <span class="source_i">
                    @if(!empty($V->tags))Tags:
                    @foreach($V->tags as $k=>$v)
                        <a href="/timeline/tag/{{$v}}">{{$v}}</a>
                    @endforeach
                    @endif
                    </span>
                    <span class="source_i">Editor:{{$V->users["name"]}}</span>
                    {{--<span class="source">Editor:{{$V->companies["name"]}}</span>--}}
                </div>
            </div>
            <?php  unset($timeline[$K]);?>
        @endif
    @endforeach
    <div class="type_label">Related Funding</div>
    @foreach($timeline as $K => $V)
        @if(in_array("融资",$V->tags))
            @if(isset($V->person)&&$V->person!="")
                <?php $person[] = $V->person; ?>
            @endif
            <?php $is_wuxu = array_intersect($wuxu, $V->tags); ?>

            <div class="list">
                {{--            @if($V->updated_at > Carbon\Carbon::today()->subHours(6))--}}
                {{--<label class="latest">today</label>--}}
                @if(count($is_wuxu)>0)
                    <label class="latest">
                        @foreach($is_wuxu as $a=>$b)
                            {{$b}}
                        @endforeach
                    </label>
                    {{--@endif--}}
                @endif
                <div class="date_wrap">
                    <span class="news_date">{{$V->created_at}}</span>
                </div>

                <div class="content_wrap">
                    <span class="des">{{$V->excerpt}}</span>
                <span class="title">Origin:<a href="{{$V->link}}" target="_blank">{{$V->title}}</a>
                    <span style="float: right;color: #d2d2d2">From:{{$V->source}}</span>
                </span>
                <span class="source_i">
                    @if(!empty($V->tags))Tags:
                    @foreach($V->tags as $k=>$v)
                        <a href="/timeline/tag/{{$v}}">{{$v}}</a>
                    @endforeach
                    @endif
                    </span>
                    <span class="source_i">Editor:{{$V->users["name"]}}</span>
                    {{--<span class="source">Editor:{{$V->companies["name"]}}</span>--}}
                </div>
            </div>
            <?php  unset($timeline[$K]);?>
        @endif
    @endforeach
    <div class="type_label">Related Industry Info</div>
    @foreach($timeline as $K => $V)
        @if(isset($V->person)&&$V->person!="")
            <?php $person[] = $V->person; ?>
        @endif
        <?php $is_wuxu = array_intersect($wuxu, $V->tags); ?>

        <div class="list">
            {{--            @if($V->updated_at > Carbon\Carbon::today()->subHours(6))--}}
            {{--<label class="latest">today</label>--}}
            @if(count($is_wuxu)>0)
                <label class="latest">
                    @foreach($is_wuxu as $a=>$b)
                        {{$b}}
                    @endforeach
                </label>
                {{--@endif--}}
            @endif
            <div class="date_wrap">
                <span class="news_date">{{$V->created_at}}</span>
            </div>

            <div class="content_wrap">
                <span class="des">{{$V->excerpt}}</span>
                <span class="title">Origin:<a href="{{$V->link}}" target="_blank">{{$V->title}}</a>
                    <span style="float: right;color: #d2d2d2">From:{{$V->source}}</span>
                </span>
                <span class="source_i">
                    @if(!empty($V->tags))Tags:
                    @foreach($V->tags as $k=>$v)
                        <a href="/timeline/tag/{{$v}}">{{$v}}</a>
                    @endforeach
                    @endif
                    </span>
                <span class="source_i">Editor:{{$V->users["name"]}}</span>
                {{--<span class="source">Editor:{{$V->companies["name"]}}</span>--}}
            </div>


        </div>
    @endforeach
    <div class="type_label">Related Company</div>
    <?php
    $funds = [];
    ?>
    @foreach($companys as $cK => $cV)

        <?php //$person[] = $cV["founder"];
        $funds[] = $cV["raiseFunds"];?>
        <div class="list">
            @if($V->updated_at > Carbon\Carbon::today()->subHours(6))
                <label class="latest">today</label>
            @endif

            <div class="company_wrap">
                <span class="r_c_list"><img src="{{$cV["avatar"]}}"></span>
                <span class="r_c_list"><a href="/qs-admin/detail/{{$cV['id']}}">{{$cV["name"]}}</a></span>
                <span class="r_c_list c_des">{{$cV["des"]}}</span>
            </div>
            <div class="company_wrap">
                @if(isset($cV["raiseFunds"][0]))
                    最近一次融资发生在
                    <span class="r_c_list">{{$cV["raiseFunds"][0]["times"]}}</span>
                    ,融资额为
                    <span class="r_c_list">{{isset($cV["raiseFunds"][0]["amount_o"])?$cV["raiseFunds"][0]["amount_o"]:$cV["raiseFunds"][0]["amount"]}}</span>
                    ,融资轮次为
                    <span class="r_c_list">{{$cV["raiseFunds"][0]["phase"]}}</span>
                @endif
            </div>
        </div>

    @endforeach
    <div class="type_label">Related Person</div>
    {{--    {{dd($person)}}--}}
    @foreach($person as $cK => $cV)
        @if(is_array($cV)&&!empty($cV))
            @foreach($cV as $pk=>$pv)
                {{--                {{dd($pv["name"])}}--}}
                @if($pv!=""&&!empty($pv))
                    <div class="list">
                        <div class="company_wrap">
                                    <span class="r_c_list"><a
                                                href="/qs-admin/founder/{{$pv["_id"]}}">{{$pv["name"]}}</a></span>
                        </div>
                    </div>
                @endif
            @endforeach
        @elseif($cV!="")
            {{--<div class="list">--}}
            {{--<div class="company_wrap">--}}
            {{--<span class="r_c_list"><a href="/qs-admin/person/{{$cV}}">{{$cV}}</a></span>--}}
            {{--</div>--}}
            {{--</div>--}}
        @endif
    @endforeach
    <div class="type_label">Related Clinical</div>
    <div class="list">
        <div class="company_wrap">
            <span class="r_c_list">
                <a target="_blank" href="{{isset($tag->clinical)?$tag->clinical:"#"}}">{{$tag->name}}相关临床研究</a>
            </span>
        </div>
    </div>

    {{--<div class="type_label">Related Funds</div>--}}
    {{--@foreach($funds as $cK => $cV)--}}
    {{--@if(is_array($cV)&&!empty($cV))--}}
    {{--@foreach($cV as $pk=>$pv)--}}
    {{--                {{dd($pv)}}--}}
    {{--<div class="list">--}}
    {{--<div class="company_wrap">--}}
    {{--<span class="r_c_list"><a href="#">{{$pv["phase"]}}</a></span>--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--@endforeach--}}
    {{--@endif--}}
    {{--@endforeach--}}
    {{--<div class="type_label">Related Policy</div>--}}
    <script src="//cdn.bootcss.com/jquery/3.1.1/jquery.min.js"></script>
    {{--    {{$keynews->links()}}--}}
    <script src="//cdn.bootcss.com/wangeditor/2.1.20/js/wangEditor.min.js"></script>
    <script type="text/javascript">
        var editor = new wangEditor('knowledge_content');
        editor.create();
        $(function () {
            $(".knowledge_add").click(function () {
                editor.$txt.html("<p><br></p>");
                $(".edit_flag").attr("_id", "0");
                $(".knowledge_container").show();
                $(".knowledge_back").show();
            });

            $(".close_k").click(function () {
                $(this).parent().hide();
                $(".knowledge_back").hide();
            });
            $(".knowledge_submit").click(function () {
                var content = editor.$txt.html();
                if (content.replace(/\s+/g, "") == "<p><br></p>") {
                    alert("空内容!");
                    return false;
                }
                var type = $(".knowledge_list select option:selected").text();
                var tags = $(this).attr("tags");
                var param = {};
                param.content = content;
                param.tags = tags;
                param.type = type;
                param._id = $(".edit_flag").attr("_id");
                if (param._id != "0") {
                    update_knowledge("/knowledge/update", param, $(this));
                } else {
                    add_knowledge("/knowledge/add", param, $(this));
                }

            });
            $(".knowledge_edit").click(function () {
                var _id = $(this).parent().attr("data-id");
                var _content = $(this).siblings(".content")[0].innerHTML;
                console.log(_content);
                var _type = $(this).siblings(".knowledge_type").text();
                $(".knowledge_list select").val(_type);
                editor.$txt.html(_content);
                $(".edit_flag").attr("_id", _id);
                $(".knowledge_container").show();
                $(".knowledge_back").show();
//                alert(_id);
            });
            $(".knowledge_delete").click(function () {
                var _id = $(this).parent().attr("data-id");
                var param = {};
                param._id = _id;
                if (confirm("确定要移除?")) {
                    delete_knowledge("/knowledge/delete", param, $(this));
                }
            });
            function add_knowledge(url, param, dom) {
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
                            $(".knowledge_container").hide();
                            $(".knowledge_back").hide();
                            window.location.reload();
//                            dom.siblings(".search_r").html(html);
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

            function update_knowledge(url, param, dom) {
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
                            $(".knowledge_container").hide();
                            $(".knowledge_back").hide();
                            window.location.reload();
//                            dom.siblings(".search_r").html(html);
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

            function delete_knowledge(url, param, dom) {
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
                            $(".knowledge_container").hide();
                            $(".knowledge_back").hide();
                            dom.parent().remove();
//                            dom.siblings(".search_r").html(html);
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


            $(".excerpt").each(function () {
                height = $(this).height();
                if (height > 88) {
                    $(this).css({"height": "88", "overflow": "hidden"});
                    $(this).after("<a class=\"morecon\" >see more</a>");
                    $(this).after("<a class=\"leescon\" style=\"display: none\" >see less</a>");
                }
            });
            $(".morecon").click(function () {
                $(this).parent().children(".excerpt").css("height", "auto");
                $(this).parent().children(".leescon").css("display", "block");
                $(this).css("display", "none");
            });

            $(".leescon").click (function () {
                $(this).parent().children(".excerpt").css({"height": "88", "overflow": "hidden"});
                $(this).parent().children(".morecon").css("display", "block");
                $(this).css("display", "none");
            });


        })
    </script>
@endsection