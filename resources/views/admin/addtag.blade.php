@extends('admin')

@section('title', 'qisu-Tag')

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

    ::-webkit-input-placeholder {
        color: #c0c2c1;
    }

    .border_container {
        width: 3%;
        border: 1px solid #0299f8;

    }

    .item_container {
        width: 60%;
        display: inline-block;
        top: 50%;
        position: relative;
        vertical-align: middle;
    }

    .ok_container {
        width: 35%;
        display: inline-block;
        position: relative;
        vertical-align: middle;
        top: 50%;
    }

    .tag_container {
        background: #eceff0;
        border: 1px solid #dddfe2;
        padding: 20px;
        margin-bottom: 50px;
        margin-top: 10px;

    }

    .tag_add {
        border-left-width: 10px;
        border-left-color: #0299f8;
        border-top-left-radius: 10px;
        border-bottom-left-radius: 10px;
    }

    .tag_container label {
        width: 100px;
        text-align: right;
        color: #4e4e4e;
    }

    .tag_container input {
        outline: none;
    }

    .item {
        margin-bottom: 10px;
        width: 45%;
        display: inline-block;
    }

    .add_ok {
        background: none;
        border: 1px solid #d2d2d2;
        margin-left: 110px;
    }

    .tag_search_out {
        display: none;
        position: absolute;
        width: 154px;
        margin-left: 104px;
        list-style: none;
        z-index: 2;
        background: #fff;
    }

    .tag_search_out ul {
        list-style: none;
        padding: 0;
        margin: 0 auto;
        border: 1px solid #e2e2e2;
        border-bottom: 0;
        text-align: center;

    }

    .tag_search_out li {
        border-bottom: 1px solid #e2e2e2;
        cursor: pointer;
    }

    .ok_container input {
        width: 100%;
        background: #1e88e5;
        /*border: 2px solid #1e88e5;*/
        color: #fff;
        /*border-radius: 2px;*/
        -webkit-transition: all .1s ease-in .1s;
        transition: all .1s ease-in .1s;
    }

    .add_ok:hover {
        background: #0d47a1;
        /*border: 2px solid #0d47a1;*/
        color: #fff;
        /*border: none;*/
        -webkit-transition: all .1s ease-in .1s;
        transition: all .1s ease-in .1s;
    }

    .first_tag p {
        border-bottom: 2px solid #000000;
        padding: 10px 0;
        font-size: 20px;
    }

    .second_tag {
        border-bottom: 1px solid #e2e2e2;
    }

    .second_tag span {
        display: inline-block;
        margin: 10px 10px;
        padding: 2px 8px;
        height: 35px;
        line-height: 35px;
        min-width: 100px;
        text-align: center;
        cursor: pointer;
        font-size: 14px;
    }

    /*.third_tag:after{*/
    /*border: 10px solid transparent;*/
    /*border-left: 10px solid #f00;*/
    /*width: 0;*/
    /*height: 0;*/
    /*position: absolute;*/
    /*content: ' '*/
    /*}*/
    .tag {
        border-radius: 2px;
    }

    .third_tag {
        display: inline-block;
    }

    .fourth_tag {
        margin: 10px 15px;
        background: #89cafc;
        height: 30px !important;
        line-height: 30px !important;
        color: #fff;
    }

    .s_first_label {
        font-size: 18px;
        font-weight: 500;
        color: #0299f8;
    }

    .second_tag_label {
        color: #fff;
        background: #0299f8;
        float: left;
        display: block;
    }

    .third_tag_label {
        color: #fff;
        background: #48b3f6;
    }

    ._58527195c3666efea044ab4a {
        color: #1e88e5;
    }

    .s_58527195c3666efea044ab4a {
        background: #1e88e5;
    }

    ._58575cc6c3666e0519353094 {
        color: #ffa000;
    }

    .s_58575cc6c3666e0519353094 {
        background: #ffa000;
    }

    ._5861d7a6e9c046043f7b56a4 {
        color: #4caf50;
    }

    .s_5861d7a6e9c046043f7b56a4 {
        background: #4caf50;
    }

    ._58638e99e9c04603d141deb0 {
        color: #f44336;
    }

    .s_58638e99e9c04603d141deb0 {
        background: #f44336;
    }

    .t_58527195c3666efea044ab4a .second_tag_label {
        background: #0d47a1;
    }

    .t_58527195c3666efea044ab4a .third_tag_label {
        background: #1e88e5;
        border-radius: 2px;
    }

    .t_58527195c3666efea044ab4a .fourth_tag {
        background: #90caf9;
        border-radius: 6px;
        vertical-align: bottom;
    }

    .t_58575cc6c3666e0519353094 .second_tag_label {
        background: #c86b23;
    }

    .t_58575cc6c3666e0519353094 .third_tag_label {
        background: #ffa000;
        border-radius: 2px;
    }

    .t_58575cc6c3666e0519353094 .fourth_tag {
        background: #f9c834;
        border-radius: 6px;
    }

    .t_5861d7a6e9c046043f7b56a4 .second_tag_label {
        background: #4caf50;
    }

    .t_58638e99e9c04603d141deb0 .second_tag_label {
        background: #b71c1c;
    }

    .tag_anchor {
        display: block;
        position: fixed;
        right: 30px;
        top: 40%;
        border-radius: 4px;
    }

    .tag_anchor span {
        display: block;
        width: 40px;
        text-align: center;
        color: #fff;
        font-size: 14px;
    }

    .tag_anchor a {
        cursor: pointer;
        text-decoration: none;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .tag_anchor a:hover {
        text-decoration: none;
    }

    .med_anchor {
        display: block;
        background: #1e88e5;
        height: 50px;
        line-height: 20px;
        vertical-align: middle;
        border-top-left-radius: 4px;
        border-top-right-radius: 4px;
    }

    .med_anchor:hover {
        background: #0d47a1;
        -webkit-transition: all .1s ease-in .1s;
        transition: all .1s ease-in .1s;
    }

    .med_anchor span, .them_anchor span {
        padding-top: 5px;
    }

    .gene_anchor {
        display: block;
        background: #4caf50;
        height: 40px;
        line-height: 40px;
        vertical-align: middle;
        text-underline: none;
    }

    .gene_anchor:hover {
        background: #1b5e20;
        -webkit-transition: all .1s ease-in .1s;
        transition: all .1s ease-in .1s;
    }

    .des_anchor {
        background: #ffa000;
        display: block;
        height: 40px;
        line-height: 40px;
        vertical-align: middle;
        text-underline: none;
    }

    .des_anchor:hover {
        background: #c86b23;
        -webkit-transition: all .1s ease-in .1s;
        transition: all .1s ease-in .1s;
    }

    .them_anchor {
        background: #f44336;
        display: block;
        height: 50px;
        line-height: 20px;
        vertical-align: middle;
        text-underline: none;
        border-bottom-left-radius: 4px;
        border-bottom-right-radius: 4px;
    }

    .them_anchor:hover {
        background: #b71c1c;
        -webkit-transition: all .1s ease-in .1s;
        transition: all .1s ease-in .1s;
    }

    .n_anchor {
        background: #dee5ea;
        display: block;
        height: 40px;
        line-height: 40px;
        vertical-align: middle;
        text-underline: none;
        margin-bottom: 20px;
        border-radius: 6px;
    }

    .square_container {
        display: inline-block;
        position: relative;
        width: 10px;
        height: 10px;
        margin-right: 5px;
    }

    .square {
        position: absolute;
        top: 50%;
        left: 50%;
        margin-top: -7px;
        margin-left: -5px;
        width: 10px;
        height: 10px;
    }

    .third_wrap {
        display: inline-block;
        /*float: left;*/
        width: 86%;
    }

    .t_5861d7a6e9c046043f7b56a4 .second_tag, .t_5861d7a6e9c046043f7b56a4 .second_tag {
        display: inline-block !important;
        position: relative;
    }

    .clear {
        clear: both;
    }

    .t_5861d7a6e9c046043f7b56a4 .clear {
        display: none;
    }

    .arrow {
        height: 16px;
    }

    .arrow-top {
        width: 0 !important;
        height: 0;
        border-left: 8px solid transparent;
        border-right: 8px solid transparent;
        border-bottom: 10px solid #bec3c8;
    }

    .square-top {
        width: 8px !important;
        background: #bec3c8;
        height: 6px;
        margin-left: 4px;
    }

    .n_anchor:hover {
        background: #a7aeb4;
    }

    .n_anchor:hover > .arrow > .arrow-top {
        border-bottom: 10px solid  #cdd5db;
    }
    .n_anchor:hover > .arrow > .square-top{
        background: #cdd5db;
    }

    @media (max-width: 1200px) {
        .tag_anchor {
            right: 0;
        }

        .ok_container {
            width: 35%;
        }

        .item_container {
            width: 64%;
        }

        .tag_container label {
            width: 26%;
        }
    }

    @media (max-width: 768px) {
        .tag_anchor {
            right: 0;
        }

        .ok_container {
            width: 100%;
            text-align: center;
        }

        .ok_container input {
            width: 50%;
        }

        .item {
            width: 100%;
        }

        .item_container {
            width: 100%;
        }

        .tag_container label {
            width: 40%;
            text-align: left;
        }
    }


</style>
@section('content')
    <div class="tag_anchor">
        <a class="n_anchor box" href="#top">
            <div class="arrow"><span class="arrow-top"></span><span class="square-top"></span></div>
        </a>
        <a class="med_anchor" href="#58574a7bc3666e2e1a4b60d1mao"><span>医疗科技</span></a>
        <a class="des_anchor" href="#58575d11c3666e5b3f404cafmao"><span>疾病</span></a>
        <a class="gene_anchor" href="#585e27e7e9c0460445718d63mao"><span>基因</span></a>
        <a class="them_anchor" href="#58638ea5e9c0460445718d84mao"><span>中心主题</span></a>
    </div>
    <div class="tag_container tag_add" name="top">
        {{--<div class="border_container"></div>--}}
        <div class="item_container">
            <div class="item">
                <label>标签名</label>
                <input type="text" name="name" id="name"/>
            </div>
            <div class="item">
                <label>所属标签</label>
                <input type="text" name="group_id" id="group_id" value="0" placeholder="一级标签填0" id="group_id"/>

                <div class="tag_search_out">
                    <ul>

                    </ul>
                </div>
            </div>
            <div class="item">
                <label>标签别名</label>
                <input type="text" name="alias" id="alias" placeholder="建议起个英文名"/>
            </div>
            <div class="item">
                <label>来源</label>
                <input type="text" name="source" id="source" value="geekheal" disabled/>
            </div>
        </div>
        <div class="ok_container">
            <div class="item">
                <input class="add_ok" type="submit" name="submit" value="确认添加"/>
            </div>
        </div>

    </div>

    <?php
    $array_first = [];
    $array_second = [];
    $array_third = [];
    $array_fourth = [];
    ?>
    {{--{{dd($tags)}}--}}
    @foreach($tags as $K=>$V)
        {{--{{dd($V)}}--}}
        @if($V->group_id==0)
            <?php
            $array_first[] = $V->_id;
            unset($tags[$K]);
            ?>
        @elseif(in_array($V->group_id,$array_first))
            <?php
            $array_first[$V->group_id][] = $V->name;
            ?>
            <div name="{{$V->_id."mao"}}" id="{{$V->_id."mao"}}"
                 style="display: block;top: -40px;position: relative;"></div>
            <div class="square_container"><span class="square s_{{$V->group_id}}"></span></div>
            <span class="s_first_label tag _{{$V->group_id}}">{{$V->name}}</span>
            <div class="first_tag tag tag_container t_{{$V->group_id}}" id="{{$V->_id}}" group_id="{{$V->group_id}}"
                 data-tag="{{$V->name}}">

            </div>
            <div class="clear"></div>
            <?php
            $array_second[] = $V->_id;
            unset($tags[$K]);
            ?>
        @elseif(in_array($V->group_id,$array_second))
            <?php
            $array_second[$V->group_id][] = $V->name;
            ?>
            <script>
                $(function () {
                    var html = '<div class="second_tag tag" data-tag="{{$V->name}}"><span class="second_tag_label tag"><?php echo $V->name; ?></span><div class="third_wrap" id=<?php echo $V->_id;?>></div></div>' + '<div class="clear"></div>'
                    $("#<?php echo $V->group_id; ?>").append(html);
                })
            </script>
            <?php
            $array_third[] = $V->_id;
            unset($tags[$K]);
            ?>
        @elseif(in_array($V->group_id,$array_third))
            <?php
            $array_third[$V->group_id][] = $V->name;
            ?>
            <script>
                $(function () {
                    var html = '<div class="third_tag tag" id=<?php echo $V->_id;?> data-tag="{{$V->name}}"><span class="third_tag_label tag"><?php echo $V->name; ?></span></div>'
                    $("#<?php echo $V->group_id; ?>").append(html);
                })
            </script>
            <?php
            $array_fourth[] = $V->_id;
            unset($tags[$K]);
            ?>
        @elseif(in_array($V->group_id,$array_fourth))
            <?php
            $array_fourth[$V->group_id][] = $V->name;
            ?>
            <script>
                $(function () {
                    var html = '<span class="fourth_tag tag" id=<?php echo $V->_id;?> data-tag="{{$V->name}}"><?php echo $V->name; ?></span>'
                    $("#<?php echo $V->group_id; ?>").append(html);
                })
            </script>
            <?php
            $array_fourth[] = $V->_id;
            unset($tags[$K]);
            ?>
        @endif
        {{--<div id="">{{$V->name}}</div>--}}
        {{--@elseif(in_array($V["group_id"],$array_first))--}}

    @endforeach
    @while($tags->count()>0)
        @foreach($tags as $K=>$V)
            {{--{{dd($V)}}--}}
            @if($V->group_id==0)
                <?php
                $array_first[] = $V->_id;
                unset($tags[$K]);
                ?>
            @elseif(in_array($V->group_id,$array_first))
                <?php
                $array_first[$V->group_id][] = $V->name;
                ?>
                <span class="s_first_label tag">{{$V->name}}</span>
                <div class="first_tag tag tag_container" id="{{$V->_id}}" group_id="{{$V->group_id}}"
                     data-tag="{{$V->name}}">

                </div>
                <?php
                $array_second[] = $V->_id;
                unset($tags[$K]);
                ?>
            @elseif(in_array($V->group_id,$array_second))
                <?php
                $array_second[$V->group_id][] = $V->name;
                ?>
                <script>
                    $(function () {
                        var html = '<div class="second_tag tag" id=<?php echo $V->_id;?> data-tag="{{$V->name}}"><span class="second_tag_label tag"><?php echo $V->name; ?></span><div class="third_wrap" id=<?php echo $V->_id;?>></div></div>'
                        $("#<?php echo $V->group_id; ?>").append(html);
                    })
                </script>
                <?php
                $array_third[] = $V->_id;
                unset($tags[$K]);
                ?>
            @elseif(in_array($V->group_id,$array_third))
                <?php
                $array_third[$V->group_id][] = $V->name;
                ?>
                <script>
                    $(function () {
                        var html = '<div class="third_tag tag" id=<?php echo $V->_id;?> data-tag="{{$V->name}}"><span class="third_tag_label tag"><?php echo $V->name; ?></span></div>'
                        $("#<?php echo $V->group_id; ?>").append(html);
                    })
                </script>
                <?php
                $array_fourth[] = $V->_id;
                unset($tags[$K]);
                ?>
            @elseif(in_array($V->group_id,$array_fourth))
                <?php
                $array_fourth[$V->group_id][] = $V->name;
                ?>
                <script>
                    $(function () {
                        var html = '<span class="fourth_tag tag" id=<?php echo $V->_id;?> data-tag="{{$V->name}}"><?php echo $V->name; ?></span>'
                        $("#<?php echo $V->group_id; ?>").append(html);
                    })
                </script>
                <?php
                $array_fourth[] = $V->_id;
                unset($tags[$K]);
                ?>
            @endif
            {{--<div id="">{{$V->name}}</div>--}}
            {{--@elseif(in_array($V["group_id"],$array_first))--}}

        @endforeach
    @endwhile
    {{--        {{var_dump($tags->count())}}--}}
    {{--{{dd($array_third)}}--}}


    <script>
        $(function () {

            var set_interval = _debounce(search_tag, 500);
//            var set_interval_name = _debounce(search_tag, 500);
            $('#group_id').on('input', function () {
                var param = {};
                param.q = $(this).val();
                if (param.q == "")
                    return false;
                set_interval("/api/dailynews/tags/", param, $(this));

            });
            $('#name').on('input', function () {
                var param = {};
                param.q = $(this).val();
                if (param.q == "")
                    return false;
                set_interval("/api/dailynews/tags/", param, $(this));

            });

            $(".tag_search_out").on("click", '.result_item', function () {
                var v = $(this).text();
                var _id = $(this).attr("_id");
                $(this).parent("ul").parent(".tag_search_out").siblings("#group_id").val(v);
                $(this).parent("ul").parent(".tag_search_out").siblings("#group_id").attr("_id", _id);
                $(this).parent("ul").parent(".tag_search_out").hide();
            });

            $(".add_ok").click(function () {
                var param = {};
                param.name = $("#name").val();
                if (param.name == "") {
                    alert("标签名不为空!");
                    return false;
                }
                param.group_id = $("#group_id").attr("_id");
                if (param.group_id == null || typeof(param.group_id) == "undefined") {
                    param.group_id = 0;
                }
                param.alias = $("#alias").val();
                param.source = $("#source").val();
                console.log(param);
//                return false;
                update_tags("/api/tags/add", param, $(this));
            });

            $(".tag").dblclick(function () {
                alert("edit the tag");
            });
//            $(".second_tag span").click(function () {
//                var tag = $(this).text();
//                window.location.href = "/timeline/tag/" + tag;
//            });


            $("span.tag").click(function () {
                var tag = $(this).text();
                window.location.href = encodeURI("/timeline/tag/" + encodeURIComponent(tag));
            });


            function search_tag(url, param, dom) {
                $.ajax({
                    method: "POST",
                    url: url,
                    dataType: "json",
                    data: param,
                    success: function (data) {
                        if (data.error == 0) {
                            var html = "";
                            $.each(data.result, function (n, value) {
                                html += "<li class='result_item' _id='" + value['_id'] + "'>" + value['name'] + "</li>";
                            });
                            dom.siblings(".tag_search_out").children("ul").html(html);
                            $(".tag_search_out").show();
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

            function update_tags(url, param, dom) {
                $.ajax({
                    method: "POST",
                    url: url,
                    dataType: "json",
                    data: param,
                    success: function (data) {
                        if (data.error == 0) {
                            alert("success created!");
                            location.reload();
//                            dom.parent(".opr_tags").hide();
                        } else if (data.error == 6) {
                            alert("existed!");
                        } else {
                            console.log(data);
                        }
                    },
                    error: function (data) {
                        console.log(data);
                    }
                })

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
        });
    </script>

@endsection