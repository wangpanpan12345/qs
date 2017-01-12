@extends('admin')

@section('title', 'QS')

@section('sidebar')
    @parent

@endsection
<style>
    .tags {
        width: 100%;
    }

    .tags a {
        margin: 0 auto;
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

</style>
<script src="//cdn.bootcss.com/jquery/3.1.1/jquery.min.js"></script>
<script type="text/javascript" src="/js/echarts.min.js"></script>
@section('content')
    {{--<h2>奇速数据概览</h2>--}}
    <div id="main" style="width: 400px;height:300px; display: inline-block"></div>
    <div id="date_num" style="width: 400px;height:300px;display: inline-block"></div>
    <script type="text/javascript">
        // 基于准备好的dom，初始化echarts实例
        var myChart = echarts.init(document.getElementById('main'));
        var mynumChart = echarts.init(document.getElementById('date_num'));
        // 指定图表的配置项和数据
        var option = {
            title: {
                text: '数据抓取进度-crunchbase',
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
                data: ['待抓取', '已完成']
            },
            series: [
                {
                    name: '数据进度',
                    type: 'pie',
                    radius: '55%',
                    center: ['50%', '60%'],
                    data: [
                        {value: 600, name: '待抓取'},
                        {value: 23000, name: '已完成'},
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
        var option_date = {
            title: {
                text: '公司成立年份',
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
                data: ['2014', '2015', '2016', '其他']
            },
            series: [
                {
                    name: '成立时间占比',
                    type: 'pie',
                    radius: '55%',
                    center: ['50%', '60%'],
                    data: [
                        {value:<?php echo $count_2014;?>, name: '2014'},
                        {value:<?php echo $count_2015;?>, name: '2015'},
                        {value:<?php echo $count_2016;?>, name: '2016'},
                        {value:<?php echo $count-$count_2014-$count_2015-$count_2016;?>, name: '其他'},
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
        mynumChart.setOption(option_date);
    </script>
    {{--<p>Total Companies:{{$count}}</p>--}}
    {{--<p>Total Founders:{{$count_f}}</p>--}}
    {{--<p>Total Invests:{{$count_i}}</p>--}}
    {{--<p>Set up in 2016:{{$count_2016}}</p>--}}
    {{--<p>Set up in 2015:{{$count_2015}}</p>--}}
    {{--<p>Set up in 2014:{{$count_2014}}</p>--}}

    <div class="tags">
        @foreach($tags as $k=>$v)
            {{--{{dd($v->name)}}--}}
            @if(isset($v->source)&&$v->source =="geekheal")
                <span><a style="margin: 0 auto;" class="geekheal_tag" href="/qs-admin/tag/{{$v->name}}">{{$v->name}}</a></span>
            @else
                <span><a style="margin: 0 auto;" href="/qs-admin/tag/{{$v->name}}">{{$v->name}}</a></span>
            @endif
        @endforeach
    </div>


@endsection