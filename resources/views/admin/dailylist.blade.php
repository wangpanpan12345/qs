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

    .top_menu {
        display: flex;
        justify-content: space-between;
    }

    .taps {
        display: flex;
        /*align-items: center;*/
        justify-content: flex-end;
    }

    .taps .add_relation {
        margin-right: 20px;
        line-height: 30px;
    }

    .type_tap {
        display: inline-block;
        width: 130px;
        height: 30px;
        line-height: 30px;
        background: #dde0e4;
        color: #fff;
        border-radius: 2px;
        text-align: center;
    }

    .type_tap a {
        color: #fff;
    }

    .top_menu .active {
        background: #1E88E5;
    }

    .list {
        margin: 20px 0;
        border: 1px solid #e2e2e2;
        padding: 10px;
        position: relative;
    }

    .core_edit {
        display: flex;
        display: none;
        /*justify-content: space-between;*/
        flex-direction: column;
        /*align-items: flex-start;*/
        position: fixed;
        width: 900px;
        /*height: 400px;*/
        background: #f5f8fa;
        top: 50%;
        margin-top: -200px;
        left: 50%;
        margin-left: -450px;
        z-index: 1001;
        border: 1px solid #1d87e5;
        border-left: 10px solid #1d87e5;
        border-radius: 2px;
        animation: fade-in; /*动画名称*/
        animation-duration: .3s; /*动画持续时间*/
        -webkit-animation: fade-in .3s; /*针对webkit内核*/
    }

    .core_edit_l {
        justify-content: space-between;
        display: flex;
    }

    @keyframes fade-in {
        0% {
            opacity: 0;
        }
        /*初始状态 透明度为0*/
        40% {
            opacity: 0;
        }
        /*过渡状态 透明度为0*/
        100% {
            opacity: 1;
        }
        /*结束状态 透明度为1*/
    }

    @-webkit-keyframes fade-in { /*针对webkit内核*/
        0% {
            opacity: 0;
        }
        40% {
            opacity: 0;
        }
        100% {
            opacity: 1;
        }
    }

    @keyframes fade-in-shadow {
        0% {
            opacity: 0;
        }
        /*初始状态 透明度为0*/
        40% {
            opacity: 0;
        }
        /*过渡状态 透明度为0*/
        100% {
            opacity: .7;
        }
        /*结束状态 透明度为1*/
    }

    @-webkit-keyframes fade-in-shadow { /*针对webkit内核*/
        0% {
            opacity: 0;
        }
        40% {
            opacity: 0;
        }
        100% {
            opacity: .7;
        }
    }

    .ex_title {

        margin: 20px 50px 0 50px;
        background: #fff;
        display: inline-block;
        padding: 10px;
        border: 1px solid #d8d8d8;
        max-height: 48px;
        overflow: hidden;
        line-height: 28px;
        flex: 1;
    }

    .left_edit {
        margin-left: 52px;
        margin-top: 14px;
        margin-bottom: 25px;
    }

    .right_ok {
        margin-top: 14px;
        margin-right: 50px;
    }

    .right_ok span {
        display: block;
        width: 100px;
        height: 30px;
        background: #1d87e5;
        line-height: 30px;
        text-align: center;
        border-radius: 2px;
        color: #fff;
        cursor: pointer;
    }

    .right_ok img {
        width: 100px;
        height: 20px;
        margin: 5px 0;
        cursor: pointer;
        border-radius: 2px;
    }

    .l_row:first-child {
        margin: 0;
    }

    .l_row {
        display: flex;
        align-items: flex-start;
        margin: 10px 0;
    }

    .l_row label {
        width: 60px;
        color: #1E87E5;
    }

    .l_input {
        margin: 0 20px;
    }

    .news_wrap {
        display: flex;
        flex-direction: column;
    }

    .top_title {
        display: flex;
        justify-content: space-between;
        height: 54px;
        background: #fff;
        align-items: center;
    }

    .news_wrap {
        border: 1px solid #dde0e4;
    }

    .news_list {
        border-left: 10px solid #dde0e4;
    }

    .news_list:before {
        content: ""; /*:before和:after必带技能，重要性为满5颗星*/
        display: block;
        position: absolute; /*日常绝对定位*/
    }

    .news_wrap .active {
        border-left: 10px solid #1d87e5;
    }

    .news_wrap .edit_button {
        display: flex;
        flex: 0 0 60px;
        height: 30px;
        align-items: center;
        justify-content: center;
        background: #1d87e5;
        border-radius: 2px;
        cursor: pointer;
    }

    .pub {
        border-left: 10px solid #4CAF50 !important;
    }

    .edit_button img {
        height: 20px;
        margin: 5px 0;
    }

    .top_title {
        padding: 0 20px;
    }

    .tags {
        padding: 12px 20px;
        font-size: 12px;
        background: #f2f2f2;
        border-bottom: 1px solid #dde0e4;
        /*border-top: 1px solid #dde0e4;*/
    }

    .tags span {
        margin-right: 20px;
    }

    .tags label {
        margin: 0;
        display: inline-block;
        background: #d8d8d8;
        color: #fff;
        padding: 0 10px;
        margin-right: 6px;
        /*border-radius: 2px;*/
    }

    img {
        /*border-radius: 50%;*/
    }

    .title {
        display: inline-block;
        font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
        /*font-family: Baskerville-eText, Baskerville, Garamond, serif !important;*/
        font-size: 16px;
        font-weight: 500;
        margin-right: 20px;
    }

    .title a, .source a, .company_relate a {
        color: #323232;
    }

    .source {
        display: inline-block;
        width: 160px;
    }

    .pub_date {
        display: inline-block;
        width: 110px;
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
        outline: none;
    }

    .source_show span {
        display: inline-block;
        border: 1px solid #e2e2e2;
        padding: 2px;
        margin: 3px;
        height: 20px;
    }

    .selector input {
        height: 30px;
        outline: none;
    }

    .source_select {
        height: 30px;
        width: 180px;
        -webkit-appearance: none;
        border: 1px solid #d8d8d8;
        border-radius: 0 !important;
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
        background: #1d87e5 !important;
    }

    .pubished {
        background: #4CAF50 !important;
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
        margin-right: 42px;
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
        display: flex;
        align-items: center;
        justify-content: center;
        top: -46px;
        width: 26px;
        height: 26px;
        right: 0px;
        line-height: 26px;
        cursor: pointer;
        background: #fff;
        border: 1px solid #1d87e5;
        border-radius: 50%;
        color: #1d87e5;
    }

    .key_search {
        width: 180px;
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

    .select2-container--default .select2-selection--multiple {
        border-radius: 0;
    }

    .select2-search__field {
        width: 150px !important;
    }

    .shadow {
        display: none;
        position: fixed;
        left: 0;
        top: 0;
        width: 100%;
        height: 100vh;
        background: #4A4A4A;
        opacity: .7;
        z-index: 1000;
        animation: fade-in-shadow; /*动画名称*/
        animation-duration: .3s; /*动画持续时间*/
        -webkit-animation: fade-in-shadow .3s; /*针对webkit内核*/
    }

    @media (max-width: 748px) {
        .add_relation {
            display: none;
        }

        .top_title {
            height: auto;
        }

        .delete a {
            display: none;
        }
        .core_edit{
            width: 100%;
        }
        .taps{
            display: inline-block;
        }
        .type_tap{
            margin-bottom: 10px;
        }
        .news_wrap{
            margin-top: 10px;
        }
        .edit_button{
            display: none!important;
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
    <div class="shadow">

    </div>
    <div class="remover_container">
        <label><input name="no" type="radio" value="-2"/>内容不相关 </label>
        <label><input name="no" type="radio" value="-1"/>内容不重要 </label>
        <span class="close_s">X</span>
        <input type="text" style="display: none" id="delete_id_tmp"/>
        <a href="javascript:void(0);" id="delete_selector">提交</a>
    </div>

    <section class="core_edit">
        <div style="display: flex">
            <span class="ex_title"></span>
        </div>
        <div class="core_edit_l">
            <div class="close_s">X</div>
            <div class="left_edit">
                <div class="l_row">
                    <label>关联公司</label>
                <span class="l_input">
                    <select class="company_select" multiple="multiple" style="width: 300px">
                        @if(!empty($V->company))
                            @foreach($V->company as $kt=>$kv)
                                <option value="{{$kv["_id"]}}" selected="selected">{{$kv["name"]}}</option>
                            @endforeach
                        @endif
                    </select>
                </span>
                </div>
                <div class="l_row">
                    <label>关联人</label>
                <span class="l_input">
                    <select class="person_select" multiple="multiple" style="width: 300px">
                        @if(!empty($V->person))
                            @foreach($V->person as $kt=>$kv)
                                <option value="{{$kv["_id"]}}" selected="selected">{{$kv["name"]}}</option>
                            @endforeach
                        @endif
                    </select>
                </span>
                </div>
                <div class="l_row">
                    <label>打标签</label>
                <span class="l_input">
                    <select class="tags_select" multiple="multiple" style="width: 300px">
                        @if(!empty($V->tags))
                            @foreach($V->tags as $kt=>$kv)
                                <option value="{{$kv}}" selected="selected">{{$kv}}</option>
                            @endforeach
                        @endif
                    </select>
                </span>
                </div>
                <div class="l_row"><label>加入选题</label><span></span></div>
                <div class="l_row">
                    <label>列为精选</label>
                <span class="l_input"><textarea class="cp_textarea"></textarea>
                </span>
                </div>

            </div>
            <div class="right_ok">
                <div><span id="update_dn">提交</span></div>
            </div>
        </div>

    </section>

    <section class="top_menu">
        <div class="selector source_select_container">
            <form action="/dailynews/key" method="get" class="search_form">
                <input class="key_search" type="text" name="key" placeholder="keywords"/>
                <input class="selector_ok" type="submit" value="Search"/>
            </form>
            <select class="source_select">
                <option><span>新闻源选择</span></option>
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
                <option><span>forbes</span></option>
                <option><span>ca_cancer</span></option>
                <option><span>immunity</span></option>
                <option><span>annals</span></option>
                <option><span>jco</span></option>
                <option><span>jci</span></option>
                <option><span>cell_neurosciences</span></option>
                <option><span>gastrojournal</span></option>
                <option><span>annals_surgery</span></option>
                <option><span>jbjsjournal</span></option>


            </select>

            <input class="add_show" type="submit" value="添加一条"/>
        </div>
        <div class="taps">
                <span><a class="add_relation" target="_blank"
                         href="https://admin.geekheal.net/create">缺少关联?去创建</a></span>
            <span class="type_tap active"><a href="/dailynews">DailyNews(T)</a></span>
            <span class="type_tap"><a href="/dailynews_p">DailyNews(P)</a></span>
        </div>
        {{--<a style="float: right" href="/qs-admin/keynews">每日精选</a>--}}
    </section>


    <div class="add_dailynews add_news_container">
        <span><input type="text" id="add_title" placeholder="原文标题"/></span>
        <span><input type="text" id="add_link" placeholder="原文链接"/></span>
        <span><input type="text" id="add_source" placeholder="来源"/></span>
        <span><textarea placeholder="简述" id="add_excerpt"></textarea></span>
        <span><input type="text" id="add_created" placeholder="发布时间(2017-01-01)"/></span>
        <select class="tags_select" multiple="multiple" style="width: 300px">

        </select>
        <span class="company_add_">
             <select class="company_select" multiple="multiple" style="width: 300px">

             </select>
        </span>
        <span class="person_add_">
             <select class="person_select" multiple="multiple" style="width: 300px">
                 <a id="ttt">ttt11</a>
             </select>
        </span>
        <span><input type="submit" id="add_add" value="提交"/></span>
    </div>
    {{--<div class="process_report">--}}
    {{--<span class="deal"></span>--}}
    {{--</div>--}}
    {{--    {{dd($dn)}}--}}
    <span class="authoring" style="display: none" data-id="{{Auth::user()->name}}"></span>
    <section class="news_wrap">
        @foreach($dn->items() as $K => $V)
            <div class="news_list @if($V["created_at"]>Carbon\Carbon::today()->subHours(6)) active @endif @if(isset($V["is_pub"])&&$V["is_pub"]=='1') pub
            @endif">

                <div class="top_title">
                    <span class="title"><a style="margin: 0" target="_blank"
                                           href="{{ URL::to($V["link"]) }}">{!! $V["title"] !!}</a></span>
                    <span class="edit_button" data-id="{{$V["id"]}}"><img src="/img/Edit.svg"></span>


                    <div class="company_html" style="display: none">
                        @if(!empty($V->company))
                            @foreach($V->company as $kt=>$kv)
                                <option value="{{$kv["_id"]}}" selected="selected">{{$kv["name"]}}</option>
                            @endforeach
                        @endif
                    </div>
                    <div class="person_html" style="display: none">
                        @if(!empty($V->person))
                            @foreach($V->person as $kt=>$kv)
                                <option value="{{$kv["_id"]}}" selected="selected">{{$kv["name"]}}</option>
                            @endforeach
                        @endif
                    </div>
                    <div class="tags_html" style="display: none">
                        @if(!empty($V->tags))
                            @foreach($V->tags as $kt=>$kv)
                                <option value="{{$kv}}" selected="selected">{{$kv}}</option>
                            @endforeach
                        @endif
                    </div>
                    <div class="excerpt_html"
                         style="display: none">@if(isset($V["excerpt"])){{trim($V["excerpt"])}}@endif</div>

                </div>
                <div class="tags" data-id="{{$V["id"]}}">
                    <label>来源</label><span class="source">{{$V["source"]}}</span>
                    <label>时间</label><span class="pub_date">{{$V["pub_date"]}}</span>
                    @if(isset($V["is_pub"])&&$V["is_pub"]=='1')
                        <label class="pubished">{{"已发布"}}</label>
                    @endif
                    @if(isset($V["priority"])&&$V["priority"]=='1')
                        <label class="important">{{$V["source"]}}</label>
                    @endif
                </div>
            </div>
        @endforeach
    </section>
    {{--<?php $total = 0;$deal = 0?>--}}
    {{--@foreach($dn->items() as $K => $V)--}}

    {{--<div class="list" data-id="{{$V->_id}}">--}}
    {{--<div class="labels">--}}
    {{--@if($V["created_at"]>Carbon\Carbon::today()->subHours(6))--}}
    {{--<?php $total++;?>--}}
    {{--@if(!empty($V["tags"]||!empty($V["company"])||$V["is_pub"]=='1'||$V["flag"]=='0'))--}}
    {{--<?php $deal++;?>--}}
    {{--@endif--}}
    {{--<label class="latest">today</label>--}}
    {{--@endif--}}
    {{--@if(isset($V["priority"])&&$V["priority"]=='1')--}}
    {{--<label class="important">{{$V["source"]}}</label>--}}
    {{--@endif--}}
    {{--@if(isset($V["is_pub"])&&$V["is_pub"]=='1')--}}
    {{--<label class="pubished">{{"已发布"}}</label>--}}
    {{--@endif--}}
    {{--</div>--}}
    {{--<span class="title">--}}
    {{--<a style="margin: 0" target="_blank"--}}
    {{--href="{{ URL::to($V["link"]) }}">{!! $V["title"] !!}</a>--}}
    {{--</span>--}}
    {{--<span class="pub_at">@if($V["pub_date"]!=""&&!is_array($V["pub_date"]))--}}
    {{--pub_at:{{$V["pub_date"]}}@endif</span>--}}
    {{--<span class="source">--}}
    {{--From:<a href="/dailynews/source/{{$V["source"]}}">{{$V["source"]}}</a>--}}
    {{--</span>--}}
    {{--@if($V["company"]!="")--}}
    {{--<span class="company_relate">Related:--}}
    {{--@foreach($V["company"] as $c=>$n)--}}
    {{--<a href="/qs-admin/detail/{{$n["_id"]}}">{{$n["name"]}}</a>--}}
    {{--@endforeach--}}
    {{--</span>--}}
    {{--@endif--}}
    {{--@if($V["person"]!="")--}}
    {{--<span class="company_relate">Related person:--}}
    {{--@foreach($V["person"] as $t=>$g)--}}
    {{--<a href="/qs-admin/founder/{{$g["_id"]}}">{{$g["name"]}}</a>--}}
    {{--@endforeach--}}
    {{--</span>--}}
    {{--@endif--}}
    {{--@if(!empty($V["tags"]))--}}
    {{--<span class="company_relate">tags:--}}
    {{--@foreach($V["tags"] as $t=>$g)--}}
    {{--<a href="/timeline/tag/{{$g}}">{{$g}}</a>--}}
    {{--@endforeach--}}
    {{--</span>--}}
    {{--@endif--}}


    {{--<div class="opr_group">--}}
    {{--<span class="opr company"><a style="margin: 0" href="javascript:void(0);">关联公司</a></span>--}}
    {{--<span class="opr person"><a style="margin: 0" href="javascript:void(0);">关联人</a></span>--}}
    {{--<span class="opr tags"><a style="margin: 0" href="javascript:void(0);">打标签</a></span>--}}
    {{--<span class="opr pub_news"><a style="margin: 0" href="javascript:void(0);">列为精选</a></span>--}}
    {{--<span class="opr delete" data-id="{{$V->_id}}"><a style="margin: 0" href="javascript:void(0);">移入回收站</a></span>--}}

    {{--<span class="opr_company">--}}
    {{--<select class="company_select" multiple="multiple" style="width: 300px">--}}
    {{--@if(!empty($V->company))--}}
    {{--@foreach($V->company as $kt=>$kv)--}}
    {{--<option value="{{$kv["_id"]}}" selected="selected">{{$kv["name"]}}</option>--}}
    {{--@endforeach--}}
    {{--@endif--}}
    {{--</select>--}}
    {{--<input data-id="{{$V->_id}}" class="company_in" type="button" value="commit"/>--}}
    {{--</span>--}}

    {{--<span class="opr_person">--}}

    {{--<select class="person_select" multiple="multiple" style="width: 300px">--}}
    {{--@if(!empty($V->person))--}}
    {{--@foreach($V->person as $kt=>$kv)--}}
    {{--<option value="{{$kv["_id"]}}" selected="selected">{{$kv["name"]}}</option>--}}
    {{--@endforeach--}}
    {{--@endif--}}
    {{--</select>--}}
    {{--<input data-id="{{$V->_id}}" class="person_in" type="button" value="commit"/>--}}
    {{--</span>--}}

    {{--<span class="opr_tags">--}}
    {{--<select class="tags_select" multiple="multiple" style="width: 300px">--}}
    {{--@if(!empty($V->tags))--}}
    {{--@foreach($V->tags as $kt=>$kv)--}}
    {{--<option value="{{$kv}}" selected="selected">{{$kv}}</option>--}}
    {{--@endforeach--}}
    {{--@endif--}}
    {{--</select>--}}
    {{--<input data-id="{{$V->_id}}" class="tags_in" type="button" value="commit"/>--}}
    {{--</span>--}}
    {{--<span class="opr_excerpt">--}}
    {{--<div class="excerpt_wap" style="display: inline-block">--}}
    {{--<textarea class="cp_textarea">{{$V["excerpt"]}}</textarea>--}}
    {{--</div>--}}
    {{--<input data-id="{{$V->_id}}" class="excerpt_in" type="button" value="commit"/>--}}
    {{--</span>--}}
    {{--</div>--}}

    {{--</div>--}}

    {{--@endforeach--}}
    {{$dn->links()}}
    @if($total==0)
        {{$total = 1}}
    @endif
    <script>
        $(function () {
            $(".edit_button").click(function () {
                $(".company_select")[0].innerHTML = "";
                $(".person_select")[0].innerHTML = "";
                $(".tags_select")[0].innerHTML = "";
                $(".ex_title")[0].innerHTML = "";
                $(".cp_textarea").val("");
//                var company = $(this).parent(".top_title").children(".company_html")[0].innerHTML;
//                var person = $(this).parent(".top_title").children(".person_html")[0].innerHTML;
//                var tags = $(this).parent(".top_title").children(".tags_html")[0].innerHTML;
//                var excerpt = $(this).parent(".top_title").children(".excerpt_html")[0].innerHTML;
                var title = $(this).parent(".top_title").children(".title").children("a")[0].innerHTML;
                var _id = $(this).attr("data-id");
//                $(".company_select")[0].innerHTML = company;
//                $(".person_select")[0].innerHTML = person;
//                $(".tags_select")[0].innerHTML = tags;
                $(".ex_title")[0].innerHTML = title;
//                $(".cp_textarea").val(excerpt);
                var _idp = {};
                _idp._id = _id;
                get_one("/dailynews/get_one", _idp, $(this));
                $(".shadow").show();
                $(".core_edit").css("display", "flex");
                $("#update_dn").attr("data-id", _id);
                var param = {};
                param.name = $(".authoring").attr("data-id");
                param._id = $(this).attr("data-id");
//                console.log(param);
                post_edit_notify(param, "/dailynews/edit/notify");
            });


            $("#update_dn").click(function () {
                var param = {};
                var _id = $(this).attr("data-id");
                var excerpt = $(".l_input .cp_textarea").val();
                var company = $(".l_row .company_select").children('option');
                var company_now = $(".l_row .company_select").siblings(".select2").find(".select2-selection__choice");
                var company_now_tag = [];
                $.each(company_now, function (n, value) {
                    company_now_tag.push($(value).attr("title"));
                });
                var companys = [];
                $.each(company, function (n, value) {
                    var data = {};
                    data["_id"] = $(value).attr("value");
                    data["name"] = $(value).text();
                    if (company_now_tag.indexOf(data["name"]) != -1) {
                        companys.push(data);
                    }
                });
                var person = $(".l_row .person_select").children('option');
                var person_now = $(".l_row .person_select").siblings(".select2").find(".select2-selection__choice");
                var person_now_tag = [];
                $.each(person_now, function (n, value) {
                    person_now_tag.push($(value).attr("title"));
                });
                var persons = [];
                $.each(person, function (n, value) {
                    var data = {};
                    data["_id"] = $(value).attr("value");
                    data["name"] = $(value).text();
//                    console.log(data["name"], person_now_tag.indexOf(data["name"]));
                    if (person_now_tag.indexOf(data["name"]) != -1) {
                        persons.push(data);
                    }

                });
                var name = $(".l_row .tags_select").siblings(".select2").find(".select2-selection__choice");
                var tags = [];
                $.each(name, function (n, value) {
                    tags.push($(value).attr("title"));

                });
                param._id = _id;
                param.person = persons;
                param.company = companys;
                param.tags = tags;
                param.excerpt = excerpt;
                if (persons.length == 0 && companys.length == 0 && tags.length == 0 && excerpt == "") {
                    alert("空数据,发布失败!");
                    return false;
                }
                console.log(param);
                var dom = $('.tags[data-id=' + _id + ']');
                update_All("/dailynews/up_all", param, dom);

            });


            function get_one(url, param, dom) {
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
                            var company = data.result.company;
                            var company_html = "";
                            if (company != "undefined" && typeof(company) != "undefined" && company.length != 0) {
                                $.each(company, function (n, value) {
                                    company_html += '<option value="' + value._id + '" selected="selected">' + value.name + '</option>';
                                });
                            }
                            var tags = data.result.tags;
                            var tags_html = "";
                            if (tags != "undefined" && typeof(tags) != "undefined" && tags.length != 0) {
                                $.each(tags, function (n, value) {
                                    tags_html += '<option value="' + value + '" selected="selected">' + value + '</option>';
                                });
                            }
                            var person = data.result.person;
                            var person_html = "";
                            if (person != "undefined" && typeof(person) != "undefined" && person.length != 0) {
                                $.each(person, function (n, value) {
                                    person_html += '<option value="' + value._id + '" selected="selected">' + value.name + '</option>';
                                });
                            }
                            var excerpt = "";
                            if (excerpt != "undefined" && typeof(excerpt) != "undefined")
                                excerpt = data.result.excerpt;
                            $(".company_select")[0].innerHTML = company_html;
                            $(".person_select")[0].innerHTML = person_html;
                            $(".tags_select")[0].innerHTML = tags_html;
//                            $(".ex_title")[0].innerHTML = title;
                            $(".cp_textarea").val(excerpt);
                            console.log(tags_html);


                        } else {
                            console.log(data);
                        }
                    },
                    error: function (data) {
                        console.log(data);
                    }
                })
            }

            function update_All(url, param, dom) {
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
                            $(".shadow").hide();
                            $(".core_edit").hide();
                            window.location.reload();
                        } else {
                            console.log(data);
                        }
                    },
                    error: function (data) {
                        console.log(data);
                    }
                })
            }


            //  $(".deal").css({"width": "<?php echo ($deal*100/$total)."%"; ?>"});
            //$(".deal").attr({"title": "<?php echo $deal; ?>"});
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
                $(".shadow").hide();
                $(this).parent().parent().hide();
                var param = {};
                param.name = "wang";
                param._id = $("#update_dn").attr("data-id");

                post_edit_notify(param, "/dailynews/edit/notify");
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
//                            location.reload();
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
                        } else {
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
                placeholder: '请输入关联的标签',
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
                placeholder: '请输入关联的人物',
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
                placeholder: '请输入关联的公司',
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

            $(".pub_news").click(function () {
                var param = {};
                param.name = "wang";
                param._id = $(this).parent().parent(".list").attr("data-id");

                post_edit_notify(param, "/dailynews/edit/notify");
            });

            function post_edit_notify(param, url) {
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
    <script type="text/javascript">
        $(".tags_select").select2();

    </script>

@endsection
@section('js')

@endsection