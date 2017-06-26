<?//php ob_start();//开启缓存
?>
<?php
$appId = 'wx1f561eaeca5778cc';
$appsecret = '9f45ddac4e8a20753549b459cdf505f2';
$timestamp = time();
$jsapi_ticket = make_ticket($appId, $appsecret);
$nonceStr = make_nonceStr();
$url = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
$signature = make_signature($nonceStr, $timestamp, $jsapi_ticket, $url);
function make_nonceStr()
{
    $codeSet = '1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    for ($i = 0; $i < 16; $i++) {
        $codes[$i] = $codeSet[mt_rand(0, strlen($codeSet) - 1)];
    }
    $nonceStr = implode($codes);
    return $nonceStr;
}
function make_signature($nonceStr, $timestamp, $jsapi_ticket, $url)
{
    $tmpArr = array(
            'noncestr' => $nonceStr,
            'timestamp' => $timestamp,
            'jsapi_ticket' => $jsapi_ticket,
            'url' => $url
    );
    ksort($tmpArr, SORT_STRING);
    $string1 = http_build_query($tmpArr);
    $string1 = urldecode($string1);
    $signature = sha1($string1);
    return $signature;
}
function make_ticket($appId, $appsecret)
{
    $access_token_file = storage_path('app/access_token.json');
    // access_token 应该全局存储与更新，以下代码以写入到文件中做示例
    $data = json_decode(file_get_contents($access_token_file));

    if (is_null($data) || $data->expire_time < time()) {

        $TOKEN_URL = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=" . $appId . "&secret=" . $appsecret;
        $json = file_get_contents($TOKEN_URL);

        $result = json_decode($json, true);

        $access_token = $result['access_token'];
        if ($access_token) {
            $data->expire_time = time() + 7000;
            $data->access_token = $access_token;
            $fp = fopen($access_token_file, "w");
            fwrite($fp, json_encode($data));
            fclose($fp);
        }
    } else {
        $access_token = $data->access_token;
    }
    // jsapi_ticket 应该全局存储与更新，以下代码以写入到文件中做示例
    $jsapi_ticket_file = storage_path('app/jsapi_ticket.json');
    $data = json_decode(file_get_contents($jsapi_ticket_file));
    if (is_null($data) || $data->expire_time < time()) {
        $ticket_URL = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token=" . $access_token . "&type=jsapi";
        $json = file_get_contents($ticket_URL);
        $result = json_decode($json, true);
        $ticket = $result['ticket'];
        if ($ticket) {
            $data->expire_time = time() + 7000;
            $data->jsapi_ticket = $ticket;
            $fp = fopen($jsapi_ticket_file, "w");
            fwrite($fp, json_encode($data));
            fclose($fp);
        }
    } else {
        $ticket = $data->jsapi_ticket;
    }
    return $ticket;
}
?>

        <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta content="webkit" name="renderer">
    {{--<meta http-equiv="X-UA-Compatible" content="IE=edge">--}}
    {{--<meta name="viewport" content="width=device-width, initial-scale=1">--}}
    <meta content="initial-scale=1.0, width=device-width, user-scalable=no" name="viewport">
    <link href="https://oi7gusker.qnssl.com/qisu/member/WeChat.jpg" rel="icon" sizes="300x300">
    <link href="https://oi7gusker.qnssl.com/qisu/member/WeChat.jpg" rel="apple-touch-icon-precomposed">
    <link href="https://oi7gusker.qnssl.com/qisu/member/WeChat.jpg" rel="apple-touch-startup-image">
    <title>{{isset($info[0])?$info[0]->share_title:"暂无分享"}}|奇点网</title>

    <!-- Fonts -->
    {{--<link href="https://fonts.css.network/css?family=Raleway:100,600" rel="stylesheet" type="text/css">--}}

    <!-- Styles -->
    <style>
        html, body {
            background-color: #fff;
            color: #636b6f;
            font-family: PingFangSC-Regular, sans-serif, SimHei;
            font-weight: 500;
            height: 100vh;
            margin: 0;
        }

        .full-height {
            height: 100vh;
        }

        .flex-center {
            /*align-items: center;*/
            display: flex;
            /*justify-content: center;*/
            flex-direction: column;
            align-items: center;
        }

        .position-ref {
            position: relative;
        }

        .links > a {
            color: #636b6f;
            padding: 0 25px;
            font-size: 12px;
            font-weight: 600;
            letter-spacing: .1rem;
            text-decoration: none;
            text-transform: uppercase;
        }

        .list {
            max-width: 768px;
        }

        .list section {
            /*display: block;*/
            margin: 10px;
            padding: 10px 20px;
        }

        .title {
            display: flex;
            /*align-items: center;*/
            text-align: left;
            font-size: 16px;
            text-decoration: none;
            margin-bottom: 0 !important;
        }

        .title a {
            text-decoration: none;
            color: #272727;
            /*font-weight: 500;*/
            font-weight: bold;
            letter-spacing: 2px;
        }

        .title:before {
            content: ' ';
            display: inline-block;
            background: #53c6fc;
            /*height: 100px;*/
            flex: 0 0 10px;
            margin-right: 5px;
        }

        .param {
            text-align: justify;
            color: #272727;
            line-height: 2em;
            font-size: 14px;
            letter-spacing: 2px;
            padding-top: 5px !important;
            margin-top: 0 !important;
        }

        .header {
            margin: 10px;
            margin-bottom: 0;
            padding: 5px 20px;
            padding-bottom: 0;
            max-width: 768px;
            text-align: left;
            font-size: 20px;
            color: rgb(51, 51, 51);
            text-align: justify;
            border: 0;
        }

        .footer {
            margin: 10px;
            padding: 5px 20px;
            max-width: 768px;
            margin-bottom: 20px;
        }

        .footer img {
            width: auto;
            height: auto;
            max-width: 100%;
            max-height: 100%;

        }

        .header h2, .header h3 {
            margin: 0;
            padding: 0;
        }

        .header h3, .header em {
            display: inline;
            margin: 0;
            padding: 0;
            font-size: 18px;
        }

        .header em {
            font-style: normal;
            color: #53c6fc;
        }

        .author {
            margin-bottom: 6px;
        }

        .param p {
            min-width: 1em;
            min-height: 1em;
            margin: 0;
            padding: 0;
        }

        @media (max-width: 748px) {
            .list {
                align-self: flex-start;
            }

            .header {
                align-self: flex-start;
            }

        }

        @media (min-width: 1080px) {
            .list {
                width: 768px;
            }

            .footer {
                width: 768px;
            }
            .header{
                width: 768px;
            }
            .header h2,.header h3{
                margin: 10px;
                padding: 10px 20px;
            }
        }

    </style>
</head>
<body>
<img src="https://oi7gusker.qnssl.com/qisu/member/WeChat.jpg"
     style="position:absolute;left:-999em;top:0;z-index:0;"/>

<div class="flex-center position-ref full-height">
    <div class="header"><h2>{{isset($info[0])?$info[0]->share_title:"暂无分享"}}</h2></div>
    <div class="header author"><h3>{{Carbon\Carbon::parse($date)->toDateString()}} </h3><em>奇点网</em></div>
    @foreach($info as $K=>$V)
        <div class="list">
            <section class="title"><a href="{{$V->link}}">{{$V->share_title}}</a></section>
            <section class="param"><p>{{$V->excerpt}}(from:{{$V->source}})</p></section>
        </div>
    @endforeach
    <div class="footer"><img src="https://oi7gusker.qnssl.com/qisu/member/WX_qidian.png"/></div>
</div>
</body>
<script>
    var _hmt = _hmt || [];
    (function () {
        var hm = document.createElement("script");
        hm.src = "https://hm.baidu.com/hm.js?282c52a0815f7643ae3e458eeba5c4be";
        var s = document.getElementsByTagName("script")[0];
        s.parentNode.insertBefore(hm, s);
    })();
</script>
<script src="https://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<script>
    wx.config({
        debug: false,
        appId: '<?=$appId?>',
        timestamp: <?=$timestamp?>,
        nonceStr: '<?=$nonceStr?>',
        signature: '<?=$signature?>',
        jsApiList: [
            'checkJsApi',
            'onMenuShareTimeline',
            'onMenuShareAppMessage',
            'onMenuShareQQ',
            'onMenuShareWeibo',
            'hideMenuItems',
            'showMenuItems',
            'hideAllNonBaseMenuItem',
            'showAllNonBaseMenuItem',
        ]
    });
    wx.onMenuShareTimeline({
        title: '每日医疗科技速递|奇点网', // 分享标题
        link: '<?=$url?>', // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
        imgUrl: 'https://oi7gusker.qnssl.com/qisu/member/WeChat.jpg', // 分享图标
        trigger: function (res) {
        },
        success: function (res) {
        },
        cancel: function (res) {
        },
        fail: function (res) {
            alert(JSON.stringify(res));
        }
    });
    wx.onMenuShareAppMessage({
        title: '每日医疗科技速递|奇点网', // 分享标题
        desc: '每日医疗科技速递|奇点网', // 分享描述
        link: '<?=$url?>', // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
        imgUrl: 'https://oi7gusker.qnssl.com/qisu/member/WeChat.jpg', // 分享图标
        type: '', // 分享类型,music、video或link，不填默认为link
        dataUrl: '', // 如果type是music或video，则要提供数据链接，默认为空
        success: function () {
            // 用户确认分享后执行的回调函数
        },
        cancel: function () {
            // 用户取消分享后执行的回调函数
        }
    });
</script>
</html>
<?php
//file_put_contents('/usr/local/laravel/public/member/index.html', ob_get_clean());
//把生成的静态内容保存
//header("Location:/member/index.html");
?>
