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
    .tag_add{
        border-left-width:10px;
        border-left-color: #0299f8;
        border-top-left-radius: 10px;
        border-bottom-left-radius: 10px;
    }

    .tag_container label {
        width: 100px;
        text-align: right;
        color: #000;
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
        background: #fff;
        border: 2px solid #89cafc;
        color: #89cafc;
        border-radius: 2px;
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
    .tag{
        border-radius: 2px;
    }

    .third_tag {
        display: inline-block;
    }

    .fourth_tag {
        margin: 10px 15px;
        background:#89cafc;
        height: 30px!important;
        line-height: 30px!important;
        color: #fff;
    }
    .s_first_label{
        font-size: 18px;
        font-weight: 500;
        color: #0299f8;
    }
    .second_tag_label{
        color: #fff;
        background:#0299f8;
    }
    .third_tag_label{
        color: #fff;
        background:#48b3f6;
    }


</style>
@section('content')

    <div class="tag_container tag_add">
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
                <span class="s_first_label tag">{{$V->name}}</span>
                <div class="first_tag tag tag_container" id="{{$V->_id}}" group_id="{{$V->group_id}}" data-tag="{{$V->name}}">

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
                        var html = '<div class="second_tag tag" id=<?php echo $V->_id;?> data-tag="{{$V->name}}"><span class="second_tag_label tag"><?php echo $V->name; ?></span></div>'
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
                    <div class="first_tag tag tag_container" id="{{$V->_id}}" group_id="{{$V->group_id}}" data-tag="{{$V->name}}">

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
                            var html = '<div class="second_tag tag" id=<?php echo $V->_id;?> data-tag="{{$V->name}}"><span class="second_tag_label tag"><?php echo $V->name; ?></span></div>'
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
                if(param.name == ""){
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
                window.location.href = "/timeline/tag/" + tag;
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