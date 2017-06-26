<?php
/**
 * Created by PhpStorm.
 * User: wangpan
 * Date: 16/11/21
 * Time: 上午10:31
 */

namespace App\Http\Controllers;
ini_set('max_execution_time', 0);
use App\Companies;
use App\Items;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Requests;
use MongoDB\BSON\UTCDateTime;
use JonnyW\PhantomJs\Client;
use App\Founders;
use App\DailyFunds;

include 'simple_html_dom.php';

class ICController extends Controller
{
    public function builder($url = "", $func = "", $key = "")
    {
        $client = Client::getInstance();
        $client->getEngine()->addOption('--load-images=false');
        $client->getEngine()->addOption('--ignore-ssl-errors=true');

        $client->isLazy();

        $client->getEngine()->setPath('/usr/local/bin/phantomjs');
        $timeout = 30000;
        $delay = 100;

        /**
         * @see JonnyW\PhantomJs\Http\Request
         **/
        $request = $client->getMessageFactory()->createRequest($url, 'GET');
        $request->addHeader("User-Agent", "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_10_5) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/54.0.2840.98 Safari/537.36");
        $request->addHeader("Accept", "text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8");
//        $request->addHeader("Referer", "http://www.tianyancha.com/company/28702567");
        $request->addHeader("Host", "www.tianyancha.com");
        $request->addHeader("Cookie","aliyungf_tc=AQAAAJhZkw6R3wUA8hBuJNQhOuHcK03Q; TYCID=0f712d0a43af4e0e9f1e2dad44ae6623; tnet=180.97.163.185; RTYCID=c766de7596924e79a090c19033f0885a; _pk_ref.1.e431=%5B%22%22%2C%22%22%2C1495087702%2C%22http%3A%2F%2Fqimingpian.com%2Fpage%2Fdetailcom.html%3Fsrc%3Dmagic%26ticket%3D%E5%8C%97%E4%BA%AC%E5%A5%87%E7%82%B9%E4%B8%87%E8%B1%A1%E7%A7%91%E6%8A%80%E6%9C%89%E9%99%90%E5%85%AC%E5%8F%B8%26id%3De892baf2b681d656c43546eeda0e28f5%26p%3D%E5%A5%87%E7%82%B9%E7%BD%91%26from%3Dwdp3%26posnum%3D1%26word%3D%E9%99%88%E4%BA%9A%E6%85%A7%22%5D; _pk_ref.6835.e431=%5B%22%22%2C%22%22%2C1495087703%2C%22http%3A%2F%2Fqimingpian.com%2Fpage%2Fdetailcom.html%3Fsrc%3Dmagic%26ticket%3D%E5%8C%97%E4%BA%AC%E5%A5%87%E7%82%B9%E4%B8%87%E8%B1%A1%E7%A7%91%E6%8A%80%E6%9C%89%E9%99%90%E5%85%AC%E5%8F%B8%26id%3De892baf2b681d656c43546eeda0e28f5%26p%3D%E5%A5%87%E7%82%B9%E7%BD%91%26from%3Dwdp3%26posnum%3D1%26word%3D%E9%99%88%E4%BA%9A%E6%85%A7%22%5D; Hm_lvt_e92c8d65d92d534b0fc290df538b4758=1494926363,1494995041,1494995049,1494995459; Hm_lpvt_e92c8d65d92d534b0fc290df538b4758=1495090556; _pk_id.6835.e431=a4820a4c864c08f9.1494228409.5.1495090556.1495087703.; _pk_ses.6835.e431=*; _pk_id.1.e431=7de18ea033798d85.1494228409.5.1495090557.1495087702.; _pk_ses.1.e431=*; token=de78e3f14f834317861df48ddd563c02; _utm=f30a22da1730409c9c75da07c0f7ca45; paaptp=05703e057190b60fc27c95457d43ffe15c1bbaec080072663015c1a5770ac");
        $request->addHeader("Tyc-From", "normal");
        $request->addHeader("Check-Error", "check");

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
//        dd($response);
        if ($response->getContentType() == "application/json;charset=UTF-8") {
            $html = $response->getContent();
//            dd($html);
        } else {

            $html = \str_get_html($response->getContent());
            echo $html;
        }


        if ($response->getStatus() == 200) {

            call_user_func(array($this, $func), $html);


        } elseif ($response->getStatus() == 408 || $response->getStatus() == 301 || $response->getStatus() == 302 || $response->getStatus() == 307) {

            call_user_func(array($this, $func), $html);

//            call_user_func(array($this, $func), $html);
        } else {

        }
    }

    public function basic($html)
    {
        echo ($html);
    }

    public function start_job()
    {
        $url = "http://www.tianyancha.com/company/28702567";
//        $url = $r["url"];
        $this->builder($url, "basic");
    }


}