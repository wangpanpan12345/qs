<?php
/**
 * Created by PhpStorm.
 * User: wangpan
 * Date: 16/11/28
 * Time: 下午12:02
 */

namespace App\Http\Controllers;

use App\DailyNews;
use App\Tags;
use App\WechatInfo;
use App\WechatJobs;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Auth;

ini_set('max_execution_time', 0);


class WechatCollectController extends Controller
{
    public function getMsgJson()
    {
        $str = $_POST['str'];
        $url = $_POST['url'];//先获取到两个POST变量

        //先针对url参数进行操作
        parse_str(parse_url(htmlspecialchars_decode(urldecode($url)), PHP_URL_QUERY), $query);//解析url地址
        $biz = $query['__biz'];//得到公众号的biz
        //接下来进行以下操作
        //从数据库中查询biz是否已经存在，如果不存在则插入，这代表着我们新添加了一个采集目标公众号。
        $s_biz = WechatInfo::where('biz', '=', $biz)->get();
        if ($s_biz->count() == 0) {
            $wi = new WechatInfo();
            $wi->biz = $biz;
            $wi->priority = 9;
            $wi->ctime = time();
            $wi->group = "policy";
            $status = $wi->save();
        }else{
            $name = isset($s_biz[0]["name"])?$s_biz[0]["name"]:"";
        }
        //再解析str变量
        $str = htmlspecialchars_decode(urldecode($str));
        $json = json_decode($str, true);//首先进行json_decode
        if (!$json) {
            $json = json_decode(htmlspecialchars_decode($str), true);//如果不成功，就增加一步htmlspecialchars_decode
        }
//        $this->parase_log(json_encode($json));
        foreach ($json['list'] as $k => $v) {
            $type = $v['comm_msg_info']['type'];
            if ($type == 49) {//type=49代表是图文消息
                $content_url = str_replace("\\", "", htmlspecialchars_decode($v['app_msg_ext_info']['content_url']));//获得图文消息的链接地址
                $is_multi = $v['app_msg_ext_info']['is_multi'];//是否是多图文消息
                $datetime = Carbon::createFromTimestamp($v['comm_msg_info']['datetime'])->toDateTimeString();//图文消息发送时间
                //在这里将图文消息链接地址插入到采集队列库中（队列库将在后文介绍，主要目的是建立一个批量采集队列，另一个程序将根据队列安排下一个采集的公众号或者文章内容）
//                $jobs = new WechatJobs();
//                $jobs->url = $content_url;
//                $jobs->load = 0;
//                $jobs->save();

                //在这里根据$content_url从数据库中判断一下是否重复
                $daily_w = DailyNews::where('link', '=', $content_url)->take(1000)->get();
                if ($daily_w->count() == 0) {
                    $fileid = $v['app_msg_ext_info']['fileid'];//一个微信给的id
                    $title = $v['app_msg_ext_info']['title'];//文章标题
                    $title_encode = urlencode(str_replace("&nbsp;", "", $title));//建议将标题进行编码，这样就可以存储emoji特殊符号了
                    $digest = $v['app_msg_ext_info']['digest'];//文章摘要
                    $source_url = str_replace("\\", "", htmlspecialchars_decode($v['app_msg_ext_info']['source_url']));//阅读原文的链接
                    $cover = str_replace("\\", "", htmlspecialchars_decode($v['app_msg_ext_info']['cover']));//封面图片

                    $is_top = 1;//标记一下是头条内容
                    //现在存入数据库
                    $daily = new DailyNews();
                    $daily->title = $title;
                    $daily->link = $content_url;
                    $daily->source = $name;
                    $daily->pub_date = $datetime;
                    $daily->company = "";
                    $daily->tags = [];
                    $daily->flag = 2;
                    $daily->isread = 0;
                    $daily->is_pub = 0;
                    $daily->priority = 9;
                    $daily->group = "policy";
                    $status = $daily->save();
                    echo "头条标题：" . $title . "\n";//这个echo可以显示在anyproxy的终端里
                    $this->parase_log($title.$content_url.$datetime.$name);
                }
                if ($is_multi == 1) {//如果是多图文消息
                    foreach ($v['app_msg_ext_info']['multi_app_msg_item_list'] as $kk => $vv) {//循环后面的图文消息
                        $content_url = str_replace("\\", "", htmlspecialchars_decode($vv['content_url']));//图文消息链接地址
                        //这里再次根据$content_url判断一下数据库中是否重复以免出错
                        $daily_w = DailyNews::where('link', '=', $content_url)->take(1000)->get();
                        if ($daily_w->count() == 0) {
                            //在这里将图文消息链接地址插入到采集队列库中（队列库将在后文介绍，主要目的是建立一个批量采集队列，另一个程序将根据队列安排下一个采集的公众号或者文章内容）
                            $title = $vv['title'];//文章标题
                            $fileid = $vv['fileid'];//一个微信给的id
                            $title_encode = urlencode(str_replace("&nbsp;", "", $title));//建议将标题进行编码，这样就可以存储emoji特殊符号了
                            $digest = htmlspecialchars($vv['digest']);//文章摘要
                            $source_url = str_replace("\\", "", htmlspecialchars_decode($vv['source_url']));//阅读原文的链接
                            //$cover = getCover(str_replace("\\","",htmlspecialchars_decode($vv['cover'])));
                            $cover = str_replace("\\", "", htmlspecialchars_decode($vv['cover']));//封面图片
                            //现在存入数据库
                            $daily = new DailyNews();
                            $daily->title = $title;
                            $daily->link = $content_url;
                            $daily->source = $name;
                            $daily->pub_date = $datetime;
                            $daily->company = "";
                            $daily->tags = [];
                            $daily->flag = 2;
                            $daily->isread = 0;
                            $daily->is_pub = 0;
                            $daily->priority = 9;
                            $daily->group = "policy";
                            $status = $daily->save();
                            echo "标题：" . $title . "\n";
                            $this->parase_log($title.$content_url.$datetime.$name);
                        }

                    }
                }
            }
        }
    }

    public function getWxHis()
    {
        //getWxHis.php 当前页面为公众号历史消息时，读取这个程序
//当值等于1时代表正在被读取
//首先删除采集队列表中load=1的行
//然后从队列表中任意select一行
//        $jobs = []; if(empty($jobs))
//        if ('队列表为空') {
        $bz_data = WechatInfo::orderBy('ctime', 'asc')->get()->take(1);
        $biz = $bz_data[0]["biz"];
        $_id = $bz_data[0]["_id"];
        //队列表如果空了，就从存储公众号biz的表中取得一个biz，这里我在公众号表中设置了一个采集时间的time字段，按照正序排列之后，就得到时间戳最小的一个公众号记录，并取得它的biz
        $url = "http://mp.weixin.qq.com/mp/getmasssendmsg?__biz=" . $biz . "#wechat_webview_type=1&wechat_redirect";//拼接公众号历史消息url地址（第一种页面形式）
        $url = "https://mp.weixin.qq.com/mp/profile_ext?action=home&__biz=" . $biz . "&scene=124#wechat_redirect";//拼接公众号历史消息url地址（第二种页面形式）

//            //更新刚才提到的公众号表中的采集时间time字段为当前时间戳。
        $wi = WechatInfo::find($_id);
        $wi->ctime = time();
        $status = $wi->save();
//        } else {
//            //取得当前这一行的content_url字段
////            $url = $content_url;
//            //将load字段update为1
//        }
        echo "<script type='text/javascript'>setTimeout(function(){window.location.href='" . $url . "';},200000);</script>";
//        return "<script>setTimeout(function(){window.location.href='" . $url . "';},2000);</script>";
    }

    public function getMsgExt()
    {
//        $str = $_POST['str'];
//        $url = $_POST['url'];//先获取到两个POST变量
//        //先针对url参数进行操作
//        parse_str(parse_url(htmlspecialchars_decode(urldecode($url)), PHP_URL_QUERY), $query);//解析url地址
//        $biz = $query['__biz'];//得到公众号的biz
//        $sn = $query['sn'];
//        //再解析str变量
//        $json = json_decode($str, true);//进行json_decode
//
//        //$sql = "select * from `文章表` where `biz`='".$biz."' and `content_url` like '%".$sn."%'" limit 0,1;
//        //根据biz和sn找到对应的文章
//
//        $read_num = $json['appmsgstat']['read_num'];//阅读量
//        $like_num = $json['appmsgstat']['like_num'];//点赞量
//        //在这里同样根据sn在采集队列表中删除对应的文章，代表这篇文章可以移出采集队列了
//        //$sql = "delete from `队列表` where `content_url` like '%".$sn."%'"
//
//        //然后将阅读量和点赞量更新到文章表中。
//        exit(json_encode());//可以显示在anyproxy的终端里
    }

    public function getWxPost()
    {
        //getWxPost.php 当前页面为公众号文章页面时，读取这个程序
        //首先删除采集队列表中load=1的行
        //然后从队列表中按照“order by id asc”选择多行(注意这一行和上面的程序不一样)
//        if (!empty('队列表') && count('队列表中的行数') > 1) {//(注意这一行和上面的程序不一样)
//            //取得第0行的content_url字段
//            $url = $content_url;
//            //将第0行的load字段update为1
//        } else {
//            //队列表还剩下最后一条时，就从存储公众号biz的表中取得一个biz，这里我在公众号表中设置了一个采集时间的time字段，按照正序排列之后，就得到时间戳最小的一个公众号记录，并取得它的biz
//            $url = "http://mp.weixin.qq.com/mp/getmasssendmsg?__biz=" . $biz . "#wechat_webview_type=1&wechat_redirect";//拼接公众号历史消息url地址（第一种页面形式）
//            $url = "https://mp.weixin.qq.com/mp/profile_ext?action=home&__biz=" . $biz . "&scene=124#wechat_redirect";//拼接公众号历史消息url地址（第二种页面形式）
//            //更新刚才提到的公众号表中的采集时间time字段为当前时间戳。
//        }
//        echo "<script>setTimeout(function(){window.location.href='" . $url . "';},2000);</script>";


    }

    public function parase_log($pam = '', $level = "info")
    {
        $base_path = storage_path("app/wechat.txt");
        if ($level == "error") {
            $fp = fopen($base_path, "a+"); //文件被清空后再写入
        } else {
            $fp = fopen($base_path, "a+"); //文件被清空后再写入
        }

        if ($fp) {
            $flag = fwrite($fp, $pam . "\r\n");
            if (!$flag) {
                echo "写入文件失败<br>";
            }
        } else {
            echo "打开文件失败";
        }
        fclose($fp);
    }
}