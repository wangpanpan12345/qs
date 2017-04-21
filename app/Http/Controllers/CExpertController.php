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
use App\Founders;
use Faker\Provider\tr_TR\DateTime;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Http\Requests;
use Illuminate\Support\Facades\Storage;
use Psy\Exception\ErrorException;
use Illuminate\Database\Eloquent\Collection;
use TijsVerkoyen\CssToInlineStyles\Exception;
use MongoDB\BSON\UTCDateTime;

/**
 * 临时类,用来分析入库抓取到的行业专家信息
 * Class CExpertController
 * @package App\Http\Controllers
 */
class CExpertController extends Controller
{

    public $pachong_name = "kr_detail";

    public function parase_starter()
    {

        $files = scandir("/Users/wangpan/DataScraperWorks/" . $this->pachong_name);
//        $files = scandir("/Users/wangpan/DataScraperWorks/crunch-detail");
        foreach ($files as $k => $v) {
            if (strpos($v, "kr_detail") === false)
                continue;
            if ($this->check_file('/usr/local/laravel/storage/app/parase_log.txt', '/Users/wangpan/DataScraperWorks/kr_detail/' . $v))
//            if ($this->check_file('/usr/local/laravel/storage/app/tag_fix.txt', '/Users/wangpan/DataScraperWorks/'.$this->pachong_name.'/' . $v))
                continue;
//            $this->paraser_crunchbase_detail('/Users/wangpan/DataScraperWorks/'.$this->pachong_name.'/' . $v);
//            $this->paraser_detail('/Users/wangpan/DataScraperWorks/' . $this->pachong_name . '/' . $v);
//            $this->extract_name('/Users/wangpan/DataScraperWorks/' . $this->pachong_name . '/' . $v);
            $this->parse_kr_detail('/Users/wangpan/DataScraperWorks/' . $this->pachong_name . '/' . $v);

        }
    }

    /**
     * 解析抓取到的详情页的xml文件,并将其存入MongoDB数据库
     * @param string $file 文件名
     */
    public function paraser_detail($file = "")
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
        $overview = $doc->getElementsByTagName("items");

        $name = $overview->item(0)->getElementsByTagName("name")->item(0)->nodeValue;

        $detail = $overview->item(0)->getElementsByTagName("detail")->item(0)->nodeValue;

        $avatar = $overview->item(0)->getElementsByTagName("avatar")->item(0)->nodeValue;
//        $avatar = "http://www.cae.cn".$avatar;

        $item['name'] = trim($name);
        $item['detail'] = $detail;
        $detail = nl2br($detail);
        $POS = strpos($detail, $name);
        $detail = substr($detail, $POS);
        $detail = preg_replace("/(\[显示部分\]|\[显示全部\]|编辑本段回目录|目录)|/", "", $detail);
        $detail = preg_replace("/<br \/>\<br \/>/", "<br \/>", $detail);
        $item['avatar'] = $avatar;

//        dd($detail);
        $person = Founders::where('founderDetailLink', trim($path))->orWhere("name", $name)->get();
        if (count($person) == 1) {
            if (in_array($person[0]["name"], ["金力", "程京", "韩家淮", "张明杰", "施一公"])) {
                return false;
            }
            dd($person[0]);
            $person = $person[0];
            $person->name = $name;
            $person->avatar = $avatar;
            $person->des = $detail;
            $person->tags = ["专家"];
        } else {
            $person = new Founders();
            $person->name = $name;
            $person->avatar = $avatar;
            $person->des = $detail;
            $person->tags = ["专家"];
        }
//        dd($item);

        if ($item['name'] == "") {
            $this->parase_log($file, "error");
        } else {
            $status = $person->save();

            if ($status) {
                $this->parase_log($file, "info");
                echo $file . "&nbsp;&nbsp;&nbsp;&nbsp success in db.</br>";
//                dd($company);
            } else {
                $this->parase_log($file, "error");
            }
            dd("");
        }
    }

    public function extract_name($file = "")
    {
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
        $overview = $doc->getElementsByTagName("items");
        $c = 0;
        foreach ($overview->item(0)->getElementsByTagName("name") as $k => $v) {
            $link = $overview->item(0)->getElementsByTagName("link")->item($c)->nodeValue;
            $name = $v->nodeValue;
            $c++;
            $person = Companies::where('name', trim($name))->get();
            if (count($person) > 0) {

            } else {
                $links = explode(".", $link);
                $link_id = $links[1];
                $url = "https://rong.36kr.com/project/" . $link_id;
                $this->parase_log($url);
//                dd($name);
            }

        }


    }

    public function parse_kr_detail($file = "")
    {
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
        $overview = $doc->getElementsByTagName("items");
        $name = $overview->item(0)->getElementsByTagName("name")->item(0)->nodeValue;
        $slogan = $overview->item(0)->getElementsByTagName("slogan")->item(0)->nodeValue;
        $des = $overview->item(0)->getElementsByTagName("des")->item(0)->nodeValue;
        $detail = $overview->item(0)->getElementsByTagName("detail")->item(0)->nodeValue;
        $des_last = trim(preg_replace("/查看更多/", "", $des));
        $fullname = $overview->item(0)->getElementsByTagName("fullname")->item(0)->nodeValue;
        $fullname_last = trim(preg_replace("/公司全称：/", "", $fullname));
        $time = $overview->item(0)->getElementsByTagName("date")->item(0)->nodeValue;
        $location = $overview->item(0)->getElementsByTagName("location")->item(0)->nodeValue;
        $tags = $overview->item(0)->getElementsByTagName("tags")->item(0)->nodeValue;
        $website = $overview->item(0)->getElementsByTagName("site")->item(0)->nodeValue;
        $avatar = $overview->item(0)->getElementsByTagName("avatar")->item(0)->getElementsByTagName("a")->item(0)->attributes[1]->nodeValue;
        $avatar_l = stripos($avatar, "(");
        $avatar_r = stripos($avatar, ")");

        $avatar_last = substr($avatar, $avatar_l + 1, $avatar_r - $avatar_l - 1);
//        dd($match[0]);
        $teams = $overview->item(0)->getElementsByTagName("teams");
        $products = $overview->item(0)->getElementsByTagName("products");
        $raiseFunds = $overview->item(0)->getElementsByTagName("raise");
        $item["name"] = $name;
        $item["fullname"] = $fullname;
        $item["slogan"] = $slogan;
        $item["des"] = $des;
        $item["detail"] = $detail;
        $item["time"] = trim($time);
        $item["location"] = $location;
        $item["website"] = $website;
        $item["tags"] = [$tags];
        $item["avatar"] = $avatar;
        //
        $funds_array = array();
        if ($raiseFunds->item(0)->getElementsByTagName('item')->length != 0) {
            foreach ($raiseFunds->item(0)->getElementsByTagName('item') as $k => $v) {
                $fund_array = array();
                $date = $v->getElementsByTagName('r_date')->item(0)->nodeValue;
                $date = $this->date_format_crunch($date);
                $leader = $v->getElementsByTagName('invest')->item(0)->nodeValue;
                $leader_link = "";
                $type = $v->getElementsByTagName('round')->item(0)->nodeValue;
                $amount = $v->getElementsByTagName('amount')->item(0)->nodeValue;
                $amount = preg_replace("/,/", '', $amount);
//                echo $amount;
                $num = $this->findNum($amount);
                $amount = $this->format_raise_fund($amount, $num);
//                dd($amount);
                $amount_o = $amount;
//                $amount = $this->money_format_crunch($amount);
                $fund_array['times'] = $date;
                $fund_array['organizations'] = array(array("name" => $leader, "link" => $leader_link));
                $fund_array['phase'] = $type;
                $fund_array['valuation'] = "";
                $fund_array['amount'] = $amount;
                $fund_array['amount_o'] = $amount_o;
                $funds_array[] = $fund_array;
            }
        }
        $item['raiseFunds'] = $funds_array;

        $teams_array = array();
        if ($teams->item(0)->getElementsByTagName('item')->length != 0) {
            foreach ($teams->item(0)->getElementsByTagName('item') as $k => $v) {
                $team_array = array();
                $pname = $v->getElementsByTagName('tname')->item(0)->nodeValue;
                $avatar = "";
                $title = $v->getElementsByTagName('ttile')->item(0)->nodeValue;
                $des = $v->getElementsByTagName('tdes')->item(0)->nodeValue;
//                $name_url = $v->getElementsByTagName('name-url')->item(0)->nodeValue;
                $team_array['name'] = $pname;
                $team_array['avatar'] = trim(preg_replace("/(\s+)/", ' ', $avatar));
                $team_array['title'] = $title;
                $team_array['des'] = trim($des);
//                $team_array['detailLink'] = $name_url;
                $teams_array[] = $team_array;
                $person = Founders::where("name", $pname)->get();
//                if (count($person) > 0) {
//                    dd($pname.$path);
//                }
                $insertArray = array();
                $person = new Founders();
                $insertArray["name"] = $pname;
                $insertArray["des"] = trim($des);
                $insertArray["workedCases"] = [["title" => $title, "name" => $name, "time" => ""]];
                $insertArray["created_at"] = new UTCDateTime(round(microtime(true) * 1000));
                $insertArray["updated_at"] = new UTCDateTime(round(microtime(true) * 1000));
//                dd($insertArray);
//                $id = \DB::collection('founders')->insertGetId($insertArray);
                $id = $person->insertGetId($insertArray);
//                $person->save();
                $teams_array[] = ["founder_id" => $id, "title" => $title];
//                dd($id);
            }
        }
        $item['members'] = $teams_array;

        $products_array = array();
        if ($products->item(0)->getElementsByTagName("item")->length != 0) {
            foreach ($products->item(0)->getElementsByTagName('item') as $k => $v) {
                $product_array = array();
                $name = $v->getElementsByTagName('p_name')->item(0)->nodeValue;
                $des = $v->getElementsByTagName('p_des')->item(0)->nodeValue;
                $product_array['desc'] = trim(preg_replace("/查看完整数据/", "", $des));
                $product_array['name'] = $name;
                $product_array['link'] = "";
                $products_array[] = $product_array;
            }
        }
        $item['products'] = $products_array;

        $company = Companies::where('cpyDetailLink', '=', trim($path))->get();
        if (count($company) > 0) {
            $company = $company[0];
            $company->website = $website;
            $company->avatar = $avatar_last;
//        $item['name'] = trim($name)
            $company->time = $time;
            $company->founder = "";
            $company->tags = [$tags];
            $company->des = $des_last;
            $company->location = $location;
            $company->detail = $detail;
            $company->slogan = $slogan;
            $company->offices = array();
            $company->raiseFunds = $funds_array;
            $company->fullName = $fullname_last;
            $company->acquisitions = [];
            $company->investments = [];
            $company->products = $products_array;
            $company->members = $teams_array;
            $company->referer = "36Kr";
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
        } else {
            $company = new Companies();
            $company->name = $name;
            $company->cpyDetailLink = $path;
            $company->website = $website;
            $company->avatar = $avatar_last;
            $company->time = $time;
            $company->founder = "";
            $company->tags = [$tags];
            $company->des = $des_last;
            $company->location = $location;
            $company->detail = $detail;
            $company->fullName = $fullname_last;
            $company->slogan = $slogan;
            $company->offices = array();
            $company->raiseFunds = $funds_array;
            $company->acquisitions = [];
            $company->investments = [];
            $company->products = $products_array;
            $company->members = $teams_array;
            $company->referer = "36Kr";
            if ($item['name'] == "") {
                $this->parase_log($file, "error");
            } else {
                $status = $company->save();
                if ($status) {
                    $this->parase_log($file, "info");
                    echo $file . "&nbsp;&nbsp;&nbsp;&nbsp success in db.</br>";
                } else {
                    $this->parase_log($file, "error");
                }
            }
        }
        dd($path);


    }


    /**
     * 解析抓取到的crunchbase详情页的xml文件,并将其存入MongoDB数据库
     * @param string $file 文件名
     */
    public function paraser_expert_detail($file = "")
    {

//        dd($file);
//        $file = '/usr/local/laravel/storage/app/policy_expert_312866339_2877708628.xml';

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
        $overview = $doc->getElementsByTagName("detail");
        $name = $overview->item(0)->getElementsByTagName("name")->item(0)->nodeValue;
        $email = $overview->item(0)->getElementsByTagName("email")->item(0)->nodeValue;
        $s_position = $overview->item(0)->getElementsByTagName("s_position")->item(0)->nodeValue;
        $s_position = preg_replace("/(\s+)/", '', $s_position);
        $des = $overview->item(0)->getElementsByTagName("aspect")->item(0)->nodeValue;
        $des = preg_replace("/(\s+)/", '', $des);
        $edu = $overview->item(0)->getElementsByTagName("edu")->item(0)->nodeValue;
        $edu = nl2br($edu);//将分行符"\r\n"转义成HTML的换行符"<br/>"
        $edu = explode("<br />", $edu);//"<br />"作为分隔切成数组
        $edu = preg_replace("/(\s+)/", '', $edu);
        $edu = array_unique($edu);
        foreach ($edu as $k => $v) {
            if (strlen($v) < 9) {
                unset($edu[$k]);
            } else {
                $edu[$k] = substr($v, 1);
                $edu[$k] = preg_replace('/(\d)|(\.)|(\-)/i', '', $edu[$k]);
                $edu_tmp = explode(";", $edu[$k]);
                $edu[$k] = ["name" => $edu_tmp[0], "major" => isset($edu_tmp[1]) ? "$edu_tmp[1]" : ""];
            }

        }

        $paper = $overview->item(0)->getElementsByTagName("paper")->item(0)->nodeValue;
        $paper = nl2br($paper);//将分行符"\r\n"转义成HTML的换行符"<br/>"
        $paper = explode("<br />", $paper);//"<br />"作为分隔切成数组
        $paper = preg_replace("/(\s+)/", '', $paper);
        $paper = array_unique($paper);
        foreach ($paper as $k => $v) {
            if (strlen($v) < 12) {
                unset($paper[$k]);
            } else {
                $paper[$k] = ["name" => $v];
            }

        }

        $project = $overview->item(0)->getElementsByTagName("project")->item(0)->nodeValue;
        $project = nl2br($project);//将分行符"\r\n"转义成HTML的换行符"<br/>"
        $project = explode("<br />", $project);//"<br />"作为分隔切成数组
        $project = preg_replace("/(\s+)/", '', $project);
        $project = array_unique($project);
        foreach ($project as $k => $v) {
            if (strlen($v) < 12) {
                unset($project[$k]);
            } else {
                $project[$k] = ["name" => substr($v, 1)];
            }

        }

        $work = $overview->item(0)->getElementsByTagName("work")->item(0)->nodeValue;
        $work = nl2br($work);//将分行符"\r\n"转义成HTML的换行符"<br/>"
        $work = explode("<br />", $work);//"<br />"作为分隔切成数组
        $work = preg_replace("/(\s+)/", '', $work);
        $work = array_unique($work);
        foreach ($work as $k => $v) {
            if (strlen($v) < 12) {
                unset($work[$k]);
            } else {
                $work[$k] = substr($v, 1);
                preg_match_all('/(\d+\.\d+)(\-\d+\.\d+)?/i', $work[$k], $result);
                $wenzi = preg_replace('/(\d)|(\-)/i', '', $work[$k]);
                $title = $wenzi;
                $work[$k] = explode(".", $title);
                $index = 1;
                foreach ($work[$k] as $a => $b) {
                    if ($b == "") {
                        unset($work[$k][$a]);
                    } else {
                        if ($index == 1) {
                            $work[$k]["name"] = $b;
                            unset($work[$k][$a]);
                        } elseif ($index == 2) {
                            $work[$k]["title"] = $b;
                            unset($work[$k][$a]);
                        } else {

                        }
                        $index++;
                    }

                }
                if (isset($result[0][0])) {
                    $work[$k]["time"] = $result[0][0];
//                    array_push($work[$k], $result[0][0]);
                }

            }

        }
        $item["founderDetailLink"] = $path;
        $item["__v"] = 0;
        $item["avatar"] = "";
        $item["name"] = $name;
        $item["email"] = $email;
        $item["s_position"] = $s_position;
        $item["des"] = $des;
        $item["edu"] = array_values($edu);
        $item["paper"] = array_values($paper);
        $item["project"] = array_values($project);
        $item["work"] = array_values($work);
        $item["tags"] = ["专家"];
        $Founders = Founders::where('founderDetailLink', '=', $path)->get();
        if ($Founders->count() == 0) {
//            echo $file;
//            dd($item);
            $person = new Founders();
            $person->name = $name;
            $person->__v = 0;
            $person->founderDetailLink = $path;
            $person->avatar = "";
            $person->des = "关注方向:" . $des;
            $person->s_position = $s_position;
            $person->mail = $email;
            $person->tel = "";
            $person->tags = ["专家"];
            $person->paper = array_values($paper);;
            $person->projects = array_values($project);
            $person->edu_background = array_values($edu);
            $person->workedCases = array_values($work);
            $person->founderCases = [];
            $person->patent = [];
            $person->save();
//        dd($item);
            echo $name . "</br></br>";
        }
    }


    /**
     * 格式化融资日期
     * @param $date
     * @return string
     */
    public function date_format_crunch($date)
    {
        if (stripos($date, ".")) {
            $date = Carbon::createFromFormat("Y.m", $date)->toDateString();
        } elseif (stripos($date, ",")) {
            $date = Carbon::createFromFormat("M, Y", $date)->toDateString();
        } else {
        }
        return $date;

    }

    public function format_raise_fund($raise, $num)
    {
        $m = 0;
        $n = "";
        $c = "";
        if (stripos($raise, "未透露") !== false || stripos($raise, "未公布") !== false) {
            return $raise;
        }
        if (stripos($raise, "数千") !== false) {
            $num = 3000;
        }
        if (stripos($raise, "数百") !== false) {
            $num = 300;
        }
        if (stripos($raise, "数亿") !== false) {
            $num = 3;
        }
        if (stripos($raise, "元及以上人民币") !== false) {
            $num = 1.5;
        }
        preg_match("/(万|亿).*(人民币|美元|欧元|英镑)/", $raise, $t);
        if (in_array("亿", $t)) {
            if ($num > 10) {
                $m = $num / 10;
                $n = "B";
            } else {
                $m = $num * 100;
                $n = "M";
            }
        }
        if (in_array("万", $t)) {
            if ($num > 100) {
                $m = $num / 100;
                $n = "M";
            } else {
                $m = $num * 100;
                $n = "K";
            }
        }
        if (in_array("人民币", $t)) {
            $c = "¥";
        }
        if (in_array("美元", $t)) {
            $c = "$";
        }
        if (in_array("欧元", $t)) {
            $c = "€";
        }
        return $c . $m . $n;
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