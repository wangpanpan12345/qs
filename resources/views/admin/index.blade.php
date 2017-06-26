@extends('admin')

@section('title', 'QS')

@section('sidebar')
    @parent

@endsection
<style>
    .tags {
        display: none;
        width: 100%;
    }

    .tags a {
        margin: 0 auto;
    }

    .tags_flag {
        width: 100px;
        text-align: center;
        border: 2px solid #e2e2e2;
        margin: 20px 0px;
        font-size: 18px;
        height: 35px;
        line-height: 35px;
    }

    .tags span {
        display: inline-block;
        border: 1px solid #46b8da;
        margin: 4px;
        padding: 2px 6px;
        text-align: center;
    }

    .geekheal_tag {
        color: #fe4b55;
    }

    .new_static {
        font-size: 22px;
        margin: 15px 0;
    }

    .num {
        color: #fe4b55;
    }

    #logs {
        position: absolute;
    }

    #logs li {
        list-style: none;
        padding: 5px 0;
        border-bottom: 1px solid #e2e2e2;
    }

    @media (max-width: 748px) {
        #logs {
            position: relative;
        }
    }


</style>
<script src="//cdn.bootcss.com/jquery/3.1.1/jquery.min.js"></script>
<script type="text/javascript" src="/js/echarts.min.js"></script>
@section('content')
    {{--{{dd($logs)}}--}}
    {{--<h2>奇速数据概览</h2>--}}
    <div id="main" style="width: 400px;height:300px; display: inline-block"></div>
    <div id="logs" style="width: auto;height:300px; display: inline-block;overflow: hidden;">
        <ul>
            @foreach($logs as $k=>$v)
                <li>
                    <span>{{$v["created_at"]}}</span>
                    <span style="color: #fe4b55">{{$v["author"]}}</span>
                    在<span>{{$v->table}}</span>表
                    {{$v["action"]=="插入"?"创建":$v["action"]}}
                    @if($v["action"]=="更新"&&$v->table=="人物")
                        {{isset($v->person->name)?$v->person->name:""}}
                    @elseif($v["action"]=="更新"&&$v->table=="组织")
                        {{isset($v->company->name)?$v->company->name:""}}
                    @else
                        {{$v["record"]["name"]}}
                    @endif
                    {{--<a href="javascript:void(0);">详情</a>--}}
                </li>

            @endforeach
        </ul>

    </div>
    <script type="text/javascript">
        // 基于准备好的dom，初始化echarts实例
        var myChart = echarts.init(document.getElementById('main'));
        // 指定图表的配置项和数据
        var option = {
            title: {
                text: '新闻处理进度',
                subtext: '',
                x: 'center'
            },
            tooltip: {
                trigger: 'item',
                formatter: "{a} <br/>{b} : {c} ({d}%)"
            },
            legend: {
                orient: 'vertical',
                left: 'left',
                data: ['待处理', '已完成']
            },
            series: [
                {
                    name: '每日新闻处理',
                    type: 'pie',
                    radius: '55%',
                    center: ['50%', '60%'],
                    data: [
                        {value: {{$news_new-$deal_c}}, name: '待处理'},
                        {value: {{$deal_c}}, name: '已完成'},
                    ],
                    itemStyle: {
                        emphasis: {
                            shadowBlur: 10,
                            shadowOffsetX: 0,
                            shadowColor: 'rgba(0, 0, 0, 0.5)'
                        }
                    }
                }
            ]
        };


        // 使用刚指定的配置项和数据显示图表。
        myChart.setOption(option);

    </script>
    {{--<p>Total Companies:{{$count}}</p>--}}
    {{--<p>Total Founders:{{$count_f}}</p>--}}
    {{--<p>Total Invests:{{$count_i}}</p>--}}
    {{--<p>Set up in 2016:{{$count_2016}}</p>--}}
    {{--<p>Set up in 2015:{{$count_2015}}</p>--}}
    {{--<p>Set up in 2014:{{$count_2014}}</p>--}}
    <div class="new_static">
        今日新增新闻数据<span class="num">{{$news_new}}</span>,
        更新<span class="num">{{$company_new}}</span>个公司,
        更新<span class="num">{{$person_new}}</span>个人.
        更新<span class="num">{{$item_new}}</span>个词条.
        更新<span class="num">{{$knowledge_new}}</span>个知识罐头.

    </div>
    <div class="">更新的人</div>
    <div class="persons">
        @foreach($persons as $k=>$v)
            <span><a style="margin: 0 auto;" class="geekheal_tag" href="/qs-admin/founder/{{$v->_id}}">{{$v->name}}</a></span>
        @endforeach
    </div>
    <div class="">更新公司</div>
    <div class="companies">
        @foreach($companies as $k=>$v)
        <span><a style="margin: 0 auto;" class="geekheal_tag"
        href="/qs-admin/detail/{{$v->_id}}">{{$v->name}}</a></span>
        @endforeach
    </div>
    <div class="">更新词条</div>
    <div class="companies">
        @foreach($item as $k=>$v)
            <span><a style="margin: 0 auto;" class="geekheal_tag"
                     href="/item/{{$v->name}}">{{$v->name}}</a></span>
        @endforeach
    </div>
    <div class="">更新知识</div>
    <div class="companies">
        @foreach($knowledge as $k=>$v)
            <span><a style="margin: 0 auto;" class="geekheal_tag"
                     href="/timeline/tag/{{$v->tags}}">{{$v->tags}}-{{$v->type}}</a></span>
        @endforeach
    </div>
    <div class="tags_flag">显示Tags</div>

    <div class="tags">
        @foreach($tags as $k=>$v)
            {{--{{dd($v->name)}}--}}
            @if(isset($v->source)&&($v->source =="geekheal"||$v->source =="jianxiu"))
                <span><a style="margin: 0 auto;" class="geekheal_tag" href="/qs-admin/tag/{{$v->name}}">{{$v->name}}</a></span>
                {{--@else--}}
                {{--<span><a style="margin: 0 auto;" href="/qs-admin/tag/{{$v->name}}">{{$v->name}}</a></span>--}}
            @endif
        @endforeach
    </div>
    <script>
        $(function () {
            $(".tags_flag").click(function () {
                $(".tags").toggle();
            });
        })
    </script>

@endsection