<?php

namespace App\Http\Controllers;

use App\Companies;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Http\Requests;
use Illuminate\Support\Facades\Storage;
use Psy\Exception\ErrorException;
use TijsVerkoyen\CssToInlineStyles\Exception;

class MailController extends Controller
{
    //

    public function send()
    {
        $name = '王攀';
        $flag = Mail::send('emails.test', ['name' => $name], function ($message) {
            $to = 'wangpanpan@geekheal.com';
            $message->to($to)->subject('qi.summit');
        });
        dd($flag);
        if ($flag) {
            echo '发送邮件成功，请查收！';
        } else {
            echo '发送邮件失败，请重试！';
        }
    }

    public function parase_starter()
    {
        $files = scandir("/Users/wangpan/DataScraperWorks/crunch-detail");

        foreach ($files as $k => $v) {
            if (strpos($v, 'crunch-detail') === false)
                continue;
            if ($this->check_file('/usr/local/laravel/storage/app/parase_log.txt', '/Users/wangpan/DataScraperWorks/crunch-detail/' . $v))
                continue;
            $this->paraser_crunchbase_detail('/Users/wangpan/DataScraperWorks/crunch-detail/' . $v);
        }
    }

    public function paraser_crunchbase_detail($file = "")
    {

//        dd($file);
//        $file = '/usr/local/laravel/storage/app/crunch-detail_249713795_1990031743副本.xml';

        $doc = new \DOMDocument();
        try {
            $doc->load($file);
        } catch (ErrorException $e) {
            echo 'Message: ' . $e->getMessage();
        }
        $path = $doc->getElementsByTagName("fullpath")->item(0)->nodeValue;

        $item = array();
        /**
         * overview
         */
        $overview = $doc->getElementsByTagName("overview");
        $name = $overview->item(0)->getElementsByTagName("name")->item(0)->nodeValue;
        $founded = $overview->item(0)->getElementsByTagName("founded")->item(0)->nodeValue;

        $founders = $overview->item(0)->getElementsByTagName("founder")->item(0)->nodeValue;
        $founders = preg_replace("/(\s+)/", '', $founders);
        $founders = explode(',', $founders);

        $scale = $overview->item(0)->getElementsByTagName("scale")->item(0)->nodeValue;
        $site = $overview->item(0)->getElementsByTagName("site")->item(0)->nodeValue;

        $category = $overview->item(0)->getElementsByTagName("category")->item(0)->nodeValue;
        $category = preg_replace("/(\s+)/", '', $category);
        $category = explode(',', $category);

        $detail = $overview->item(0)->getElementsByTagName("detail")->item(0)->nodeValue;
        $detail = preg_replace("/(\s+)/", ' ', $detail);

        $description = $overview->item(0)->getElementsByTagName("description")->item(0)->nodeValue;
        $description = preg_replace("/(\s+)/", ' ', $description);

        $contact = $overview->item(0)->getElementsByTagName("contact")->item(0)->nodeValue;
        $contact = explode('|', $contact);

        $headquarters = $overview->item(0)->getElementsByTagName("headquarters")->item(0)->nodeValue;
        $item['name'] = trim($name);
        $item['founded'] = $founded;
        $item['founders'] = $founders;
        $item['scale'] = $scale;
        $item['website'] = $site;
        $item['tags'] = $category;
        $item['desc'] = $description;
        $item['contact'] = $contact;
        $item['location'] = $headquarters;
        $item['detail'] = $detail;
//        dd($item);
        /**
         * funds
         */
        $funds = $doc->getElementsByTagName("fundings");
        $funds_array = array();
        if ($funds->item(0)->getElementsByTagName("funding")->length != 0) {
            $fund = $funds->item(0)->getElementsByTagName("funding")->item(0)->getElementsByTagName("item");
            $count = 1;
            foreach ($fund as $k => $v) {
                if ($count == 1) {
                    $count++;
                    continue;
                }
                $fund_array = array();
                $date = $v->getElementsByTagName('date')->item(0)->nodeValue;
                $date = $this->date_format_crunch($date);
                $leader = $v->getElementsByTagName('leader')->item(0)->nodeValue;
                $type = $v->getElementsByTagName('type')->item(0)->nodeValue;
                $amount = $v->getElementsByTagName('amount')->item(0)->nodeValue;
                $amount_o = $amount;
                $amount = $this->money_format_crunch($amount);

                $fund_array['date'] = $date;
                $fund_array['leader'] = $leader;
                $fund_array['type'] = $type;
                $fund_array['amount'] = trim(preg_replace("/\//", '', $amount));
                $fund_array['amount_o'] = trim(preg_replace("/\//", '', $amount_o));
                $funds_array[] = $fund_array;
            }
        }
        $item['raiseFunds'] = $funds_array;
//        dd($item);
        /**
         * acquisitions
         */
        $acquisitions = $doc->getElementsByTagName("acquisitions");
        $acquisitions_array = array();
        if ($acquisitions->item(0)->getElementsByTagName("acquisition")->length != 0) {
            $acquisition = $acquisitions->item(0)->getElementsByTagName("acquisition")->item(0)->getElementsByTagName("item");
            foreach ($acquisition as $k => $v) {
                $acquisition_array = array();
                $date = $v->getElementsByTagName('date')->item(0)->nodeValue;
                $leader = $v->getElementsByTagName('name')->item(0)->nodeValue;
//                $type = $v->getElementsByTagName('name-url')->item(0)->nodeValue;
                $amount = $v->getElementsByTagName('amount')->item(0)->nodeValue;
                $acquisition_array['date'] = $date;
                $acquisition_array['name'] = $leader;
//                $acquisition_array['type'] = $type;
                $acquisition_array['amount'] = $amount;
                $acquisitions_array[] = $acquisition_array;
            }
        }
        $item['acquisitions'] = $acquisitions_array;
//        dd($acquisitions_array);
        /**
         * teams
         */
        $teams = $doc->getElementsByTagName("teams");
        $teams_array = array();
        if ($teams->item(0)->getElementsByTagName("team")->length != 0) {
            $team = $teams->item(0)->getElementsByTagName("team")->item(0)->getElementsByTagName('item');
            foreach ($team as $k => $v) {
                $team_array = array();
                $name = $v->getElementsByTagName('name')->item(0)->nodeValue;
                $avatar = $v->getElementsByTagName('avatar')->item(0)->nodeValue;
                $title = $v->getElementsByTagName('title')->item(0)->nodeValue;
                $name_url = $v->getElementsByTagName('name-url')->item(0)->nodeValue;
                $team_array['name'] = $name;
                $team_array['avatar'] = trim(preg_replace("/(\s+)/", ' ', $avatar));
                $team_array['title'] = $title;
                $team_array['name_url'] = $name_url;
                $teams_array[] = $team_array;
            }
        }
        $item['teams'] = $teams_array;
//        dd($teams_array);
        /**
         * products
         */
        $products = $doc->getElementsByTagName("products");
        $products_array = array();
        if ($products->item(0)->getElementsByTagName("product")->length != 0) {
            $product = $products->item(0)->getElementsByTagName("product")->item(0)->getElementsByTagName('item');
            foreach ($product as $k => $v) {
                $product_array = array();
                $name = $v->getElementsByTagName('name')->item(0)->nodeValue;
                $avatar = $v->getElementsByTagName('avatar')->item(0)->nodeValue;
                $info = $v->getElementsByTagName('info')->item(0)->nodeValue;
                $name_url = $v->getElementsByTagName('name-url')->item(0)->nodeValue;
                $product_array['name'] = $name;
                $product_array['avatar'] = trim(preg_replace("/(\s+)/", ' ', $avatar));
                $product_array['info'] = preg_replace("/(\s+)/", ' ', $info);
                $product_array['name_url'] = $name_url;
                $products_array[] = $product_array;
            }
        }
        $item['products'] = $products_array;
//        dd($products_array);
        /**
         * news
         */
        $news = $doc->getElementsByTagName("news");
        $news_array = array();
        if ($news->item(0)->getElementsByTagName("new")->length != 0) {
            $new = $news->item(0)->getElementsByTagName("new")->item(0)->getElementsByTagName('item');
            foreach ($new as $k => $v) {
                $new_array = array();
                $date = $v->getElementsByTagName('date')->item(0)->nodeValue;
                $source = $v->getElementsByTagName('source')->item(0)->nodeValue;
                $title = $v->getElementsByTagName('title')->item(0)->nodeValue;
                $name_url = $v->getElementsByTagName('link')->item(0)->nodeValue;
                $new_array['date'] = $date;
                $new_array['source'] = $source;
                $new_array['title'] = preg_replace("/(\s+)/", ' ', $title);
                $new_array['link'] = preg_replace("/(\s+)/", ' ', $name_url);
                $news_array[] = $new_array;
            }
        }
        $item['news'] = $news_array;
        /**
         * investment
         */
        $invests = $doc->getElementsByTagName("invests");
        $invests_array = array();
        if ($invests->item(0)->getElementsByTagName("invest")->length != 0) {
            $invest = $invests->item(0)->getElementsByTagName("invest")->item(0)->getElementsByTagName('item');
            foreach ($invest as $k => $v) {
                $invest_array = array();
                $date = $v->getElementsByTagName('date')->item(0)->nodeValue;
                $name = $v->getElementsByTagName('name')->item(0)->nodeValue;
                $amount = $v->getElementsByTagName('amount')->item(0)->nodeValue;
                $round = $v->getElementsByTagName('round')->item(0)->nodeValue;
                $name_url = $v->getElementsByTagName('name-url')->item(0)->nodeValue;
                $invest_array['date'] = $date;
                $invest_array['name'] = $name;
                $invest_array['amount'] = preg_replace("/(\s+)/", ' ', $amount);
                $invest_array['round'] = preg_replace("/(\s+)/", ' ', $round);
                $invests_array[] = $invest_array;
            }
        }
        $item['investment'] = $invests_array;
//        dd($item);
//        save();
        echo $file . "ok</br>";
        $status = true;
//        $company = Companies::where('cpyDetailLink', '=', $path)->take(1)->get();
//        dd($company);
////        $company->name = $name;
//        $status = $company->save();
////        var_dump($title);
        if ($status)
            $this->parase_log($file);
    }

    public function date_format_crunch($date)
    {
        if (stripos($date, ".")) {
            $date = Carbon::createFromFormat("Y.m.d", $date)->toDateString();
        } elseif (stripos($date, ",")) {
            $date = Carbon::createFromFormat("M, Y", $date)->toDateString();
        } else {
        }
        return $date;

    }

    public function money_format_crunch($amount)
    {
        if (stripos($amount, "¥") !== false && stripos($amount, "万")) {
            $num = $this->findNum($amount);
            $amount = ($num == "" ? $amount : $num / 6.7);
        }
//                        elseif (stripos($amount, "人民币") !== false && stripos($amount, "万")) {
//                            $num = $this->findNum($amount);
//                            $amount = ($num == "" ? $amount : $num / 6.7);
//                        } elseif (stripos($amount, "美元") !== false && stripos($amount, "万")) {
//                            $num = $this->findNum($amount);
//                            $amount = ($num == "" ? $amount : $num);
//                        } elseif (stripos($amount, "美元") !== false && stripos($amount, "亿")) {
//                            $num = $this->findNum($amount);
//                            $amount = ($num == "" ? $amount : $num * 10000);
//                        } elseif (stripos($amount, "人民币") !== false && stripos($amount, "亿")) {
//                            $num = $this->findNum($amount);
//                            $amount = ($num == "" ? $amount : $num * 10000 / 6.7);
//                        }
        elseif (stripos($amount, "$") !== false && stripos($amount, "M")) {
            $num = $this->findNum($amount);
            $amount = ($num == "" ? $amount : $num * 100);
        } elseif (stripos($amount, "$") !== false && stripos($amount, "B")) {
            $num = $this->findNum($amount);
            $amount = ($num == "" ? $amount : $num * 100000);
        } elseif (stripos($amount, "$") !== false && stripos($amount, "k")) {
            $num = $this->findNum($amount);
            $amount = ($num == "" ? $amount : $num / 10);
        } elseif (stripos($amount, "€") !== false && stripos($amount, "k")) {
            $num = $this->findNum($amount);
            $amount = ($num == "" ? $amount : $num * 1.11 / 10);
        } elseif (stripos($amount, "€") !== false && stripos($amount, "M")) {
            $num = $this->findNum($amount);
            $amount = ($num == "" ? $amount : $num * 111);
        } elseif (stripos($amount, "€") !== false && stripos($amount, "B")) {
            $num = $this->findNum($amount);
            $amount = ($num == "" ? $amount : $num * 111000);
        } elseif (stripos($amount, "£") !== false && stripos($amount, "k")) {
            $num = $this->findNum($amount);
            $amount = ($num == "" ? $amount : $num * 1.23 / 10);
        } elseif (stripos($amount, "£") !== false && stripos($amount, "M")) {
            $num = $this->findNum($amount);
            $amount = ($num == "" ? $amount : $num * 123);
        } elseif (stripos($amount, "£") !== false && stripos($amount, "B")) {
            $num = $this->findNum($amount);
            $amount = ($num == "" ? $amount : $num * 123000);
        } elseif (stripos($amount, "$") !== false && stripos($amount, "万")) {
            $num = $this->findNum($amount);
            $amount = ($num == "" ? $amount : $num);
        } elseif (stripos($amount, "$") !== false && stripos($amount, "亿")) {
            $num = $this->findNum($amount);
            $amount = ($num == "" ? $amount : $num * 10000);
        } elseif (stripos($amount, "¥") !== false && stripos($amount, "亿")) {
            $num = $this->findNum($amount);
            $amount = ($num == "" ? $amount : $num * 10000 / 6.7);
        } else {
        }
        return $amount;
    }

    public function findNum($str = '')
    {
        $str = trim($str);
        if (empty($str)) {
            return '';
        }
        $reg = '/(\d+(\.\d+)?)/is';//匹配数字的正则表达式
        preg_match_all($reg, $str, $result);
        if (is_array($result) && !empty($result) && !empty($result[1]) && !empty($result[1][0])) {
            return $result[1][0];
        }
        return '';
    }

    public function translate($argv)
    {
//        var_dump($argv);
        if (empty($argv[1])) {
            $from = "auto";
        } else {
            $from = $argv[1];
        }

        if (empty($argv[2])) {
            $to = "auto";
        } else {
            $to = $argv[2];
        }

        $url = "http://brisk.eu.org/api/translate.php?from=$from&to=$to&text=$argv[0]";
        var_dump($url);
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $data = curl_exec($curl);

        curl_close($curl);

        $json = json_decode($data);
        echo $json->{'res'} . "\n";
    }

    function parase_log($pam = '')
    {
        $fp = fopen("/usr/local/laravel/storage/app/parase_log.txt", "a+"); //文件被清空后再写入
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

    function check_file($file_cache, $file)
    {
        if (file_exists($file_cache) && is_readable($file_cache)) {
            $f = fopen($file_cache, "r");
            while (!feof($f)) {
                $line = fgets($f);
                if (preg_replace("/(\s+)/", '', $line) == $file)
                    return true;
            }
            return false;
            fclose($f);
        }
    }

}
