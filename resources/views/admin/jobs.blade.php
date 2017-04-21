@extends('admin')

@section('title', 'qisu')

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

    .job_block {
        margin: 20px 0;
    }

    .job_block input {
        width: 200px;
        outline: none;
    }

    .tags, .uid {
        padding: 5px 10px;
        background: #48b3f6;
        color: #fff;
        margin: 0 10px;
    }

    .users ul {
        display: none;
        margin: 0;
        padding: 0;
    }

    .users li {
        margin: 0;
        padding: 0;
        list-style: none;
        background: #48b3f6;
        color: #fff;
        width: 200px;
        text-align: center;
        margin: 3px 0;
        height: 30px;
        line-height: 30px;
        cursor: pointer;
    }

    .tag_c {
        display: inline-block;
    }

    .assign {
        padding: 8px 20px;
        background: #48b3f6;
        color: #fff;
    }
</style>
@section('content')
    <h3>标签整理任务</h3>

    <div>
        <div class="job_block subject">
            <span><input type="text" class="s_input" placeholder="主题"/></span>
        </div>
        <div class="job_block selection">
            <select class="tags_select" multiple="multiple" style="width: 300px">

            </select>
        </div>
        <div class="job_block users">

            <span>
                <input class="u_input" type="text" placeholder="指派给谁"/>
            </span>
            <span class="tag_c tag_c_u"></span>
            <ul class="author">
                <li>zhoulun</li>
                <li>yingyuyan</li>
                <li>wangxinkai</li>
                <li>wangren</li>
                <li>chenyahui</li>
            </ul>

        </div>
        <div class="job_block">
            <a href="javascript:void(0);" class="assign">开始分配</a>
        </div>
    </div>

    <h3>选题任务</h3>

    <div>
        <div class="job_block subject">
            <span><input type="text" class="s_input" placeholder="主题"/></span>
        </div>
        <div class="job_block subject">

        </div>

        <div class="job_block">
            <a href="javascript:void(0);" class="assign">开始分配</a>
        </div>
    </div>

    <script>
        $(function () {
            $(".tags_select").select2();
            $(".u_input").on("input", function () {
                $(".author").show();
            });
            $(".author li").click(function () {
                var html = "";
                html = '<i class="uid">' + $(this).text() + '</i>';
                $(".tag_c_u").append(html);
                $(this).parent("ul").hide();
                $(".u_input").val("");
            });
            $(".tags_select").select2({
                placeholder: '选择疑似标签',
                ajax: {
                    url: "/api/job/tags/",
                    cache: true,
                    delay: 250,
                    processResults: function (data) {
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
            $("body").on("click", ".assign", function () {
                var param = {};
                var name = $(".tags_select").siblings(".select2").find(".select2-selection__choice");
                var tags = [];
                $.each(name, function (n, value) {
                    tags.push($(value).attr("title"));

                });
                var users = [];
                var user_tag = $(".tag_c_u .uid");
                $.each(user_tag, function (n, value) {
                    users.push($(value).text());

                });
                var subject = $(".s_input").val();
                param.subject = subject;
                param.selection = tags;
                param.user = users;
                if (tags.length == 0 || users.length == 1 || subject == "") {
                    console.log(param);
                    alert("条件不足,不予分配!");
                    return false;
                }
                assign_jobs("'/jobs/create/", param);
                console.log(param);

            });

        })
    </script>

@endsection