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
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Http\Requests;
use Illuminate\Support\Facades\Storage;
use Psy\Exception\ErrorException;
use Illuminate\Database\Eloquent\Collection;
use TijsVerkoyen\CssToInlineStyles\Exception;
use MongoDB\BSON\UTCDateTime;


class CrunchBaseController
{

//    public $pachong_name = "crunch-detail";
    public $pachong_name = "person_detail";
//    public $pachong_name = "policy_expert";
//    public $pachong_name = "gcyys";
//    public $pachong_name = "health_care";
//    public $pachong_name = "bio-list";
//    public $pachong_name = "medical-device";
//    public $pachong_name = "therapeutics";
//    public $pachong_name = "pharmaceutical";
//    public $pachong_name = "health-diagnostics";
//    public $pachong_name = "hospital-crunch";
//    public $pachong_name = "life-science";
//    public $pachong_name = "neuroscience";
//    public $pachong_name = "bioinformatics";
//    public $pachong_name = "wellness";


    public function parase_starter()
    {

        $files = scandir("/Users/wangpan/DataScraperWorks/" . $this->pachong_name);
//        $files = scandir("/Users/wangpan/DataScraperWorks/crunch-detail");
//        dd($files);
        foreach ($files as $k => $v) {
            if (strpos($v, $this->pachong_name) === false)
                continue;
//            if ($this->check_file('/usr/local/laravel/storage/app/parase_log.txt', '/Users/wangpan/DataScraperWorks/' . $this->pachong_name . '/' . $v))
            if ($this->check_file('/usr/local/laravel/storage/app/parase_person.txt', '/Users/wangpan/DataScraperWorks/' . $this->pachong_name . '/' . $v))

//            if ($this->check_file('/usr/local/laravel/storage/app/tag_fix.txt', '/Users/wangpan/DataScraperWorks/'.$this->pachong_name.'/' . $v))
                continue;
//            dd('/Users/wangpan/DataScraperWorks/'.$this->pachong_name.'/' . $v);
//            $this->paraser_crunchbase_detail('/Users/wangpan/DataScraperWorks/' . $this->pachong_name . '/' . $v);
//            $this->paraser_expert_detail('/Users/wangpan/DataScraperWorks/' . $this->pachong_name . '/' . $v);
//            $this->parase_crunch_list('/Users/wangpan/DataScraperWorks/' . $this->pachong_name . '/' . $v);
            $this->parase_person_detail('/Users/wangpan/DataScraperWorks/' . $this->pachong_name . '/' . $v);

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
        $c_name = $overview->item(0)->getElementsByTagName("name")->item(0)->nodeValue;
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
        $item['name'] = trim($c_name);
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
        $item['avatar'] = $avatar_company;

//        dd($path);

        $company = Companies::where('cpyDetailLink', trim($path))->orWhere("name", $c_name)->get();
//        dd($company);
        if (count($company) > 0) {
//            dd($company);
            dd($file);
        }

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


                $insertArray = array();
                $person = new Founders();
                $insertArray["name"] = $name;
//                $insertArray["des"] = trim($des);
                $insertArray["founderDetailLink"] = $name_url;
                $insertArray["avatar"] = $avatar;
                $insertArray["workedCases"] = [["title" => $title, "name" => $c_name, "time" => ""]];
                $insertArray["created_at"] = new UTCDateTime(round(microtime(true) * 1000));
                $insertArray["updated_at"] = new UTCDateTime(round(microtime(true) * 1000));
//                $id = 1;
                $id = $person->insertGetId($insertArray);
//                $person->save();
                $teams_array[] = [
                    "founder_id" => $id,
                    "title" => $title,
                    "avatar" => $avatar,
                    "name" => $name,
                    "detailLink" => $name_url
                ];
//                $teams_array[] = $team_array;
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
                $amount_1 = trim(explode("/", $amount)[0]);

                $invest_array['date'] = $date;
                $invest_array['name'] = $name;

                $invest_array['amount'] = $amount_1;
                $invest_array['amount_o'] = $amount_1;
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
//        dd($name);

//       dd($company);
//        if (count($company) > 0) {
//            dd($path);
//            $company = $company[0];
//            $company->scale = $scale;
//            $company->website = $site;
//            $company->avatar = $avatar_company;
////        $item['name'] = trim($name);
//            $company->time = $founded;
//            $company->founder = $founders;
//            $company->tags = $_category;
//            $company->des = $description;
//            $company->contactInfo = $contact;
//            $company->location = $headquarters;
//            $company->detail = $detail;
//            $company->offices = array();
//            $company->raiseFunds = $funds_array;
//            $company->acquisitions = $acquisitions_array;
//            $company->investments = $invests_array;
//            $company->products = $products_array;
//            $company->news = $news_array;
//            $company->members = $teams_array;
//            if ($item['name'] == "") {
//                $this->parase_log($file, "error");
//            } else {
//                $status = $company->save();
//                if ($status) {
//                    $this->parase_log($file, "info");
//                    echo $file . "&nbsp;&nbsp;&nbsp;&nbsp success in db.</br>";
////                dd($company);
//                } else {
//                    $this->parase_log($file, "error");
//                }
//            }
//        } else {
        $company = new Companies();
        $company->scale = $scale;
        $company->website = $site;
        $company->cpyDetailLink = $path;
        $company->avatar = $avatar_company;
        $company->name = trim($c_name);
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
        $company->referer = "crunchbase";
//        dd($company);
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
//        dd($c_name);

//        }


//        var_dump($status);
//        dd($company);


//        dd($company);
    }

    /**
     * 解析并入库从crunchbase抓取到的人的信息
     * @param string $file
     */
    public function  parase_person_detail($file = "")
    {
//        dd($file);
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
        $info = $doc->getElementsByTagName("info");
        $detail = "";
        if ($info->item(0)->getElementsByTagName("item")->item(0)->getElementsByTagName("datail")->length != 0)
            $detail = $info->item(0)->getElementsByTagName("item")->item(0)
                ->getElementsByTagName("datail")->item(0)->nodeValue;

        $item["des"] = preg_replace("/(\n)/","<br/>",$detail);
//        dd($item["des"]);
        /**
         * edu
         */
        $edus = $doc->getElementsByTagName("edu");
        $edus_array = array();
        if ($edus->item(0)->getElementsByTagName("item")->length != 0) {
            $edu = $edus->item(0)->getElementsByTagName("item")->item(0)
                ->getElementsByTagName('edu_e')->item(0)->getElementsByTagName('item');
            foreach ($edu as $k => $v) {
                $edu_array = array();
                $school = $v->getElementsByTagName('school')->item(0)->nodeValue;
                $major = $v->getElementsByTagName('major')->item(0)->nodeValue;
                $avatar = $v->getElementsByTagName('avatar')->item(0)->nodeValue;
                $edu_array['name'] = $school;
                $edu_array['major'] = $major;
                $edu_array['e_avatar'] = $avatar;
                $edus_array[] = $edu_array;
            }
        }
        $item['edu_background'] = $edus_array;
        /**
         * jobs
         */
        $jobs = $doc->getElementsByTagName("jobs");
        $jobs_array = array();
        if ($jobs->item(0)->getElementsByTagName("item")->length != 0) {
            $job = $jobs->item(0)->getElementsByTagName("item")->item(0)
                ->getElementsByTagName('job_e')->item(0)->getElementsByTagName('item');
            foreach ($job as $k => $v) {
                $job_array = array();
                $company = $v->getElementsByTagName('company')->item(0)->nodeValue;
                $title = $v->getElementsByTagName('title')->item(0)->nodeValue;
                $avatar = $v->getElementsByTagName('avatar')->item(0)->nodeValue;
                $job_array['name'] = $company;
                $job_array['title'] = $title;
                $job_array['c_avatar'] = $avatar;
                $job_array['time'] = "";
                if ($company !== "")
                    $jobs_array[] = $job_array;

            }
        }
        $item['workedCases'] = $jobs_array;
        $ps = pathinfo($path);
        $pname = "/person/" . $ps["filename"];
        $pname_all = "https://www.crunchbase.com".$pname;
//        dd($pname);
//        $pname = "/person/david-sinclair-2";

        $person = Founders::where("founderDetailLink", $pname)->orWhere("founderDetailLink", $pname_all)->get();
//        dd($person);
        if (count($person) == 1) {
//            dd($person);
            $person = $person[0];
            $person->des = $item["des"];
            $person->workedCases = $item['workedCases'];
            $person->edu_background = $item['edu_background'];
            $status = $person->save();
            if ($status) {
                $this->parase_log($file, "info");
                echo $file . "&nbsp;&nbsp;&nbsp;&nbsp success in db.</br>";
//                dd($company);
            } else {
                $this->parase_log($file, "error");
            }
        }else{
            $person = $person[0];
            $person->des = $item["des"];
            $person->workedCases = $item['workedCases'];
            $person->edu_background = $item['edu_background'];
            $status = $person->save();
            if ($status) {
                $this->parase_log($file, "info");
                echo $file . "&nbsp;&nbsp;&nbsp;&nbsp success in db.</br>";
//                dd($company);
            } else {
                $this->parase_log($file, "error");
            }
//            dd($person);
        }
//        dd($item, $pname);

    }

    public function parase_crunch_list($file = "")
    {
        $doc = new \DOMDocument();
        try {
            $doc->load($file);
        } catch (ErrorException $e) {
            echo 'Message: ' . $e->getMessage();
        }
        /**
         * overview
         */
        $overview = $doc->getElementsByTagName("wellness")->item(0)->getElementsByTagName("item");
        foreach ($overview as $k => $v) {
            $link = $v->getElementsByTagName("name-url")->item(0)->nodeValue;
            $link_f = "https://www.crunchbase.com" . $link;
            $company = Companies::where('cpyDetailLink', trim($link_f))->count();
            if ($company == 0 && !$this->check_file('/usr/local/laravel/storage/app/parase_log.txt', trim($link_f))) {
                $this->parase_log($link_f);
            }
//            dd($link);
        }

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
//            $fp = fopen("/usr/local/laravel/storage/app/parase_person.txt", "a+");
        } else {
            $fp = fopen("/usr/local/laravel/storage/app/parase_person.txt", "a+");
//            $fp = fopen("/usr/local/laravel/storage/app/parase_log.txt", "a+"); //文件被清空后再写入
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