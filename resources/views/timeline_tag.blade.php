@extends('admin')

@section('title', '每日精选')

@section('sidebar')
    @parent
@endsection

{{--<link href="https://fonts.css.network/css?family=Raleway:100,600" rel="stylesheet" type="text/css">--}}

<style>
    body {
        font-family: Raleway, "Pingfang SC", "Microsoft YaHei", Helvetica, STHeiti, Verdana, Arial, Tahoma, sans-serif !important;
    }

    .type_label {
        display: block;
        background: #333333;
        color: #fff;
        font-size: 22px;
        margin: 30px 0;
        height: 45px;
        line-height: 45px;
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
    }

</style>
@section('content')
    {{--    {{dd($timeline)}}--}}
    {{--    {{Carbon\Carbon::today()->format("Y-m-d")}}--}}
    <?php
    $company = [];
    $wuxu = ["合作", "科研", "融资", "并购", "临床", "I期", "II期", "III期", "FDA", "CE", "CFDA", "预临床", "IPO"];
    ?>
    <div><h2 style="text-align: center">{{$tag}}</h2></div>
    <div class="type_label">Related Research</div>
    @foreach($timeline as $K => $V)
        @if(isset($V->company)&&$V->company!="")
            <?php $company[$V->company] = $V->companies; ?>
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
                <span class="source">
                    @if(!empty($V->tags))Tags:
                    @foreach($V->tags as $k=>$v)
                        <a href="/timeline/tag/{{$v}}">{{$v}}</a>
                    @endforeach
                    @endif
                    </span>
                <span class="source">Editor:{{$V->users["name"]}}</span>
                {{--<span class="source">Editor:{{$V->companies["name"]}}</span>--}}
            </div>


        </div>
    @endforeach
    <div class="type_label">Related Company</div>
    <?php
    $company = array_unique($company);
    $person = [];
    $funds = [];
    ?>
    {{--    {{dd($company)}}--}}
    @foreach($company as $cK => $cV)
        <?php $person[] = $cV["founder"];
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
    @foreach($person as $cK => $cV)
        @if(is_array($cV)&&!empty($cV))
            @foreach($cV as $pk=>$pv)
                @if($pv!="")
                    <div class="list">
                        <div class="company_wrap">
                            <span class="r_c_list"><a href="/qs-admin/person/{{$pv}}">{{$pv}}</a></span>
                        </div>
                    </div>
                @endif
            @endforeach
        @elseif($cV!="")
            <div class="list">
                <div class="company_wrap">
                    <span class="r_c_list"><a href="/qs-admin/person/{{$cV}}">{{$cV}}</a></span>
                </div>
            </div>
        @endif
    @endforeach
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
    <div class="type_label">Related Policy</div>
    {{--    {{$keynews->links()}}--}}
@endsection