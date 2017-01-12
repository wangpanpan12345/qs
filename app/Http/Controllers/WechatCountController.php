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
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use JonnyW\PhantomJs\Client;
use Vinelab\Rss\Rss;
use App\Jobs\CrawlMedia;

ini_set('max_execution_time', 0);

include 'simple_html_dom.php';


class WechatCountController extends Controller
{
    public function media_array()
    {
        return [

            "geekheal" => "http://mp.weixin.qq.com/s?src=3&timestamp=1483009644&ver=1&signature=3J1MgTohikq0Su*lzNFm97bHDpeT6*4ieXw0bpcOzmN7D-bEE6NVDD2-9vEcKqrVtCRZ*dRg6bFKvVdaJFhwDama1o**1qq7Z8AdRjp78IV*Ue805eYeJJFGkb04B4Fp4VGXWPe93R67ziV5Ts6PTd1oOZHVRW28tcWbDmiL2CI=",

        ];
    }

    public function starter()
    {
        $medias = $this->media_array();
        foreach ($medias as $key => $value) {
//            $this->dispatch(new CrawlMedia(array("url" => $value, "func" => $key)));
            $this->builder($value, $key);
        }

    }

    public function builder($url = "", $func = "")
    {
//        dd(Carbon::createFromTimestamp(strtotime('28-Nov-2016 2:05 PM EST'),"Asia/shanghai")->format('Y-m-d H:i'));
//        dd(Carbon::createFromTimestamp(strtotime('Nov 28, 2016 8:55am EST'),"Asia/shanghai")->format('Y-m-d H:i'));

        $client = Client::getInstance();
        $client->getEngine()->addOption('--load-images=false');
        $client->getEngine()->addOption('--ignore-ssl-errors=true');
        $client->getProcedureCompiler()->disableCache();
//        $client->getEngine()->addOption('--disk-cache=false');
//        $client->getEngine()->addOption('--proxy=80.90.161.217:80');
//        $client->getEngine()->addOption('--proxy-auth=username:password');
        $client->isLazy();
        $client->getEngine()->setPath('/usr/local/bin/phantomjs');

//        $client->getEngine()->addOption('--load-images=false');
        $timeout = 29000;
        $delay = 60;
        /**
         * @see JonnyW\PhantomJs\Http\Request
         **/
        $request = $client->getMessageFactory()->createRequest($url, 'GET');
        $request->setTimeout($timeout);
        $request->setDelay($delay);

        /**
         * @see JonnyW\PhantomJs\Http\Response
         **/
        $response = $client->getMessageFactory()->createResponse();

        // Send the request
        $client->send($request, $response);
        $html = str_get_html($response->getContent());
        if ($response->getStatus() == 200) {
            call_user_func(array($this, $func), $html);
        } else {
            call_user_func(array($this, $func), $html);
            return false;
        }

    }

    /**
     * 获取文章阅读量
     *
     */
    public function geekheal($html)
    {
        $count = $html->find("#sg_readNum3", 0)->innertext;
        $this->parase_log($count.'|'.Carbon::now()->toDateTimeString());
    }


    public function parase_log($pam = '', $level = "info")
    {
        if ($level == "error") {
            $fp = fopen("/usr/local/laravel/storage/app/wechat.txt", "a+"); //文件被清空后再写入
        } else {
            $fp = fopen("/usr/local/laravel/storage/app/wechat.txt", "a+"); //文件被清空后再写入
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