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

class BaikeController extends Controller
{
    public function builder($url = "", $func = "", $key = "")
    {
        $client = Client::getInstance();
        $client->getEngine()->addOption('--load-images=false');
        $client->getEngine()->addOption('--ignore-ssl-errors=true');

        $client->isLazy();

        $client->getEngine()->setPath('/usr/local/bin/phantomjs');
        $timeout = 30000;
        $delay = 60;

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
        $request->addHeader("User-Agent", "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_10_5) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/54.0.2840.98 Safari/537.36");
        $request->addHeader("Accept", "text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8");


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
//        echo $response->getContent();

        $html = \str_get_html($response->getContent());


        if ($response->getStatus() == 200) {
            if ($func == "search_engine_keywords") {
                $r = [
                    "html" => $html,
                    "url" => $key,
                ];
                call_user_func(array($this, $func), $r);
            } else {
                call_user_func(array($this, $func), $html);
            }

        } elseif ($response->getStatus() == 408 || $response->getStatus() == 301 || $response->getStatus() == 302 || $response->getStatus() == 307) {
            if ($func == "search_engine_keywords") {
                $r = [
                    "html" => $html,
                    "url" => $key,
                ];
                call_user_func(array($this, $func), $r);
            } else {
                call_user_func(array($this, $func), $html);
            }
//            call_user_func(array($this, $func), $html);
        } else {
            $this->parase_log($response->getStatus());
        }
    }

    public function baidubaike($html)
    {
        $name = $html->find(".lemmaWgt-lemmaTitle-title h1", 0)->innertext;
        $summary = $html->find(".lemma-summary", 0)->innertext;
        $summary = trim($summary);
        $basic = $html->find(".basicInfo-block .basicInfo-item");
        try {
            $avatar = $html->find(".summary-pic img", 0)->src;
        } catch (\Exception $e) {
            $avatar = "";
        }

        $des = $html->find(".level-2");
        $counter = 0;
        $basic_arr = [];
        $info_arr = [];
        $arr_tmp = [];
        foreach ($basic as $k => $v) {
            if ($counter % 2 == 0) {
                $arr_tmp[] = trim(preg_replace("/&nbsp;/", "", $v->innertext));
            } else {
                $arr_tmp[] = trim($v->innertext);
                $basic_arr[] = $arr_tmp;
                $arr_tmp = [];
            }
            $counter++;
        }
        $info_tmp = [];
        foreach ($des as $k => $v) {
            $info_tmp[] = $v->find('h2', 0)->plaintext;
            $info_value = "";
            while (isset($v->next_sibling()->attr["class"]) && $v->next_sibling()->attr["class"] == "para") {
                $info_value = $info_value . $v->next_sibling();
                $v = $v->next_sibling();
            }
            $info_tmp[] = $info_value;
            $info_arr[] = $info_tmp;
            $info_tmp = [];

        }

        try {
            $ref = $html->find(".reference-list", 0)->innertext;
        } catch (\Exception $e) {

        }
        $source = "baidubaike";
        $tag = [];
        $link = "http://baike.baidu.com/item/" . $name;
        $person = Founders::where("name", $name)->orWhere("founderDetailLink", $link)->get();
        if (count($person) == 0) {
            $person = new Founders();
            $person->name = $name;
            $person->des = $summary;
            $person->source = $source;
            $person->basic_info = $basic_arr;
            $person->extra_info = $info_arr;
            $person->avatar = $avatar;
            $person->founderDetailLink = $link;
            $person->save();
            echo "收录成功!";
        } else {
            echo "已经存在" . $name;
        }
//        dd($person);

        dd($person, $name, $avatar, $summary, $basic_arr, $info_arr);
    }

    public function baidubaike_org($html)
    {
        $name = $html->find(".lemmaWgt-lemmaTitle-title h1", 0)->innertext;
        $summary = $html->find(".lemma-summary", 0)->innertext;
        $summary = trim($summary);
        $basic = $html->find(".basicInfo-block .basicInfo-item");
        $avatar = $html->find(".summary-pic img", 0)->src;
        $des = $html->find(".level-2");
        $counter = 0;
        $basic_arr = [];
        $info_arr = [];
        $arr_tmp = [];
        foreach ($basic as $k => $v) {
            if ($counter % 2 == 0) {
                $arr_tmp[] = trim(preg_replace("/&nbsp;/", "", $v->innertext));
            } else {
                $arr_tmp[] = trim($v->innertext);
                $basic_arr[] = $arr_tmp;
                $arr_tmp = [];
            }
            $counter++;
        }
        $info_tmp = [];
        foreach ($des as $k => $v) {
            $info_tmp[] = $v->find('h2', 0)->plaintext;
            $info_value = "";
            while (isset($v->next_sibling()->attr["class"]) && $v->next_sibling()->attr["class"] == "para") {
                $info_value = $info_value . $v->next_sibling();
                $v = $v->next_sibling();
            }
            $info_tmp[] = $info_value;
            $info_arr[] = $info_tmp;
            $info_tmp = [];

        }

        try {
            $ref = $html->find(".reference-list", 0)->innertext;
        } catch (\Exception $e) {

        }
        $source = "baidubaike";
        $tag = [];
        $link = "http://baike.baidu.com/item/" . $name;
        $org = Companies::where("name", $name)->orWhere("cpyDetailLink", $link)->get();
        if (count($org) == 0) {
            $org = new Founders();
            $org->name = $name;
            $org->des = $summary;
            $org->source = $source;
            $org->basic_info = $basic_arr;
            $org->extra_info = $info_arr;
            $org->avatar = $avatar;
            $org->cpyDetailLink = $link;
            $org->save();
            echo "收录成功!";
        } else {
            echo "已经存在" . $name;
        }
//        dd($person);

        dd($org, $name, $avatar, $summary, $basic_arr, $info_arr);
    }

    public function baidubaike_item($html)
    {
        $name = $html->find(".lemmaWgt-lemmaTitle-title h1", 0)->innertext;
        $summary = $html->find(".lemma-summary", 0)->innertext;
        $summary = trim($summary);
        $basic = $html->find(".basicInfo-block .basicInfo-item");
        try {
            $avatar = $html->find(".summary-pic img", 0)->src;
        } catch (\Exception $e) {
            $avatar = "";
        }

        $des = $html->find(".level-2");
        $counter = 0;
        $basic_arr = [];
        $info_arr = [];
        $arr_tmp = [];
        foreach ($basic as $k => $v) {
            if ($counter % 2 == 0) {
                $arr_tmp[] = trim(preg_replace("/&nbsp;/", "", $v->innertext));
            } else {
                $arr_tmp[] = trim($v->innertext);
                $basic_arr[] = $arr_tmp;
                $arr_tmp = [];
            }
            $counter++;
        }
        $info_tmp = [];
        foreach ($des as $k => $v) {
            $info_tmp[] = $v->find('h2', 0)->plaintext;
            $info_value = "";
            while (isset($v->next_sibling()->attr["class"]) && $v->next_sibling()->attr["class"] != "para-title level-2") {
//               echo $v->next_sibling()->attr["class"];
                $info_value = $info_value . $v->next_sibling();
                $v = $v->next_sibling();
            }
            $info_tmp[] = $info_value;
            $info_arr[] = $info_tmp;
            $info_tmp = [];

        }

//        dd($info_arr);

        try {
            $ref = $html->find(".reference-list", 0)->innertext;
        } catch (\Exception $e) {
            $ref = "";
        }
        $source = "baidubaike";
        $tag = ["医院", "三级其他"];
//        $tag = [];
        $link = "http://baike.baidu.com/item/" . $name;
        $item = Items::where("name", $name)->orWhere("cpyDetailLink", $link)->get();
        if (count($item) == 0) {
            $item = new Items();
            $item->name = $name;
            $item->des = $summary;
            $item->source = $source;
            $item->basic_info = $basic_arr;
            $item->extra_info = $info_arr;
            $item->avatar = $avatar;
            $item->cpyDetailLink = $link;
            $item->tags = $tag;
            $item->ref = $ref;
            $item->save();
            echo "收录成功!数秒后刷新该页面查看!";
        } else {
            echo "已经存在,跳转至<a href='/item/" . $name . "'>" . $name . "</a>查看!";
        }
        dd();
//        dd($person);

//        dd($item, $name, $avatar, $summary, $basic_arr, $info_arr);
    }


    public function wikibaike_item($html)
    {
        $name_tmp = $html->find("#firstHeading", 0)->plaintext;
        $name = explode("[", $name_tmp)[0];

        $des = $html->find("h2");
//        $counter = 0;
//        $basic_arr = [];
//        $info_arr = [];
//        $arr_tmp = [];
//        foreach ($basic as $k => $v) {
//            if ($counter % 2 == 0) {
//                $arr_tmp[] = trim(preg_replace("/&nbsp;/", "", $v->innertext));
//            } else {
//                $arr_tmp[] = trim($v->innertext);
//                $basic_arr[] = $arr_tmp;
//                $arr_tmp = [];
//            }
//            $counter++;
//        }
        $summary = "";
        $v = $html->find(".mw-parser-output p", 0);

        while ($v->tag == "p") {
            $summary = $summary . $v->outertext;
            $v = $v->next_sibling();
        }
//        echo $summary;

        $info_tmp = [];
        foreach ($des as $k => $v) {
            if ($v->innertext == "目录" || $v->innertext == "导航菜单")
                continue;
            $info_tmp[] = $v->find('.mw-headline', 0)->plaintext;
            $info_value = "";
//            echo $v;
            while (isset($v->next_sibling()->tag) && $v->next_sibling()->tag !== "h2") {
//               echo $v->next_sibling()->attr["class"];
                $info_value = $info_value . $v->next_sibling();
                $v = $v->next_sibling();
            }
            $info_tmp[] = $info_value;
            $info_arr[] = $info_tmp;
            $info_tmp = [];

        }

//        dd($info_arr);

//        try {
//            $ref = $html->find(".reference-list", 0)->innertext;
//        } catch (\Exception $e) {
//            $ref = "";
//        }
        $source = "wiki_cn";
//        $tag = ["医院", "三级未定等"];
//        $tag = [];
        $link = "https://zh.wikipedia.org/wiki/" . $name;
        $item = Items::where("name", $name)->orWhere("cpyDetailLink", $link)->get();
        if (count($item) == 0) {
            $item = new Items();
            $item->name = $name;
            $item->des = $summary;
            $item->source = $source;
            $item->basic_info = [];
            $item->extra_info = $info_arr;
            $item->avatar = "";
            $item->cpyDetailLink = $link;
//            $item->tags = $tag;
//            $item->ref = $ref;
            $item->save();
            echo "收录成功!点击<a href='/item/'".$name.">".$name."</a>查看!";
        } else {
            echo "已经存在,跳转至<a href='/item/" . $name . "'>" . $name . "</a>查看!";
        }
//        dd();
//        dd($person);

//        dd($item, $name, $avatar, $summary, $basic_arr, $info_arr);
    }

    public function start_job(Request $r)
    {
        $url = $r["url"];
        $this->builder($url, "baidubaike");
    }

    public function add($name)
    {
        $url = "http://baike.baidu.com/item/" . urlencode($name);
        $this->builder($url, "baidubaike_item");
    }

    public function wiki($name)
    {
        $url = "https://zh.wikipedia.org/wiki/" . urlencode($name);
        $this->builder($url, "wikibaike_item");
    }


    public function search_engine_keywords_starter($key)
    {
        if (preg_match("/^[\\w\\s\.]+$/", urldecode($key))) {
//            $url = "https://www.google.com.hk/search?safe=strict&biw=400&bih=300&tbs=qdr:m&tbm=nws&q=Scopio+Labs+raises+OR+gets&nfpr=1&sa=X&ved=0ahUKEwjC647ElYrUAhUBFZQKHcxADJYQvgUIHygB";
            $url = "https://www.google.com.hk/?gws_rd=ssl#&tbm=nws&tbs=qdr:m&q=" . urlencode($key . " raises OR gets");
//            echo $url;
        } else {
            $url = "http://news.baidu.com/ns?ct=1&rn=20&ie=utf-8&bs=" . urlencode($key . " 融资 | " . $key . " 并购 | " . $key . "IPO") . "&rsv_bp=1&sr=0&cl=2&f=8&prevct=no&tn=news&clk=sortbytime&word=" . urlencode($key . " 融资 | " . $key . " 并购 | " . $key . "IPO");;
//            $url = "https://www.google.com.hk/?gws_rd=ssl#&tbm=nws&q=" . urlencode($key . " 融资 OR 并购 OR IPO");
        }
        $this->builder($url, "search_engine_keywords", $key);
    }

    public function search_engine_keywords($r)
    {
//        dd($r);
        $name = $r["url"];
//        dd($r["url"]);
        $html = $r["html"];

        echo $r["html"];
        if (count($html->find("#content_left .result")) > 0) {
            echo "baidu start";
            $r = $html->find("#content_left .result");
            $count = 0;
            $fund_story = [];
            foreach ($r as $k => $v) {
                if ($count < 2) {
                    $tmp = [];
                    $link = $v->find(".c-title a", 0)->href;
                    $title = $v->find(".c-title a", 0)->innertext;
                    $source = $v->find(".c-author", 0)->innertext;
                    $tp = explode("&nbsp;", $source);
//                    dd($tp);
                    $source = $tp[0];
                    $time = $tp[2];
//                    $time = $v->find(".slp ._uQb", 0)->innertext;
                    if (strpos($time, "前") > 0) {
                        $time = Carbon::now()->format('Y-m-d H:i');
                    } else {
                        $time = Carbon::createFromFormat("Y年m月d日 H:i", $time)->format('Y-m-d H:i');
                    }
                    $time = Carbon::createFromTimestamp(strtotime($time), "Asia/shanghai")->format('Y-m-d H:i');
                    echo '<a href="' . $link . '">' . $title . '</a>  ' . $source . $time . '</br>';
//                    dd();
                    $tmp["link"] = $link;
                    $tmp["title"] = $title;
                    $tmp["source"] = $source;
                    $tmp["time"] = $time;
                    $fund_story[] = $tmp;
                    $count++;
                }

            }

            $fund = DailyFunds::where("company_df", $name)->get();
            $fund = $fund[0];
            $fund->fund_story = $fund_story;
            $fund->save();

        } else {
            echo "google start";
//            echo $html;
//            dd();
            $r = $html->find(".g");
            $count = 0;
            $fund_story = [];
            foreach ($r as $k => $v) {
                if ($count < 2 && count($v->find(".slp span")) > 0) {
                    $tmp = [];
                    $link = $v->find("h3 a", 0)->href;
                    $title = $v->find("h3 a", 0)->innertext;
                    $source = $v->find(".slp span", 0)->innertext;
                    $time = $v->find(".slp span", 2)->innertext;
                    if (strpos($time, "前") > 0) {
                        $time = Carbon::now()->format('Y-m-d H:i');
                    } else {
                        $time = Carbon::createFromFormat("Y年m月d日", $time)->format('Y-m-d H:i');
                    }
                    $time = Carbon::createFromTimestamp(strtotime($time), "Asia/shanghai")->format('Y-m-d H:i');
                    echo '<a href="' . $link . '">' . $title . '</a>  ' . $source . $time . '</br>';
                    $tmp["link"] = $link;
                    $tmp["title"] = $title;
                    $tmp["source"] = $source;
                    $tmp["time"] = $time;
                    $fund_story[] = $tmp;
                    $count++;
                }

            }
//        echo $r["url"];
            $fund = DailyFunds::where("company_df", $name)->get();
            $fund = $fund[0];
            $fund->fund_story = $fund_story;
            $fund->save();
        }

        dd($fund);
    }


}