<?php

namespace App\Media;

use App\DailyNews;
use App\DailyFunds;
use JonnyW\PhantomJs\Client;
use JonnyW\PhantomJs\DependencyInjection\ServiceContainer;
use Carbon\Carbon;
use Vinelab\Rss\Rss;


class WeMediaBuilder
{

    public function builder($url = "", $func = "")
    {
        $client = Client::getInstance();
        $client->getEngine()->addOption('--load-images=false');
        $client->getEngine()->addOption('--ignore-ssl-errors=true');

//        $client->getProcedureCompiler()->disableCache();
//        $client->getEngine()->addOption('--disk-cache=false');
//        $client->getEngine()->addOption('--proxy=80.90.161.217:80');
//        $client->getEngine()->addOption('--proxy-auth=username:password');
        $client->isLazy();

        $client->getEngine()->setPath('/usr/local/bin/phantomjs');
        $timeout = 30000;
        $delay = 90;

        /**
         * @see JonnyW\PhantomJs\Http\CaptureRequest
         **/
//        $width  = 800;
//        $height = 600;
//        $top    = 0;
//        $left   = 0;
//        $request = $client->getMessageFactory()->createCaptureRequest($url, 'GET');
//        $request->setOutputFile(storage_path('app/'.$func.".jpg"));
//        $request->setViewportSize($width, $height);
//        $request->setCaptureDimensions($width, $height, $top, $left);


        /**
         * @see JonnyW\PhantomJs\Http\PdfRequest
         **/
//        $request = $client->getMessageFactory()->createPdfRequest($url, 'GET');
//        $request->setOutputFile(storage_path('app/'.$func.".pdf"));
//        $request->setFormat('A4');
//        $request->setOrientation('landscape');
//        $request->setMargin('1cm');

        /**
         * @see JonnyW\PhantomJs\Http\Request
         **/
        $request = $client->getMessageFactory()->createRequest($url, 'GET');
        $request->addHeader("User-Agent", "Mozilla/5.0 WindowsWechat (Linux; Android 4.4.2; sdk Build/KK) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/30.0.0.0 Mobile Safari/537.36 MicroMessenger/6.0.0.61_r920612.501 NetType/epc.tmobile.com");
        $request->addHeader("Accept", "text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8");
        $request->addHeader("Host", "mp.weixin.qq.com");

        $request->setTimeout($timeout);
        $request->setDelay($delay);
//        dd($client->getProcedure());
//
//        /**
//         * @see JonnyW\PhantomJs\Http\Response
//         **/
        $response = $client->getMessageFactory()->createResponse();
//
//        // Send the request

        $client->send($request, $response);
        dd($response->getContent());
        $html = str_get_html($response->getContent());
        dd($html);
        if ($response->getStatus() == 200) {

        } elseif ($response->getStatus() == 408 || $response->getStatus() == 301 || $response->getStatus() == 302 || $response->getStatus() == 307) {

        } else {
            $this->parase_log($response->getStatus());
        }
    }



    /**
     *
     * @param string $pam
     * @param string $level
     */
    public function parase_log($pam = '', $level = "info")
    {
        if ($level == "error") {
            $fp = fopen("/usr/local/laravel/storage/app/exist.txt", "a+"); //文件被清空后再写入
        } elseif ($level == "policy") {
            $fp = fopen("/usr/local/laravel/storage/app/policy.txt", "a+");
        } else {
            $fp = fopen("/usr/local/laravel/storage/app/exist.txt", "a+"); //文件被清空后再写入
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

    public function save($dn)
    {

        $daily_s = DailyNews::where('link', '=', $dn["link"])->get();

        if ($daily_s->count() == 0) {
            $daily = new DailyNews();
            $daily->title = $dn["title"];
            $daily->link = $dn["link"];
            $daily->source = $dn["source"];
            $daily->pub_date = $dn["time"];
            $daily->company = "";
            $daily->tags = [];
            $daily->flag = 1;
            $daily->isread = 0;
            $daily->is_pub = 0;
            $daily->priority = isset($dn["priority"]) ? $dn["priority"] : 9;
            $status = $daily->save();
            if ($status) {

            } else {
                $this->parase_log($dn["link"]);
            }
        }
    }

    public function save_funds($dn)
    {

        $daily_s = DailyFunds::where('link', '=', $dn["link"])->orWhere('company', 'like', '%' . $dn["company"] . '%')->take(1000)->get();

        if ($daily_s->count() == 0) {
            $daily = new DailyFunds();
            $daily->company = $dn["company"];
            $daily->link = $dn["link"];
            $daily->source = $dn["source"];
            $daily->pub_date = $dn["time"];
            $daily->amount = $dn["amount"];
            $daily->round = $dn["round"];
            $daily->invest = $dn["invest"];
            $daily->flag = 1;
            $daily->isread = 0;
            $daily->is_pub = 0;
            $daily->priority = isset($dn["priority"]) ? $dn["priority"] : 9;
            $status = $daily->save();
            if ($status) {

            } else {
                $this->parase_log($dn["link"]);
            }
        }
    }
}
