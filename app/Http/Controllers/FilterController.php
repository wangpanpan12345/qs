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
use App\CompanyIC;
use App\Items;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Requests;
use MongoDB\BSON\UTCDateTime;
use JonnyW\PhantomJs\Client;
use App\Founders;
use App\DailyFunds;


class FilterController extends Controller
{
    /**
     * 格式化公司全名
     */
    public function fullname()
    {
        global $tmp;

        Companies::where('fullName', 'exists', true)->where("fullName", "!=", "")->chunk(200, function ($companies) {
            global $tmp;
            $tmp = "";
            foreach ($companies as $company) {
                $fname_native = $company->fullName;

                if (strpos($fname_native, "：")) {

                    $fname_explode = explode("：", $fname_native);
                    if ($fname_explode[1] == "暂无" || $fname_explode[1] == "暂未收录" || $fname_explode[1] == "查看工商信息") {
                        $company->fullName = "";
                        $company->save();
                        echo $fname_explode[1];
                    } else {
                        $company->fullName = $fname_explode[1];
                        $company->save();
                        echo $fname_explode[1];
                    }

                }
                if ($fname_native == "查看工商信息") {
                    $company->fullName = "";
                    $company->save();
                }
                if ($tmp == $company->fullName) {
                    echo $company->fullName . "<br>";
                }
                $tmp = $company->fullName;
                echo $company->fullName . "<br>";

            }
        });
    }

    /**
     * 格式化公司规模
     */
    public function scale()
    {
        Companies::where('scale', 'exists', true)->where("scale", "!=", "")->chunk(200, function ($companies) {
            foreach ($companies as $company) {
                $fname_native = $company->scale;

                if (strpos($fname_native, "：")) {

                    $fname_explode = explode("：", $fname_native);
                    if ($fname_explode[1] == "暂无" || $fname_explode[1] == "暂未收录" || $fname_explode[1] == "不明确") {
                        $company->scale = "";
                        $company->save();
                        echo $fname_explode[1] . "<br>";
                    } else {
                        $company->scale = $fname_explode[1];
                        $company->save();
                        echo $fname_explode[1] . "<br>";
                    }

                } else if ($fname_native == "None found in Crunchbase" || $fname_native == "不明确") {
//                    echo $fname_native . "<br>";
                    $company->scale = "";
                    $company->save();
                    echo $fname_native . "<br>";
                } else if (strpos($fname_native, "|")) {
                    $fname_explode = explode("|", $fname_native);
                    $company->scale = $fname_explode[0];
                    $company->save();
//                    echo $fname_explode[0] . "<br>";
                } elseif (strpos($fname_native, "in Crunchbase")) {
                    $company->scale = "";
                    $company->save();
                    echo $fname_native . "<br>";
                } elseif (strpos($fname_native, "20人以下")) {
//                    echo mb_substr($fname_native, 0, -1). "<br>";
                    $company->scale = '11-20';
                    $company->save();
                    echo $fname_native . "<br>";
                } else {
                    $company->scale = trim($fname_native);
                    $company->save();
                    echo $fname_native . "<br>";
                }

            }
        });
    }

    public function get_scale()
    {
        $scale = Companies::distinct('scale')->where("scale", "!=", "")->get();
        dd($scale);
    }

    /**
     * extract all the org in the similar and distinct(step1)
     */
    public function get_similar()
    {
        global $similar;
        CompanyIC::chunk(200, function ($companies) {
            global $similar;
            foreach ($companies as $company) {
                if (isset($company->similar) && !empty($company->similar)) {
                    foreach ($company->similar as $k => $v) {
                        $sm = $v["companyName"];
                        if ($sm !== "" && $v["hangye"] == "医疗健康") {
                            $similar[] = $sm;
                            echo $sm . "<br/>";
                        }

                    }
                }
            }
        });

        $sl = array_unique($similar);
        echo count($sl) . "<br/><br/>";
        foreach ($sl as $sk => $sv) {
            echo $sv . "<br/>";
            $logs = new \App\Lib\QLogs();
            $logs->write_log($sv, "similar.txt");
        }

    }

    /**
     * extract all the org in the companies collection (step2)
     */
    public function current_fullname()
    {
        Companies::where('fullName', 'exists', true)->where("fullName", "!=", "")->chunk(200, function ($companies) {

            foreach ($companies as $company) {
                $fullName = $company->fullName;
                $logs = new \App\Lib\QLogs();
                $logs->write_log($fullName, "all_similar.txt");
            }
        });
    }

    /**
     * diff the step1 and step2 and insert into companies collection
     */

    public function distinct_fullname()
    {
        $log_s = new \App\Lib\QLogs();
        $similar = $log_s->read_file_array(storage_path("app/similar.txt"));
        $similar_all = $log_s->read_file_array(storage_path("app/all_similar.txt"));

        $diff = array_diff($similar, $similar_all);
        foreach ($diff as $k => $v) {
            if (preg_match("/^[\\w\\s\.,，&-]+$/", $v)) {

            } else {
                $company = new Companies();
                $company->tags = ["预处理", "待命名"];
                $company->fullName = $v;
                $company->name = $v;
                $company->save();
                echo $v . "<br/>";
            }
        }
    }

    public function get_investor()
    {
        global $similar;
        Companies::where('raiseFunds', 'exists', true)->chunk(200, function ($companies) {
            global $similar;
            foreach ($companies as $company) {
                if (!empty($company->raiseFunds)) {
                    foreach ($company->raiseFunds as $K => $V) {
                        if (!empty($V["organizations"])) {
                            foreach ($V["organizations"] as $kk => $vv) {
                                $similar[] = trim($vv["name"]);

                                echo $vv["name"] . "<br/>";
                            }
                        }
                    }
                }
            }
        });
        $sl = array_unique($similar);
        echo count($sl) . "<br/><br/>";
        foreach ($sl as $sk => $sv) {
            echo $sv . "<br/>";
            $logs = new \App\Lib\QLogs();
            $logs->write_log($sv, "investor.txt");
        }
    }

    public function get_crawl_investor()
    {
        $log_s = new \App\Lib\QLogs();
        $investor = $log_s->read_file_array(storage_path("app/investor.txt"));
        $similar_all = $log_s->read_file_array(storage_path("app/all_similar.txt"));
        $diff = array_diff($investor, $similar_all);

//        dd($diff);
        $count = 0;
        foreach ($diff as $k => $v) {
            if (preg_match("/^[\\w\\s\.,，&\-\/()'\+®]+$/", $v)) {

            } else {
                $count++;

//                echo $v . "<br/>";
//                $company = new Companies();
//                $company->tags = ["预处理", "待命名"];
//                $company->fullName = $v;
//                $company->name = $v;
//                $company->save();
                echo $v . "<br/>";
            }
        }
        echo $count;
    }

    public function get_company_from_invest()
    {
        $company = Companies::where("tags", "投资机构")->get();
        foreach ($company as $C => $V) {
            if (isset($V->IC->investevent) && !empty($V->IC->investevent))
                foreach ($V->IC->investevent as $ik => $iv) {
                    if ($iv["hangye1"] == "医疗健康")
                        if (Companies::where("fullName", $iv["company"])->count() == 0) {
                            $cn = new Companies();
                            $cn->avatar = (isset($iv["logo"]) && $iv["logo"] != null) ? $iv["logo"] : "";
                            $cn->fullName = $iv["company"];
                            $cn->des = (isset($iv["yewu"]) && $iv["yewu"] != null) ? $iv["yewu"] : "";
                            $cn->name = (isset($iv["product"]) && $iv["product"] != null) ? $iv["product"] : $iv["company"];
                            $cn->location = (isset($iv["location"]) && $iv["location"] != null) ? $iv["location"] : "";
                            $cn->save();
                            echo $iv["company"] . "<br/>";
                        }

                }
        }
    }

    public function get_invest_from_invest()
    {
        $company = Companies::where("tags", "投资机构")->get();
        $last = [];
        foreach ($company as $C => $V) {
            if (isset($V->IC->investevent) && !empty($V->IC->investevent))
                foreach ($V->IC->investevent as $ik => $iv) {
                    if (isset($iv["rongzi_map"]) && $iv["hangye1"] == "医疗健康") {
                        $invests = substr(trim($iv["rongzi_map"]), 1, -1);
                        $invest_array = explode(",", $invests);
                        $last = array_merge($last, $invest_array);
//                        dd($last);
//                        echo $iv["rongzi_map"] . "<br/>";
                    }

                }

        }
        $is_new_invest = array_unique($last);
        foreach ($is_new_invest as $K => $V) {
            $i_array = explode(":", $V);
            $i_name = $i_array[0];
            $i_link = "0";
            if (isset($i_array[1]) && $i_array[1] != "")
                $i_link = "http://www.tianyancha.com/company/" . $i_array[1];
            $is = Companies::where("name", $i_name)->count();
            if ($is == 0) {
                $n_i = new Companies();
                $n_i->name = $i_name;
                $n_i->cpyDetailLink = $i_link;
                $n_i->tags = ["投资机构"];
                $n_i->save();
                echo $i_name . "<br/>";
//                dd($i_name);
            }
        }
    }

    public function hospital_meta()
    {
        $hp = Items::where("tags", "医院")->get();
        $all = [];
        foreach ($hp as $k => $v) {
            if (isset($v["basic_info"])) {
                foreach ($v["basic_info"] as $K => $V) {
                    $all[] = $V[0];
                }
            }
        }
        dd(array_unique($all));
    }

    public function hospital_meta_format()
    {
        $o_data = $this->sanjia();
        $data_o = json_decode($o_data);
        foreach ($data_o as $K => $V) {

            $items = Items::where("tags", "三级乙等")->where("name", $V->hName)->get();
            if (count($items) == 0) {
                dd($V->hName);
            }
            $hps = $items[0];
            $hps->hType = $V->hType;
            $hps->provinceId = $V->provinceId;
            $hps->save();
//            dd($hps);
//            if($V->hName=="鸡西矿业集团总医院"){
//            echo '<a target="_blank" href = "https://qs.geekheal.net/item/' . $V->hName . '">' . $V->hName . '</a></br>';
//            }
//            dd($V->hName);
        }
        dd("OK!");


        $hp = Items::where("tags", "医院")->get();
//        dd($hp);
        $all = [];
//        $depart = ["原隶属于", "所属单位", "主管部门"];
//        $leader = ["医院院长", "院长", "现任院长"];
        $cdate = ["始建时间", "建立时间", "创建时间", "成立时间", "开放时间", "创办时间", "始建于"];
        foreach ($hp as $k => $v) {
            if (isset($v["basic_info"])) {
                $bs = [];
                foreach ($v["basic_info"] as $K => $V) {
//                    if (in_array($V[0], $leader)) {
//                        echo $V[1] . "</br>";
//                    }

                    if ($V[0] == "现任院长") {
                        echo $V[1] . "</br>";
                        echo $v["name"];
                        $bs[] = ["院长", $V[1]];
                    } else {
                        $bs[] = $V;
                    }
//                    if ($V[0] == "所属单位") {
//                        echo $V[1] . "</br>";
//                        echo $v["name"];
//                        $bs[] = ["主管部门", $V[1]];
//                    } else {
//                        $bs[] = $V;
//                    }
                }
//                $v->basic_info = $bs;
//                $v->save();
//                var_dump($bs);
//                echo "<br/>";

            }
        }
//        dd(array_unique($all));
    }

    public function sanjia()
    {
        return $o_data = '[{"provinceId":7224,"hName":"阿坝藏族羌族自治州人民医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7188,"hName":"巴彦淖尔市医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7224,"hName":"巴中市中心医院","hGrade":"三级乙等","hType":""},{"provinceId":7192,"hName":"白山市中心医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7234,"hName":"白银市第一人民医院","hGrade":"三级乙等","hType":""},{"provinceId":7232,"hName":"宝鸡市人民医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7228,"hName":"保山市人民医院","hGrade":"三级乙等","hType":""},{"provinceId":7190,"hName":"本溪市金山医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7208,"hName":"滨州市人民医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7208,"hName":"滨州市中心医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7224,"hName":"成都市第六人民医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7188,"hName":"赤峰学院附属医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7228,"hName":"楚雄州人民医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7200,"hName":"慈溪市妇幼保健院","hGrade":"三级乙等","hType":" 专科医院 "},{"provinceId":7200,"hName":"慈溪市人民医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7228,"hName":"大理白族自治州人民医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7186,"hName":"大同市第一人民医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7208,"hName":"单县中心医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7228,"hName":"德宏州人民医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7208,"hName":"德州市人民医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7200,"hName":"东阳市人民医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7208,"hName":"东营市第二人民医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7188,"hName":"鄂尔多斯市蒙医医院","hGrade":"三级乙等","hType":" 民族医院 "},{"provinceId":7212,"hName":"鄂钢医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7208,"hName":"肥城矿业中心医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7206,"hName":"丰城市人民医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7216,"hName":"佛山市三水区人民医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7190,"hName":"抚顺煤矿脑科医院","hGrade":"三级乙等","hType":" 专科医院 "},{"provinceId":7190,"hName":"抚顺市第二医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7190,"hName":"抚顺市第三医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7190,"hName":"抚顺市第五医院","hGrade":"三级乙等","hType":" 专科医院 "},{"provinceId":7196,"hName":"复旦大学附属金山医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7196,"hName":"复旦大学附属中山医院青浦分院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7234,"hName":"甘肃省武威肿瘤医院","hGrade":"三级乙等","hType":""},{"provinceId":7224,"hName":"甘孜藏族自治州人民医院","hGrade":"三级乙等","hType":""},{"provinceId":7218,"hName":"广西壮族自治区龙潭医院","hGrade":"三级乙等","hType":" 专科医院 "},{"provinceId":7216,"hName":"广州市花都区人民医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7226,"hName":"贵阳白志祥骨科医院","hGrade":"三级乙等","hType":" 专科医院 "},{"provinceId":7226,"hName":"贵州航天医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7226,"hName":"水矿总医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7184,"hName":"邯郸市中西医结合医院","hGrade":"三级乙等","hType":" 中西医结合医院"},{"provinceId":7200,"hName":"杭州市萧山区第一人民医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7216,"hName":"河源市人民医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7232,"hName":"核工业215医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7208,"hName":"菏泽市立医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7194,"hName":"黑龙江省林业第二医院","hGrade":"三级乙等","hType":" 专科医院 "},{"provinceId":7194,"hName":"黑龙江农垦九三管理局中心医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7184,"hName":"衡水市第四人民医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7186,"hName":"侯马市人民医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7188,"hName":"呼和浩特市第二医院","hGrade":"三级乙等","hType":" 专科医院 "},{"provinceId":7188,"hName":"呼和浩特市第一医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7188,"hName":"呼伦贝尔市人民医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7212,"hName":"湖北省新华医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7200,"hName":"湖州市第一人民医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7198,"hName":"淮安市第四人民医院","hGrade":"三级乙等","hType":" 专科医院 "},{"provinceId":7198,"hName":"淮安市第二人民医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7198,"hName":"淮安市第三人民医院","hGrade":"三级乙等","hType":" 专科医院 "},{"provinceId":7198,"hName":"淮安市肿瘤医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7202,"hName":"淮南新华医院","hGrade":"三级乙等","hType":""},{"provinceId":7212,"hName":"黄石市爱康医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7234,"hName":"嘉峪关市第一人民医院","hGrade":"三级乙等","hType":""},{"provinceId":7200,"hName":"金华市第二医院","hGrade":"三级乙等","hType":" 专科医院 "},{"provinceId":7200,"hName":"金华市人民医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7186,"hName":"晋中市第二人民医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7234,"hName":"靖远煤业集团有限责任公司总医院","hGrade":"三级乙等","hType":""},{"provinceId":7234,"hName":"酒钢医院","hGrade":"三级乙等","hType":""},{"provinceId":7208,"hName":"莱芜钢铁集团有限公司医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7234,"hName":"兰州石化总医院","hGrade":"三级乙等","hType":""},{"provinceId":7234,"hName":"兰州市第二人民医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7200,"hName":"乐清市人民医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7228,"hName":"丽江市人民医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7200,"hName":"丽水市第二人民医院","hGrade":"三级乙等","hType":" 专科医院 "},{"provinceId":7224,"hName":"凉山彝族自治州第二人民医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7192,"hName":"辽源矿业集团职工总医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7192,"hName":"辽源市中心医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7228,"hName":"临沧市人民医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7234,"hName":"临夏州人民医院","hGrade":"三级乙等","hType":""},{"provinceId":7208,"hName":"临沂市沂水中心医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7204,"hName":"龙岩市第二医院","hGrade":"三级乙等","hType":""},{"provinceId":7218,"hName":"南宁市第四人民医院","hGrade":"三级乙等","hType":" 专科医院 "},{"provinceId":7204,"hName":"南平市人民医院","hGrade":"三级乙等","hType":""},{"provinceId":7198,"hName":"南通市妇幼保健院","hGrade":"三级乙等","hType":" 专科医院 "},{"provinceId":7188,"hName":"包头医学院第二附属医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7200,"hName":"宁波市康宁医院","hGrade":"三级乙等","hType":" 专科医院 "},{"provinceId":7200,"hName":"宁波市鄞州人民医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7188,"hName":"宁城县蒙医中医医院","hGrade":"三级乙等","hType":" 民族医院 "},{"provinceId":7200,"hName":"平湖市第一人民医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7234,"hName":"平凉市第二人民医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7206,"hName":"萍乡市第二人民医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7228,"hName":"普洱市人民医院","hGrade":"三级乙等","hType":""},{"provinceId":7228,"hName":"普洱市中医院","hGrade":"三级乙等","hType":""},{"provinceId":7208,"hName":"青岛海慈医疗集团","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7208,"hName":"青岛市胶州中心医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7234,"hName":"庆阳市中医医院","hGrade":"三级乙等","hType":""},{"provinceId":7200,"hName":"衢州市中医医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7204,"hName":"泉州市第三医院","hGrade":"三级乙等","hType":" 专科医院 "},{"provinceId":7208,"hName":"日照市人民医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7208,"hName":"荣成市人民医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7200,"hName":"瑞安市人民医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7204,"hName":"三明市第二医院","hGrade":"三级乙等","hType":""},{"provinceId":7204,"hName":"厦门市第二医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7204,"hName":"厦门市第三医院","hGrade":"三级乙等","hType":""},{"provinceId":7186,"hName":"长治市第二人民医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7216,"hName":"汕尾逸挥基金医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7232,"hName":"商洛市中心医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7210,"hName":"商丘市第一人民医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7196,"hName":"上海交通大学医学院附属第三人民医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7196,"hName":"上海交通大学医学院附属新华医院（崇明）","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7196,"hName":"上海市第五人民医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7196,"hName":"上海市奉贤区中心医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7196,"hName":"上海市普陀区中心医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7196,"hName":"上海市杨浦区中心医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7214,"hName":"邵阳市第一人民医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7200,"hName":"绍兴第二医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7200,"hName":"绍兴市第六人民医院","hGrade":"三级乙等","hType":" 专科医院 "},{"provinceId":7200,"hName":"绍兴市第七人民医院","hGrade":"三级乙等","hType":" 专科医院 "},{"provinceId":7224,"hName":"四川省中西医结合医院","hGrade":"三级乙等","hType":""},{"provinceId":7192,"hName":"四平市第一人民医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7198,"hName":"苏州市第五人民医院","hGrade":"三级乙等","hType":" 专科医院 "},{"provinceId":7198,"hName":"苏州市立医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7212,"hName":"随州市中心医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7200,"hName":"台州市第一人民医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7200,"hName":"台州市立医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7208,"hName":"泰山医学院附属医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7208,"hName":"滕州市中心人民医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7182,"hName":"天津市安定医院","hGrade":"三级乙等","hType":" 专科医院 "},{"provinceId":7182,"hName":"天津市宝坻区人民医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7182,"hName":"天津市第三医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7182,"hName":"天津市第四医院","hGrade":"三级乙等","hType":" 专科医院 "},{"provinceId":7182,"hName":"天津市第四中心医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7182,"hName":"天津市第五中心医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7182,"hName":"天津市公安局安康医院","hGrade":"三级乙等","hType":" 专科医院 "},{"provinceId":7182,"hName":"天津市海河医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7182,"hName":"天津市蓟县人民医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7182,"hName":"天津市静海县医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7182,"hName":"天津市天和医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7182,"hName":"天津医科大学代谢病医院","hGrade":"三级乙等","hType":" 专科医院 "},{"provinceId":7192,"hName":"通化市中心医院","hGrade":"三级乙等","hType":""},{"provinceId":7188,"hName":"通辽市医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7232,"hName":"铜川矿务局中心医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7232,"hName":"铜川市人民医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7208,"hName":"威海市立医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7208,"hName":"威海市文登中心医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7208,"hName":"潍坊市益都中心医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7208,"hName":"潍坊医学院附属医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7200,"hName":"温岭市第一人民医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7200,"hName":"温州康宁医院","hGrade":"三级乙等","hType":" 专科医院 "},{"provinceId":7228,"hName":"文山州人民医院","hGrade":"三级乙等","hType":""},{"provinceId":7228,"hName":"文山州中医院","hGrade":"三级乙等","hType":""},{"provinceId":7188,"hName":"乌兰察布市中心医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7198,"hName":"无锡市传染病医院","hGrade":"三级乙等","hType":" 专科医院 "},{"provinceId":7218,"hName":"梧州市红十字会医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7218,"hName":"梧州市人民医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7212,"hName":"武钢第二职工医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7212,"hName":"武汉市普仁医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7234,"hName":"武威市中医院","hGrade":"三级乙等","hType":""},{"provinceId":7234,"hName":"武威市凉州医院","hGrade":"三级乙等","hType":""},{"provinceId":7234,"hName":"武威市人民医院","hGrade":"三级乙等","hType":""},{"provinceId":7232,"hName":"西安医学院第二附属医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7232,"hName":"西安医学院附属医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7186,"hName":"山西焦煤西山煤电集团公司职工总医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7188,"hName":"锡林郭勒盟医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7232,"hName":"咸阳市第一人民医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7214,"hName":"湘西自治州人民医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7206,"hName":"湘雅萍矿合作医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7208,"hName":"新汶矿业集团公司中心医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7198,"hName":"宿迁市人民医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7198,"hName":"徐州市第一人民医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7202,"hName":"宣城地区人民医院","hGrade":"三级乙等","hType":""},{"provinceId":7208,"hName":"烟台市莱阳中心医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7208,"hName":"烟台市烟台山医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7232,"hName":"延安市人民医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7208,"hName":"兖矿集团有限公司总医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7186,"hName":"阳泉市第三人民医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7194,"hName":"伊春林业管理局中心医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7240,"hName":"伊犁哈萨克自治州奎屯医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7206,"hName":"宜春市第三人民医院","hGrade":"三级乙等","hType":" 专科医院 "},{"provinceId":7200,"hName":"义乌市中心医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7200,"hName":"余姚市人民医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7232,"hName":"榆林市星元医院","hGrade":"三级乙等","hType":" 专科医院 "},{"provinceId":7218,"hName":"玉林市红十字会医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7228,"hName":"云南省第二人民医院","hGrade":"三级乙等","hType":""},{"provinceId":7228,"hName":"云南省第三人民医院","hGrade":"三级乙等","hType":""},{"provinceId":7228,"hName":"云南省农垦总局第一职工医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7208,"hName":"枣庄矿业集团公司中心医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7208,"hName":"沾化县人民医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7212,"hName":"长江航运总医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7216,"hName":"肇庆市端州区妇幼保健院","hGrade":"三级乙等","hType":" 妇幼保健院"},{"provinceId":7200,"hName":"浙江萧山医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7198,"hName":"镇江市第三人民医院","hGrade":"三级乙等","hType":" 专科医院 "},{"provinceId":7226,"hName":"中国贵航集团三0二医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7208,"hName":"胜利油田胜利医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7232,"hName":"中铁二十局中心医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7186,"hName":"中国人民解放军五四一总医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7200,"hName":"诸暨市人民医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7208,"hName":"淄博矿业集团有限责任公司中心医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7208,"hName":"淄博市第一医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7224,"hName":"自贡市第三人民医院","hGrade":"三级乙等","hType":" 综合医院"}]';;

    }
}