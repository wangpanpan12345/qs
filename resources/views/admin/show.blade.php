@extends('admin')

@section('title', '奇速数据列表')

@section('sidebar')
    @parent
@endsection
<meta name="csrf-token" content="{{ csrf_token() }}">
<script src="//cdn.bootcss.com/jquery/3.1.1/jquery.min.js"></script>
<style>
    body {
        font-family: "PingFangSC-Light", "Microsoft YaHei", Helvetica, STHeiti, Verdana, Arial, Tahoma, sans-serif !important;
    }

    .labels, .labels_v {
        display: inline-block;
    }

    .labels_v {
        margin-left: 4px;
    }

    .labels_v i {
        display: inline-block;
        /*margin: 0 8px;*/
        color: #6d7375;
        /*background: #1d87e5;*/
        padding: 0 10px;
        height: 20px;
        line-height: 20px;
        height: 20px;
        border-radius: 2px;
        font-size: 12px;
        font-style: normal;
        cursor: pointer;

    }

    .labels_v i:hover {
        background: #1d87e5;
        color: #fff;
    }

    .active {
        background: #1d87e5;
        color: #fff !important;
    }

    .labels {
        margin-bottom: 10px;
    }

    .labels label {
        margin: 0;
        padding: 0;
        background: #1d87e5;
        width: 64px;
        height: 20px;
        line-height: 20px;
        border-radius: 2px;
        color: #fff;
        font-size: 12px;
        font-weight: 100;
        text-align: center;
    }

    .c_list_container {
        margin-top: 10px;
    }

    .c_list:first-child {
        /*border-top: 1px solid #ebefef;*/
    }

    .c_list {
        display: flex;
        /*align-content: space-between;*/
        /*align-items: center;*/
        /*padding: 10px 0;*/
        border: 1px solid #dde0e4;
        flex-direction: column;
        font-size: 14px;
        color: #6d7375;
        margin-bottom: 8px;
    }

    .t_s {
        height: 55px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 1px solid #dde0e4;
        background: #fff;
    }

    .a_d {
        display: flex;
        align-items: center;
        background: #f4f4f4;
    }

    .avatar img {
        width: 60px;
        max-height: 60px;
        border-radius: 0;
        align-self: center;

    }

    .avatar {
        display: flex;
        flex: 0 0 auto;
        height: 60px;
        margin: 18px 20px;
        background: #fff;
    }

    .wap_title {
        /*flex: 0 0 100px;*/
        margin: 0 20px;
        text-align: center;
        font-size: 18px;
        color: #1d87e5;
        font-weight: bold;
    }

    .wap_des {
        flex-wrap: wrap;
        flex: 1;
        overflow: hidden;
        max-height: 66px;
        text-align: justify;
        margin-right: 20px;
    }

    .score {
        flex: 0 0 200px;
        text-align: center;
        font-size: 24px;
        color: #d8d8d8;
    }

    .score i {
        font-style: normal;
        color: #f8e71c;
    }

    .pagination > li {
        cursor: pointer;
    }

    @media (max-width: 748px) {
        .wap_des {
            display: block;
            width: 100%;
            text-align: justify;
            margin: 0 auto;
        }

        .wap_title {
            width: 60%;
        }

        .wap_score {
            width: 100%;
            margin: 0 auto;
            text-align: right;
        }
    }
</style>
@section('content')
    {{--    {{dd($param)}}--}}
    @if(isset($tag))<h4>查询到{{$tag}}类公司共有{{$cs->total()}}个</h4>
    @else
        <section class="selector">
            <div>
                <div class="labels"><label>行业分类</label></div>
                <div class="labels_v" id="industry">
                    <i class="active">全部</i>
                    <i>医院</i>
                    <i>企业</i>
                    <i>科研机构</i>
                    <i>投资机构</i>
                    <i>高校</i>
                </div>
            </div>
            <div>
                <div class="labels"><label>全部地区</label></div>
                <div class="labels_v" id="area">
                    <i class="active">全部</i>
                    <i>国内</i>
                    <i>国外</i>
                </div>
            </div>
            <div>
                <div class="labels"><label>融资规模</label></div>
                <div class="labels_v" id="round">
                    <i class="active">全部</i>
                    <i>种子轮</i>
                    <i>天使轮</i>
                    <i>A轮</i>
                    <i>A+轮</i>
                    <i>B轮</i>
                    <i>C轮</i>
                    <i>D轮</i>
                    <i>E轮及以后</i>
                    <i>IPO</i>
                    <i>已上市</i>
                </div>
            </div>
            <div>
                <div class="labels"><label>人员规模</label></div>
                <div class="labels_v" id="scale">
                    <i class="active">全部</i>
                    <i>0-10</i>
                    <i>11-50</i>
                    <i>51-200</i>
                    <i>201-500</i>
                    <i>501-1k</i>
                    <i>1k-5k</i>
                    <i>5k-10k</i>
                    <i>10k+</i>
                </div>
            </div>
            <div>
                <div class="labels"><label>行业标签</label></div>
                <div class="labels_v" id="tags">
                    <i class="active">全部</i>
                    @if(!empty($tags))
                        @foreach($tags as $tk=>$tv)
                            <i>{{$tv->name}}</i>
                        @endforeach
                    @endif
                </div>
            </div>

        </section>
    @endif
    <section class="c_list_container">
        @foreach($cs->items() as $K => $V)
            <div class="c_list">
                <div class="t_s">
                     <span class="wap_title">
                        <a target="_blank" href="{{ URL::to('/qs-admin/detail/'.$V["_id"]) }}">{{$V["name"]}}</a>
                     </span>
                     <span class="score">
                    <?php $width = isset($V["complete_score"]) ? $V["complete_score"] : 0 ?>
                         @if((int)$width>100 || (int)$width==100)
                             <i>★★★★★</i>
                         @elseif((int)$width>80 || (int)$width==80)
                             <i>★★★★</i>★
                         @elseif((int)$width>60 || (int)$width==60)
                             <i>★★★</i>★★
                         @else
                             <i>★★</i>★★★
                         @endif
                    </span>
                </div>
                <div class="a_d">
                    <span class="avatar"><img src="{{$V["avatar"] or ""}}"/></span>

                    <span class="wap_des">{!! $V["des"] !!}</span>
                </div>


            </div>
        @endforeach
    </section>
    <script>
        $(function () {
            $('.labels_v i').click(function () {
                var url = "";
                var param = {};
                var query = $(this).parent().attr("id");

                $(this).parent().children().removeClass("active");

                if ($(this).text() != "全部")
                    param[query] = $(this).text();
                $(".labels_v .active").each(function () {
                    if ($(this).text() != "全部") {
                        param[$(this).parent().attr("id")] = $(this).text();
                    }

                });
                if (JSON.stringify(param) == "{}") {
                    param.all = 1;
                }
                $(this).addClass("active");

                get_result("/qs-admin/show", param, $(this));
            });
            $("body").on("click", ".pagination li span", function () {
                var page = $(this).attr("page");
                var url = "";
                var param = {};
                var query = $(this).parent().attr("id");

//                $(this).parent().children().removeClass("active");

//                if ($(this).text() != "全部")
//                    param[query] = $(this).text();
                $(".labels_v .active").each(function () {
                    if ($(this).text() != "全部") {
                        param[$(this).parent().attr("id")] = $(this).text();
                    }

                });

                if (JSON.stringify(param) == "{}") {
                    param.all = 1;
                }
                param.page = page;
//                $(this).addClass("active");
                console.log(param);

                get_result("/qs-admin/show", param, $(this));
            });
            function get_result(url, param, dom) {
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
                            console.log(data);
                            var html = "";

                            $.each(data.a["hits"], function (i, e) {
                                var avatar = "";
                                var des = "";
                                var score = 0;
                                var score_html = "";
                                if (e._source.avatar != "undefined" && typeof(e._source.avatar) != "undefined")
                                    avatar = e._source.avatar;
                                if (e._source.des != "undefined" && typeof(e._source.des) != "undefined")
                                    des = e._source.des;
                                if (e._source.complete_score != "undefined") {
                                    if (e._source.complete_score > 99) {
                                        score_html = '<i>★★★★★</i>'
                                    } else if (e._source.complete_score > 79) {
                                        score_html = '<i>★★★★</i>★'
                                    } else if (e._source.complete_score > 59) {
                                        score_html = '<i>★★★</i>★★'
                                    } else {
                                        score_html = '<i>★★</i>★★★'
                                    }

                                } else {
                                    score_html = '<i>★★</i>★★★'
                                }
                                html += '<div class="c_list">' +
                                        '<div class="t_s">' +
                                        '<span class="wap_title">' +
                                        '<a target="_blank" href="/qs-admin/detail/' + e._id + '">' + e._source.name + '</a>' +
                                        '</span>' +
                                        '<span class="score">' +
                                        score_html +
                                        '</span>' +
                                        '</div>' +
                                        '<div class="a_d">' +
                                        '<span class="avatar"><img src="' + avatar + '"/></span>' +

                                        '<span class="wap_des">' + des + '</span>' +
                                        '</div>' +

                                        '</div>';
                            });
                            $(".c_list_container")[0].innerHTML = html;
                            $(".pagination")[0].innerHTML = data.ph;
//                            console.log(html);

                        } else {
                            console.log(data);
                        }
                    },
                    error: function (data) {
                        console.log(data);
                    }
                })

            }
        });
    </script>
    {{$cs->links()}}
@endsection