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

    .list {
        position: relative;
        margin: 20px 0;
        border: 1px solid #e2e2e2;
        padding: 10px;
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

    .key_edit, .key_collect, .key_share {
        text-align: right;
    }

    .key_edit span, .key_collect span, .key_share span {
        background: #fe4b55;
        color: #fff;
        margin: 0 18px;
        padding: 0 10px;
        border-radius: 3px;
        cursor: pointer;
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
        padding: 10px;
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

    .share label {
        display: block;
        margin: 10px 0;
        text-align: center;
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
        .list{
            padding-bottom: 40px;
        }
    }

</style>
@section('content')

    <?php
    $author = isset($author) ? $author : "All";
    $scoring = $keynews->total();
    $level = ($author == "All") ? "" : "Lv" . (intval($scoring / 100) + 1);
    ?>
    <div class="author">Author:<b>{{$level}}</b>
        <select class="source_select">
            <option value="All">All</option>
            <option value="5847b487e9c046107b05fa81" {{($author=="5847b487e9c046107b05fa81")?"selected":""}}>周伦
            </option>
            {{--<option value="586b0c87e9c04603d141dec3" {{($author=="586b0c87e9c04603d141dec3")?"selected":""}}>李盈--}}
            {{--</option>--}}
            <option value="5858ec53e9c0460445718d62" {{($author=="5858ec53e9c0460445718d62")?"selected":""}}>应雨妍
            </option>
            <option value="5847c54de9c046107c26a381" {{($author=="5847c54de9c046107c26a381")?"selected":""}}>陈亚慧
            </option>
            {{--<option value="5858e90fe9c04603d141de93" {{($author=="5858e90fe9c04603d141de93")?"selected":""}}>王建秀--}}
            {{--</option>--}}
            {{--<option value="58b393bde9c0461f775dc3c2" {{($author=="58b393bde9c0461f775dc3c2")?"selected":""}}>张振宇--}}
            {{--</option>--}}
            <option value="58b6354fe9c0461d8f46d542" {{($author=="58b6354fe9c0461d8f46d542")?"selected":""}}>王新凯
            </option>
            <option value="58e6f811e9c046049346ef82" {{($author=="58e6f811e9c046049346ef82")?"selected":""}}>朱爽
            </option>
            <option value="58e6f820e9c046733142c1d1" {{($author=="58e6f820e9c046733142c1d1")?"selected":""}}>王鑫英
            </option>
            {{--<option value="58e703d7e9c046049346ef84" {{($author=="58e703d7e9c046049346ef84")?"selected":""}}>王韧--}}
            {{--</option>--}}

        </select>
    </div>
    <div class="share">
        <label>摘要</label>
        <span class="share_content" id="share_info">标签:纳米技术 科研,作者:zhoulun,来源:nature</span>
        <span class="share_content" id="share_excerpt">深化医疗、医保、医药联动改革。全面推开城市公立医院综合改革，全部取消药品加成，推进现代医院管理制度建设。推进公立医院人事制度改革，创新机构编制管理，建立以公益性为导向的考核评价机制，开展公立医院薪酬制度改革试点。健全医疗保险稳定可持续筹资和报销比例调整机制，提高城乡居民医保财政补助标准，同步提高个人缴费标准，扩大用药保障范围。改进个人账户，开展门诊统筹。深化医保支付方式改革，推进基本医保全国联网和异地就医结算，基本实现异地安置退休人员和符合规定的转诊人员就医住院医疗费用直接结算。继续推进城乡居民医保制度整合和政策统一。开展生育保险和基本医疗保险合并实施试点。在85%以上的地市开展分级诊疗试点和家庭签约服务，全面启动多种形式的医疗联合体建设试点。进一步改革完善药品生产流通使用政策，逐步推行公立医疗机构药品采购“两票制”。深化药品医疗器械审评审批制度改革。出台支持社会力量提供多层次多样化医疗服务的政策措施。</span>
        <span class="close_s">X</span>
    </div>

    @foreach($keynews->items() as $K => $V)

        <div class="list" data-id="{{$V->id}}">
            @if($V->updated_at > Carbon\Carbon::today()->subHours(6))
                <label class="latest">today</label>
            @endif
            <div class="date_wrap">
                <span class="news_date">{{$V->created_at}}</span>
            </div>

            <div class="content_wrap">
                <span class="des">{{$V->excerpt}}</span>
                <span class="title">Origin:<a href="{{$V->link}}" target="_blank">{{$V->title}}</a>
                    <span style="display:block;text-align: right;color: #d2d2d2">From:{{$V->source}}</span>
                </span>
                <span class="source">
                    @if(!empty($V->company))
                        Company:
                        @foreach($V->companies() as $k=>$v)
                            @if($v["_id"]!==""&&$v["name"]!=="")
                                <a href="/qs-admin/detail/{{$v["_id"]}}">{{$v["name"]}}</a>
                            @endif;
                        @endforeach
                    @endif
                </span>
                <span class="source">
                    @if(!empty($V->person))Person:
                    @foreach($V->person as $k=>$v)
                        <a href="/qs-admin/founder/{{$v["_id"]}}">{{$v["name"]}}</a>
                    @endforeach
                    @endif
                </span>
                <span class="source">
                    @if(!empty($V->tags))Tags:
                    @foreach($V->tags as $k=>$v)
                        <a href="/timeline/tag/{{$v}}">{{$v}}</a>
                    @endforeach
                    @endif
                </span>
                <span class="source">Editor:{{$V->users["name"]}}</span>
            </div>
            <div class="key_edit"><span>编辑</span></div>

            @if(isset($V->cuser)&&in_array(Auth::user()->id,$V->cuser))
                <div class="key_collect" disabled="true"><span>已收藏</span></div>
            @else
                <div class="key_collect"><span>收藏</span></div>
            @endif
            <div class="key_share"><span>分享</span></div>
        </div>

    @endforeach
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
            $(".key_edit span").click(function () {
                var title = $(this).parent(".key_edit").siblings(".content_wrap").find(".title a")[0].innerHTML;
                window.location.href = encodeURI("/dailynews/key?key=" + title);
            });
            $(".key_collect span").click(function () {
                var param = {};
                var _id = $(this).parent(".key_collect").parent(".list").attr("data-id");
                var type = "dailynews";
                param._id = _id;
                param.type = type;
                _collect("/dailynews/collect", param, $(this));
            });
            $(".key_share").click(function () {
                var info = $(this).parent(".list").find(".des")[0].innerHTML;
                var from = $(this).parent(".list").find(".title span")[0].innerHTML;
                var author = $(this).parent(".list").find(".source")[3].innerHTML;
                var tags = $(this).parent(".list").find(".source")[2].innerHTML;
                var content = from + ";  " + author + ';   ' + tags;
                $("#share_excerpt")[0].innerHTML = info;
                $("#share_info")[0].innerHTML = content;
                $(".share").show();
            });
            $(".close_s").click(function () {
                $(this).parent(".share").hide();
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
                            dom[0].innerHTML = "已收藏";
                            dom.css("diabled");
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
        })
    </script>
@endsection