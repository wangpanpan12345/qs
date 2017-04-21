<?php

namespace App\Media;

use App\DailyNews;
use App\DailyFunds;
use App\Companies;
use JonnyW\PhantomJs\Client;
use JonnyW\PhantomJs\DependencyInjection\ServiceContainer;
use Carbon\Carbon;


class DealFund
{
    /**
     * 将融资信息插入数据库中
     * @author wangpan
     * @param  array $fund_array
     * @return bool
     */
    public function intoDb($fund_array)
    {
        $name = isset($fund_array["company_df"]) ? $fund_array["company_df"] : "";
        $url = isset($fund_array["link"]) ? $fund_array["link"] : "";
        //查询是否存在该公司,使用精准匹配公司名查询
        $is_company = $this->queryCompany($name);

        if ($is_company) {
            $is_up = $this->updateFund($fund_array, $is_company);
            if ($is_up) {
                $fund_array->is_pub = 1;
                $r = $fund_array->save();
                dd($r);
            }
        } else {
            $source = $fund_array->source;
            $this->createCompany($url, $source);
        }
        return true;
    }

    /**
     * 查询是否存在该公司
     * @param string $company
     * @return bool
     */
    public function queryCompany($company)
    {
        $company_model = Companies::where("name", $company)->first();

        if (!$company_model)
            return false;
        return $company_model;
    }

    public function createCompany($url, $source)
    {
        if ($source == "it桔子") {
            $this->paraseItjz($url);
        } elseif ($source == "创业邦") {
            $this->paraseCyb($url);
        }
    }

    /**
     * 更新查询到的公司的融资信息
     * @param array $fund_array
     * @param object $is_company
     * @return bool
     */
    public function updateFund($fund_array, $is_company)
    {
        $f_origin = $is_company->raiseFunds;
        $num = $this->findNum($fund_array["amount"]);
        $amount = $this->format_raise_fund($fund_array["amount"], $num);
        $invest = [];
        foreach ($fund_array["invest"] as $k => $v) {
            $invest[] = [
                "link" => "",
                "name" => $v
            ];
        }
        $f_new_ = [
            "amount" => $amount,
            "amount_o" => $amount,
            "phase" => $fund_array["round"],
            "times" => Carbon::parse($fund_array["pub_date_f"]["date"])->toDateString(),
            "organizations" => $invest
        ];
        if (is_null($f_origin)) {
            $f_origin = [];
        }

        array_push($f_origin, $f_new_);
        dd($f_origin, $fund_array->company_df);
        $is_company->raiseFunds = $f_origin;
        $result = $is_company->save();
        return $result;
    }

    public function paraseItjz($url)
    {
        echo "itJZ 未创建数据" . $url . "<br/>";
    }

    public function paraseCyb($url)
    {
        echo "CYB 未创建数据" . $url . "<br/>";
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

    /**
     *
     * @param string $pam
     * @param string $level
     */
    public
    function parase_log($pam = '', $level = "info")
    {
        $exist = storage_path('/app/exist.txt');
        $policy = storage_path('/app/policy.txt');
        if ($level == "error") {
            $fp = fopen($exist, "a+"); //文件被清空后再写入
        } elseif ($level == "policy") {
            $fp = fopen($policy, "a+");
        } else {
            $fp = fopen($exist, "a+"); //文件被清空后再写入
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

//    public
//    function save($dn)
//    {
//
//        $daily_s = DailyNews::where('link', '=', $dn["link"])->get();
//
//        if ($daily_s->count() == 0) {
//            $daily = new DailyNews();
//            $daily->title = $dn["title"];
//            $daily->link = $dn["link"];
//            $daily->source = $dn["source"];
//            $daily->pub_date = $dn["time"];
//            $daily->company = "";
//            $daily->tags = [];
//            $daily->flag = 1;
//            $daily->isread = 0;
//            $daily->is_pub = 0;
//            $daily->priority = isset($dn["priority"]) ? $dn["priority"] : 9;
//            $daily->group = 't';
//            $status = $daily->save();
//            if ($status) {
//
//            } else {
//                $this->parase_log($dn["link"]);
//            }
//        }
//    }
//
//    public
//    function save_p($dn)
//    {
//
//        $daily_s = DailyNews::where('link', '=', $dn["link"])->get();
//
//        if ($daily_s->count() == 0) {
//            $daily = new DailyNews();
//            $daily->title = $dn["title"];
//            $daily->link = $dn["link"];
//            $daily->source = $dn["source"];
//            $daily->pub_date = $dn["time"];
//            $daily->company = "";
//            $daily->tags = [];
//            $daily->flag = 2;
//            $daily->isread = 0;
//            $daily->is_pub = 0;
//            $daily->priority = isset($dn["priority"]) ? $dn["priority"] : 9;
//            $daily->group = 'policy';
//            $status = $daily->save();
//            if ($status) {
//
//            } else {
//                $this->parase_log($dn["link"]);
//            }
//        }
//    }

    public
    function save_funds($dn)
    {

        $daily_s = DailyFunds::where('link', '=', $dn["link"])->orWhere('company', 'like', '%' . $dn["company"] . '%')->take(1000)->get();

        if ($daily_s->count() == 0) {
            $daily = new DailyFunds();
            $daily->company_df = $dn["company_df"];
            $daily->link = $dn["link"];
            $daily->source = $dn["source"];
            $daily->pub_date_f = $dn["time"];
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
