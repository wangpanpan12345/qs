@extends('admin')

@section('title', 'qisu-NameCard')

@section('sidebar')
    @parent

@endsection
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width,initial-scale=1.0">
<script src="//cdn.bootcss.com/jquery/3.1.1/jquery.min.js"></script>
<script src="//cdn.bootcss.com/jquery.form/3.51/jquery.form.min.js"></script>

<style>
    .thumb-wrap {
        overflow: hidden;
    }

    .thumb-wrap img {
        margin-top: 10px;
    }

    .pic-upload {
        width: 100%;
        height: 34px;
        margin-bottom: 10px;
    }

    #thumb-show {
        max-width: 100%;
        max-height: 300px;
    }

    .upload-mask {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, .4);
        z-index: 1000;
    }

    .upload-file .close {
        cursor: pointer;
        font-size: 14px;
    }

    .upload-file {
        position: absolute;
        top: 50%;
        left: 50%;
        margin-top: -105px;
        margin-left: -150px;
        max-width: 300px;
        z-index: 1001;
        display: none;
    }

    .upload-mask {
        display: none;
    }

    input[type=text] {
        display: block;
        width: 80%;
    }

    label {
        display: inline-block;
    }

    #logo {
        width: 100%;
        height: auto;
        display: none;
    }

    #thumb {
        width: 100%;
        height: 40px;
    }

    .submit {
        margin: 20px 10px;
    }

    .upload-wrap {
        text-align: center;
        border: 1px solid #e2e2e2;
    }

    .upload-wrap input {
        position: relative;

        top: 0;
        opacity: 0;
        filter: alpha(opacity=0);
        cursor: pointer
    }

    .up-tips {
        position: absolute;
        font-size: 26px;
    }

    .spinner {
        width: 60px;
        height: 60px;
        display: none;
        position: relative;
        margin: 10px auto;
    }

    .double-bounce1, .double-bounce2 {
        width: 100%;
        height: 100%;
        border-radius: 50%;
        background-color: #67CF22;
        opacity: 0.6;
        position: absolute;
        top: 0;
        left: 0;

        -webkit-animation: bounce 2.0s infinite ease-in-out;
        animation: bounce 2.0s infinite ease-in-out;
    }

    .double-bounce2 {
        -webkit-animation-delay: -1.0s;
        animation-delay: -1.0s;
    }

    @-webkit-keyframes bounce {
        0%, 100% {
            -webkit-transform: scale(0.0)
        }
        50% {
            -webkit-transform: scale(1.0)
        }
    }

    @keyframes bounce {
        0%, 100% {
            transform: scale(0.0);
            -webkit-transform: scale(0.0);
        }
        50% {
            transform: scale(1.0);
            -webkit-transform: scale(1.0);
        }
    }

    @media (min-width: 992px) {
        .col-md-4 {
            position: relative;
            padding: 15px 0;
            margin: 0 auto;
            width: 100% !important;
            overflow: hidden;
        }
    }
    @media (max-width: 748px) {
        .form-group input{
            width: 100%;
        }
    }
</style>
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Name Card upload</div>
                    <div class="upload-mask">
                    </div>
                    <div class="panel-body">
                        <div id="validation-errors"></div>
                        <form method="post" id="imgForm" action="/api/namecard/upload/" enctype="multipart/form-data">
                            <div class="form-group">
                                <label>名片上传</label>
                                <span class="require">(*)</span>

                                <div class="upload-wrap">
                                    <span class="up-tips">+</span>
                                    <input id="thumb" name="file" type="file"/>
                                </div>
                                <input id="imgID" type="hidden" name="id" value=""/>
                            </div>
                        </form>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-4 thumb-wrap">
                            <img id="logo" src="">
                            <input type="hidden" name="logo" id="img_path" value="">

                            <div class="pic-upload btn btn-block btn-info btn-flat" title="开始识别">开始识别</div>
                            <div class="spinner">
                                <div class="double-bounce1"></div>
                                <div class="double-bounce2"></div>
                            </div>
                        </div>
                        <div id="upload-avatar"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Name Card Detail</div>

                    <div class="panel-body">
                        <input type="hidden" name="MAX_FILE_SIZE" value="8000000">

                        <div class="form-group">
                            <label>姓名</label>
                            <span class="require">(*)</span>
                            <input id="name" name="name" type="text"/>
                            <label>公司</label>
                            <input id="company" name="company" type="text"/>
                            <label>部门</label>
                            <input id="department" name="department" type="text"/>
                            <label>职位</label>
                            <input id="jobtitle" name="jobtitle" type="text"/>
                            <label>电话</label>
                            <input id="tel_main" name="tel_main" type="text"/>
                            <label>手机</label>
                            <input id="tel_mobile" name="tel_mobile" type="text"/>
                            <label>家庭电话</label>
                            <input id="tel_home" name="tel_home" type="text"/>
                            <label>直拨电话</label>
                            <input id="tel_inter" name="tel_inter" type="text"/>
                            <label>传真</label>
                            <input id="fax" name="fax" type="text"/>
                            <label>传呼机</label>
                            <input id="pager" name="pager" type="text"/>
                            <label>主页</label>
                            <input id="web" name="web" type="text"/>
                            <label>邮箱</label>
                            <input id="email" name="email" type="text"/>
                            <label>地址</label>
                            <input id="address" name="address" type="text"/>
                            <label>邮编</label>
                            <input id="postcode" name="postcode" type="text"/>
                            <label>QQ</label>
                            <input id="ICQ" name="ICQ" type="text"/>

                            <div class="submit"><input type="submit" id="indb" value="导入数据库"/></div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <script>
        $(function () {
            //ajax 上传
            $(document).ready(function () {
                var options = {
                    beforeSubmit: showRequest,
                    success: showResponse,
                    dataType: 'json'
                };
//                var options = {
//                    target: '#imgForm',   // target element(s) to be updated with server response
//                    beforeSubmit: showRequest,  // pre-submit callback
//                    success: showResponse,  // post-submit callback
//                    dataType: 'json',
//                    // other available options:
//                    //url:       url         // override for form's 'action' attribute
//                    type: "post",        // 'get' or 'post', override for form's 'method' attribute
//                    // 'xml', 'script', or 'json' (expected server response type)
//                    //clearForm: true        // clear all form fields after successful submit
//                    //resetForm: true        // reset the form after successful submit
//
//                    // $.ajax options can be used here too, for example:
//                    timeout: 3000
//                };

                // bind form using 'ajaxForm'
                $('#imgForm').ajaxForm(options);
                $('#imgForm').on('change', function () {
                    $('#imgForm').ajaxForm(options).submit();
                });
            });

            function showRequest(formData, jqForm, options) {
                var queryString = $.param(formData);

                // jqForm is a jQuery object encapsulating the form element.  To access the
                // DOM element for the form do this:
                // var formElement = jqForm[0];

//                alert('About to submit: \n\n' + queryString);

                // here we could return false to prevent the form from being submitted;
                // returning anything other than false will allow the form submit to continue
                return true;
            }

            function showResponse(response) {
                if (response.success == false) {
                    var responseErrors = response.errors;
                    $.each(responseErrors, function (index, value) {
                        if (value.length != 0) {
                            $("#validation-errors").append('<div class="alert alert-error"><strong>' + value + '</strong><div>');
                        }
                    });
                    $("#validation-errors").show();
                } else {

//                    $('.pic-upload').next().css('display', 'block');
                    $("#logo").show();
                    console.log(response.pic);

                    $("#logo").attr('src', response.pic);
                    $("#logo").next().attr('value', response.pic);
                }
            }

//            function showResponse(responseText, statusText, xhr, $form)  {
//                alert('status: ' + statusText + '\n\nresponseText: \n' + responseText +
//                        '\n\nThe output div should have already been updated with the responseText.');
//            }

            $(".pic-upload").click(function () {
                var path = $("#img_path").val();
                var param = {};
                param.path = path;
                if (path === null || path == "") {
                    alert("请先上传!")
                    return false;
                }
                start_reg("/api/namecard", param);
            });
            $("#indb").click(function () {
                var param = {};
                var name = $("#name").val();
                var company = $("#company").val();
                var department = $("#department").val();
                var jobtitle = $("#jobtitle").val();
                var tel_main = $("#tel_main").val();
                var tel_mobile = $("#tel_mobile").val();
                var tel_inter = $("#tel_inter").val();
                var fax = $("#fax").val();
                var pager = $("#pager").val();
                var web = $("#web").val();
                var email = $("#email").val();
                var address = $("#address").val();
                var postcode = $("#postcode").val();
                var qq = $("#ICQ").val();
                param.name = name;
                param.company = company;
                param.department = department;
                param.jobtitle = jobtitle;
                param.tel_main = tel_main;
                param.tel_mobile = tel_mobile;
                param.tel_inter = tel_inter;
                param.fax = fax;
                param.email = email;
                param.pager = pager;
                param.web = web;
                param.address = address;
                param.postcode = postcode;
                param.qq = qq;
                console.log(param);
                if (name == "") {
                    alert("Name Needed!")
                    return false;
                }
                create_namecard("/api/namecard/add", param);
            });
            function test() {
                var data = {
                    "status": "OK",
                    "data": {
                        "er": "http://121.40.44.191:9099/enginefile/scan/20161215/1481771957215_1012550a-9b7c-439a-a83c-43aee2d76b29_d69911391ec44a078034e21f9e593dcd_er.jpg",
                        "dw": ["name:113,164;423,269", "zhicheng:454,215;761,276", "zhicheng:123,306;737,366", "company:119,511;1041,590", "addr:108,870;1194,920", "postcode:1352,881;1639,918", "tel:599,780;892,817", "mobile:109,776;497,815", "email:110,674;873,727"],
                        "f": [{"n": "name", "v": "范建兵"}, {
                            "n": "company",
                            "v": "广州市基准医疗有限责任公司"
                        }, {"n": "department"}, {"n": "jobtitle", "v": ["董事长", "国家a千人计划\"特聘专家"]}, {
                            "n": "tel_main",
                            "v": "020-3443 8809"
                        }, {
                            "n": "tel_mobile",
                            "v": "187 0184 1892"
                        }, {"n": "tel_home"}, {"n": "tel_inter"}, {"n": "fax"}, {"n": "pager"}, {"n": "web"}, {
                            "n": "email",
                            "v": "jianbing_fan@anchordx.com"
                        }, {"n": "address", "v": "中国.广州市国际生物岛螺旋三路9号502单元"}, {
                            "n": "postcode",
                            "v": "510300"
                        }, {"n": "ICQ"}]
                    }
                };
                if (data.status == "OK") {
                    $.each(data.data.f, function (n, value) {
//                        $('#' + value.n).val(value.v);
//                        console.log(value);
                    });
                }
            }

            test();

            function start_reg(url, param) {
                $.ajax({
                    method: "POST",
                    url: url,
                    dataType: "json",
                    data: param,
                    beforeSend: function (XMLHttpRequest) {
                        //alert('远程调用开始...');
                        $(".spinner").show();
                    },
                    success: function (data) {
                        if (data.status == "OK") {
                            $(".spinner").hide();
                            $.each(data.data.f, function (n, value) {
                                $('#' + value.n).val(value.v);
                            });
                        }
                        else {
                            console.log(data);
                        }
                    },
                    error: function (data) {
                        console.log(data);
                    }
                })
            }

            function create_namecard(url, param) {
                $.ajax({
                    method: "POST",
                    url: url,
                    dataType: "json",
                    data: param,
                    success: function (data) {
                        if (data.error == 0) {
                            alert("success!");
                            location.reload();
                        } else if (data.error == 6) {
                            alert("已存在的名片信息!请确认");
                        }
                        else {
                            console.log(data);
                        }
                    },
                    error: function (data) {
                        console.log(data);
                    }
                })
            }


        })
    </script>
@endsection
