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
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Http\Requests;
use Illuminate\Support\Facades\Storage;
use Psy\Exception\ErrorException;
use Illuminate\Database\Eloquent\Collection;
use TijsVerkoyen\CssToInlineStyles\Exception;


class CrunchBaseController
{
    public $pachong_name = "crunch-detail";
    public function parase_starter()
    {
        $files = scandir("/Users/wangpan/DataScraperWorks/".$this->pachong_name);
//        $files = scandir("/Users/wangpan/DataScraperWorks/crunch-detail");
//        dd($files);
        foreach ($files as $k => $v) {
            if (strpos($v, "crunch-detail") === false)
                continue;
            if ($this->check_file('/usr/local/laravel/storage/app/parase_log.txt', '/Users/wangpan/DataScraperWorks/crunch-detail/' . $v))
//            if ($this->check_file('/usr/local/laravel/storage/app/tag_fix.txt', '/Users/wangpan/DataScraperWorks/'.$this->pachong_name.'/' . $v))
                continue;
            $this->paraser_crunchbase_detail('/Users/wangpan/DataScraperWorks/'.$this->pachong_name.'/' . $v);
        }
    }

    /**
     * 解析抓取到的crunchbase详情页的xml文件,并将其存入MongoDB数据库
     * @param string $file 文件名
     */
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

        $avatar_company = $overview->item(0)->getElementsByTagName("avatar")->item(0)->nodeValue;

        $founders = $overview->item(0)->getElementsByTagName("founder")->item(0)->nodeValue;
        $founders = preg_replace("/(\s+)/", '', $founders);
        $founders = iconv('UTF-8', 'UTF-8//IGNORE', $founders);
        $founders = explode(',', $founders);

        $scale = $overview->item(0)->getElementsByTagName("scale")->item(0)->nodeValue;
        $site = $overview->item(0)->getElementsByTagName("site")->item(0)->nodeValue;

        $category = $overview->item(0)->getElementsByTagName("category")->item(0)->nodeValue;
//        $category = preg_replace("/(\s+)/", '', $category);
        $category = explode(',', $category);
        $_category = array();
        foreach ($category as $c => $s) {
            $_category[] = trim($s);
        }

        $detail = $overview->item(0)->getElementsByTagName("detail")->item(0)->nodeValue;
        $detail = preg_replace("/(\s+)/", ' ', $detail);
        $detail = iconv('UTF-8', 'UTF-8//IGNORE', $detail);

        $description = $overview->item(0)->getElementsByTagName("description")->item(0)->nodeValue;
        $description = preg_replace("/(\s+)/", ' ', $description);
        $description = iconv('UTF-8', 'UTF-8//IGNORE', $description);


        $contact = $overview->item(0)->getElementsByTagName("contact")->item(0)->nodeValue;
//        $contact = explode('|', $contact);

        $headquarters = $overview->item(0)->getElementsByTagName("headquarters")->item(0)->nodeValue;
        $item['name'] = trim($name);
        $item['time'] = $founded;
        $item['founder'] = $founders;
        $item['scale'] = trim($scale);
        $item['website'] = $site;
        $item['tags'] = $_category;
        $item['des'] = $description;
        $item['contactinfo'] = $contact;
        $item['location'] = $headquarters;
        $item['detail'] = $detail;
        $item['offices'] = array();
//        $item['avatar'] = $avatar_company;

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
                $leader_link = "";
                $leader_link = isset($v->getElementsByTagName('leader-link')->item(0)->nodeValue) ? $v->getElementsByTagName('leader-link')->item(0)->nodeValue : "";
                $type = $v->getElementsByTagName('type')->item(0)->nodeValue;
                $amount = $v->getElementsByTagName('amount')->item(0)->nodeValue;
                $amount_o = $amount;
                $amount = $this->money_format_crunch($amount);

                $fund_array['times'] = $date;
                $fund_array['organizations'] = array(array("name" => $leader, "link" => $leader_link));
                $fund_array['phase'] = $type;
                $fund_array['valuation'] = "";
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
                $team_array['detailLink'] = $name_url;
                $teams_array[] = $team_array;
            }
        }
        $item['members'] = $teams_array;
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
                $product_array['logo'] = trim(preg_replace("/(\s+)/", ' ', $avatar));
                $product_array['desc'] = preg_replace("/(\s+)/", ' ', $info);
                $product_array['desc'] = iconv('UTF-8', 'UTF-8//IGNORE', $product_array['desc']);
                $product_array['link'] = $name_url;
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
//                print_r(iconv_get_encoding());
                $title_charcode = preg_replace("/(\s+)/", ' ', $title);
                $title_charcode = iconv('UTF-8', 'UTF-8//IGNORE', $title_charcode);
//                $str_translit = iconv('UTF-8', 'UTF-8//TRANSLIT', $str);
//                $title_charcode =   iconv('UTF-8', 'UTF-8', $title_charcode);
                $new_array['title'] = $title_charcode;
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
            $count = 1;
            foreach ($invest as $k => $v) {
                if ($count == 1) {
                    $count++;
                    continue;
                }
                $invest_array = array();
                $date = $v->getElementsByTagName('date')->item(0)->nodeValue;
                $name = $v->getElementsByTagName('name')->item(0)->nodeValue;
                $amount = $v->getElementsByTagName('amount')->item(0)->nodeValue;
                $round = $v->getElementsByTagName('round')->item(0)->nodeValue;
                $name_url = $v->getElementsByTagName('name-url')->item(0)->nodeValue;
                $invest_array['date'] = $date;
                $invest_array['name'] = $name;
                $invest_array['amount'] = preg_replace("/(\s+)/", ' ', $amount);
                $invest_array['amount'] = trim(explode("/", $amount)[0]);
                $invest_array['round'] = preg_replace("/(\s+)/", ' ', $round);
                $invests_array[] = $invest_array;
            }
        }
        $item['investments'] = $invests_array;
//        dd($file);
//        dd($item);
//        save();
//        var_dump($file);
//        $status = true;
        $company = Companies::where('cpyDetailLink', '=', trim($path))->get()[0];
//        dd($path);
        $company->scale = $scale;
        $company->website = $site;
        $company->avatar = $avatar_company;
//        $item['name'] = trim($name);
        $company->time = $founded;
        $company->founder = $founders;
        $company->tags = $_category;
        $company->des = $description;
        $company->contactInfo = $contact;
        $company->location = $headquarters;
        $company->detail = $detail;
        $company->offices = array();
        $company->raiseFunds = $funds_array;
        $company->acquisitions = $acquisitions_array;
        $company->investments = $invests_array;
        $company->products = $products_array;
        $company->news = $news_array;
        $company->members = $teams_array;
        if ($item['name'] == "") {
            $this->parase_log($file, "error");
        } else {
            $status = $company->save();
            if ($status) {
                $this->parase_log($file, "info");
                echo $file . "&nbsp;&nbsp;&nbsp;&nbsp success in db.</br>";
//                dd($company);
            } else {
                $this->parase_log($file, "error");
            }
        }


//        var_dump($status);
//        dd($company);


//        dd($company);
    }

    /**
     * 格式化融资日期
     * @param $date
     * @return string
     */
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

    /**
     * 格式化融资额
     * @param $amount
     * @return float|string
     */
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

    /**
     * 格式化融资额,提取数字
     * @param string $str
     * @return string
     */
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

    /**
     * 写入日志
     * @param string $pam 日志内容
     * @param string $level 日志级别
     */
    function parase_log($pam = '', $level = "info")
    {
        if ($level == "error") {
            $fp = fopen("/usr/local/laravel/storage/app/parase_error.txt", "a+"); //文件被清空后再写入
        } else {
            $fp = fopen("/usr/local/laravel/storage/app/parase_log.txt", "a+"); //文件被清空后再写入
//            $fp = fopen("/usr/local/laravel/storage/app/tag_fix.txt", "a+"); //文件被清空后再写入

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

    /**
     * 比对日志
     * @param $file_cache 日志文件
     * @param $file   待比对内容
     * @return bool   返回值
     */
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