<?php

namespace App\Http\Controllers;
ini_set('max_execution_time', 0);
ini_set("memory_limit", "-1");
use App\Companies;
use App\Founders;
use App\Invests;
use App\DailyNews;
use App\Logs;
use App\Tags;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Http\Requests;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;

class CompanyAdminController extends Controller
{
    /*
     * 后台首页的数据,目前已废弃,暂时没有使用
     */
    public function index()
    {
        $count = Companies::count();
        $count_f = Founders::count();
        $count_i = Invests::count();
        $count_2016 = Companies::where('time', 'like', '%2016%')->count();
        $count_2015 = Companies::where('time', 'like', '%2015%')->count();
        $count_2014 = Companies::where('time', 'like', '%2014%')->count();
        $tags = Tags::All();
        $result = array(
            'count' => $count,
            'count_f' => $count_f,
            'count_i' => $count_i,
            'count_2016' => $count_2016,
            'count_2015' => $count_2015,
            'count_2014' => $count_2014,
            'tags' => $tags,
        );
        return view("admin/index")->with($result);
    }

    /**
     * 公司列表页,按照完成度排列
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function company_by_page()
    {
        $limit = 60;
        $projections = ["*"];
        //当排序数据量过大,超过内存分配时,需要加此参数,使用硬盘的空间
        $options = ["allowDiskUse" => true];
//        $projections = ['name', 'avatar','des','complete_score'];
//        $companies = \DB::collection('companies')->paginate($limit, $projections);
//
        $companies = Companies::orderBy('complete_score', 'desc')->options(["allowDiskUse" => true])->paginate($limit, $projections);//
//        dd($companies);
//        foreach ($companies as $K => $V) {
////            if (isset($V["complete_score"]) && !empty($V["complete_score"]))
////                continue;
//            $score = $this->score_company($V);
//            $company = Companies::find($V["_id"]);
//            $company->complete_score = $score;
//            $company->save();
////            dd($company);
//        }
        return view('admin/show', ['cs' => $companies]);
    }

    /**
     * 按照公司的id,查询公司详情信息,对应公司详情页面
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function company_detail_id($id)
    {
        $detail = Companies::find($id);
        if (is_null($detail)) {
            abort(404);
        }
        $time_line = DailyNews::where('company', 'elemMatch', ['name' => ['$eq' => $detail->name]])->orderby("created_at", "desc")->get();
        $merger = Companies::where('acquisitions', 'elemMatch', ['name' => ['$eq' => $detail->name]])->first();
        $result = [
            "detail" => $detail,
            "timeline" => $time_line,
            "merge" => $merger,
        ];
        return view('admin.detail', $result);
    }

    /**
     * 按照公司名,查询公司详情信息(为防止多个重名,返回第一个查到的数据)
     * @param $name
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function company_detail_name($name)
    {
        $detail = Companies::where("name", $name)->first();
        if (is_null($detail)) {
            abort(404);
        }
        $time_line = DailyNews::where('company', 'elemMatch', ['name' => ['$eq' => $name]])->get();
        $merger = Companies::where('acquisitions', 'elemMatch', ['name' => ['$eq' => $detail->name]])->first(["_id", "name"]);

        $result = [
            "detail" => $detail,
            "timeline" => $time_line,
            "merge" => $merger,
        ];

        return view('admin.detail', $result);
    }

    /**
     * 添加只有公司名的公司
     * @param Request $request
     * @return mixed
     */
    public function add(Request $request)
    {
        $company = new Companies();
        $company->name = $request->input("name");
        $status = $company->save();
//        $score = $this->score_company($company);
        return $status;
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function update(Request $request)
    {
        $company = Companies::find($request->input("_id"));
        $value = $request->input("key");
        $company->key = $value;
        $status = $company->save();
//        $score = $this->score_company($company);
        return $status;
    }

    /**
     * 公司的完成度分,暂时废弃
     * @param null $company
     * @return int
     */
    public function score_company($company = null)
    {
        $keys_5 = collect(['avatar', 'dec', 'detail', 'industry', 'location',
            'website', 'tags', 'time', 'phone', 'email', 'status', 'scale', 'slogan',
            'offices', 'contactInfo']);
        $keys_10 = collect(['raiseFunds', 'keytec', 'trends', 'products',
            'founder_id', 'members', 'investments', 'acquisitions']);
        $c_company_k = Collection::make($company)->keys();
        $k_k_5 = $c_company_k->intersect($keys_5);
        $k_k_10 = $c_company_k->intersect($keys_10);
        $c_5 = 0;
        foreach ($k_k_5 as $k5 => $v5) {
            if (!empty($company[$v5]))
                $c_5++;
        }
        $c_10 = 0;
        foreach ($k_k_10 as $k10 => $v10) {
            if (!empty($company[$v10]))
                $c_10++;
        }
        $score_5 = $c_5 * 5;
        $score_10 = $c_10 * 10;
        $score = $score_5 + $score_10;
        return $score;
    }

    /**
     * 导出相关的头像地址,主要用来七牛抓取相关的头像到自己服务器时使用
     */
    public function export_avatar()
    {

        Companies::where("avatar", "like", "%https://crunchbase-production%")->chunk(200, function ($companies) {
            foreach ($companies as $company) {
                $pathinfo = pathinfo($company->avatar);

                if (isset($pathinfo["dirname"]) && $pathinfo["dirname"] == "/assets")
                    continue;
                $log = $company->avatar . "\t" . "qisu/image/avatar/" . $pathinfo["basename"];
                echo $log . "</br>";

                $this->write_to_log($log, 'avatar_crunch_m.txt');
            }
        });
    }

    /**
     * 更新头像,将七牛的地址替换原地址
     */
    public function update_avatar()
    {
        Companies::chunk(200, function ($companies) {
            foreach ($companies as $company) {

                $pathinfo = pathinfo($company->avatar);
//                dd($pathinfo);
//                if ($pathinfo["basename"] == "avatarcb-default-image-ebacd75729c4c3620011e69a21ec8918.png") {
//                    $company->avatar = "https://oi7gusker.qnssl.com/qisu/image/avatar_default.jpg";
//                    $company->save();
//                    echo $company->avatar . "</br>";
//                }
//
//                if (isset($pathinfo["dirname"]) && $pathinfo["dirname"] == "/assets") {
//                    echo $pathinfo["basename"];
//                    $company->avatar = "https://oi7gusker.qnssl.com/qisu/image/avatar_default.jpg";
//                    $company->save();
//                    echo $company->avatar . '</br>';
//                }
                if (isset($pathinfo["dirname"]) && $pathinfo["dirname"] == "http://static-site.geekheal.net/qisu/image") {
                    $company->avatar = 'https://oi7gusker.qnssl.com/qisu/image/' . $pathinfo["basename"];
//                    dd($company->avatar);
                    $company->save();

                }


//                $pathinfo_orgin=str_replace("avatar","",$pathinfo["basename"]);
//                $company->avatar = 'http://static-site.geekheal.net/qisu/image/avatar' . $pathinfo_orgin;
//                $company->save();
//                echo $company->avatar."</br>";

            }
        });
    }

    public function update_crunch_avatar()
    {
        Companies::where("avatar", "like", "%/assets/%")->chunk(200, function ($companies) {
            foreach ($companies as $company) {

                $pathinfo = pathinfo($company->avatar);
//                dd($pathinfo);
                if ($pathinfo["basename"] == "avatarcb-default-image-ebacd75729c4c3620011e69a21ec8918.png") {
                    $company->avatar = "https://oi7gusker.qnssl.com/qisu/image/avatar_default.jpg";
                    $company->save();
                    echo $company->avatar . "</br>";
                }

                if (isset($pathinfo["dirname"]) && $pathinfo["dirname"] == "/assets") {
                    echo $pathinfo["basename"];
                    $company->avatar = "https://oi7gusker.qnssl.com/qisu/image/avatar_default.jpg";
                    $company->save();
                    echo $company->avatar . '</br>';
                }
                if (isset($pathinfo["dirname"]) && $pathinfo["dirname"] == "http://static-site.geekheal.net/qisu/image") {
                    continue;
                }
                if (isset($pathinfo["dirname"]) && $pathinfo["dirname"] == "https://oi7gusker.qnssl.com/qisu/image") {
                    continue;
                }
                if ($company->avatar == "") {
                    $company->avatar = "https://oi7gusker.qnssl.com/qisu/image/avatar_default.jpg";
                    $company->save();
                    echo $company->avatar . "</br>";
                }

                if (isset($pathinfo["dirname"]) && $pathinfo["dirname"] == "https://oi7gusker.qnssl.com/qisu/image/avatar") {
                    continue;
//                    dd($company->avatar);
                }


//                dd("https://oi7gusker.qnssl.com/qisu/image/avatar/".$pathinfo["basename"]);
//                $company->avatar = "https://oi7gusker.qnssl.com/qisu/image/avatar/".$pathinfo["basename"];
//                echo "https://oi7gusker.qnssl.com/qisu/image/avatar/".$pathinfo["basename"]."</br>";
//                $pathinfo_orgin=str_replace("avatar","",$pathinfo["basename"]);
//                dd($company->avatar);
//                $company->avatar = "https://oi7gusker.qnssl.com/qisu/image/avatar/".$pathinfo["basename"];
//                $company->referer = "crunchbase";
//                $company->save();
//                echo $company->avatar."</br>";

            }
        });
//        Companies::where("avatar","like","%https://crunchbase-production%")->chunk(200, function ($companies) {
//            foreach ($companies as $company) {
//
//                $pathinfo = pathinfo($company->avatar);
////                dd($pathinfo);
//                if ($pathinfo["basename"] == "avatarcb-default-image-ebacd75729c4c3620011e69a21ec8918.png") {
//                    $company->avatar = "https://oi7gusker.qnssl.com/qisu/image/avatar_default.jpg";
//                    $company->save();
//                    echo $company->avatar . "</br>";
//                }
//
//                if (isset($pathinfo["dirname"]) && $pathinfo["dirname"] == "/assets"){
//                    echo $pathinfo["basename"];
//                    $company->avatar="https://oi7gusker.qnssl.com/qisu/image/avatar_default.jpg";
//                    $company->save();
//                    echo $company->avatar.'</br>';
//                }
//                if(isset($pathinfo["dirname"]) && $pathinfo["dirname"] == "http://static-site.geekheal.net/qisu/image"){
//                    continue;
//                }
//                if(isset($pathinfo["dirname"]) && $pathinfo["dirname"] == "https://oi7gusker.qnssl.com/qisu/image"){
//                    continue;
//                }
//                if($company->avatar==""){
//                    $company->avatar = "https://oi7gusker.qnssl.com/qisu/image/avatar_default.jpg";
//                    $company->save();
//                    echo $company->avatar . "</br>";
//                }
//
//                if(isset($pathinfo["dirname"]) && $pathinfo["dirname"] == "https://oi7gusker.qnssl.com/qisu/image/avatar"){
//                    continue;
////                    dd($company->avatar);
//                }
//
//
////                dd("https://oi7gusker.qnssl.com/qisu/image/avatar/".$pathinfo["basename"]);
////                $company->avatar = "https://oi7gusker.qnssl.com/qisu/image/avatar/".$pathinfo["basename"];
////                echo "https://oi7gusker.qnssl.com/qisu/image/avatar/".$pathinfo["basename"]."</br>";
////                $pathinfo_orgin=str_replace("avatar","",$pathinfo["basename"]);
////                dd($company->avatar);
//                $company->avatar = "https://oi7gusker.qnssl.com/qisu/image/avatar/".$pathinfo["basename"];
//                $company->referer = "crunchbase";
//                $company->save();
//                echo $company->avatar."</br>";
//
//            }
//        });
    }

    /**
     * 写入文件
     * @param $log
     * @param $file
     */
    public function write_to_log($log, $file)
    {
        Storage::append($file, $log);
//        Storage::append('avatar_crunch_m.txt', $log);
//        Storage::append('avatar.txt', $log);
//        $fp = fopen(public_path()."/avatar.txt", "a+"); //文件追加写入
//        if ($fp) {
//            $flag = fwrite($fp, $log . "\r\n");
//            if (!$flag) {
//                echo "写入文件失败<br>";
//            }
//        } else {
//            echo "打开文件失败";
//        }
//        fclose($fp);
    }

    public function tag_search($tag)
    {

//        global $now;
//        $now = time();
//        Companies::where('tags', $tag)->where('raiseFunds', 'elemMatch', ['times' => ['$gt' => '2016-01-01']])->chunk(200, function ($tags) {
//            $end = time();
//            global $now;
//            echo $end - $now;
//            dd($tags);
//        });

        global $tag_array;
        Companies::distinct('tags')->chunk(200, function ($tags) {
            $ss = Collection::make($tags)->pluck('tags')->all();
            $sss = array_unique(array_flatten($ss));
            global $tag_array;
            $tag_array[] = $sss;

//            foreach ($ss as $k => $v) {
//                foreach ($v as $sk => $sv) {
//                    global $tag_array;
//                    $tag_array[] = $sv;
//
//                }
//
//            }
        });
        $tag = array_unique(array_flatten($tag_array));
//        dd($tag);
//        foreach($tag as $k=>$v){
//            $tags = Tags::create(array('name'=>$v));
//            echo $v."</br>";
////            dd();
//        }
//        dd();
        dd($tag);


    }

    /**
     * 导出所有公司的城市信息
     */
    public function city()
    {
        Companies::chunk(200, function ($companies) {
            foreach ($companies as $company) {
                $city = $company->location;
                $id = $company->_id;
                $log = $id . "|" . "\t" . $city;
                echo $log;
                $this->write_to_log($log, "city_all");
            }
        });
    }

    /**
     * 按照标签,查看相关的公司列表
     * @param $tag
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function tag_list($tag)
    {

        $limit = 60;
        $projections = ['*'];

        $companies_tag = Companies::where('tags', $tag)->orderby("qi_score", "desc")->paginate($limit, $projections);
//        Companies::where('tags', $tag)->chunk(200, function ($tags) {
//            global $count;
//            foreach($tags as $k=>$v){
//                $count++;
//            }
//        });
        return view('admin.show', ['cs' => $companies_tag, 'tag' => $tag]);


    }

    /**
     * 格式化公司融资的时间与货币金额,暂时废弃
     */
    public function format_raisefund()
    {
        Companies::where('referer', 'crunchbase')->chunk(2000, function ($companys) {
            foreach ($companys as $k => $v) {
                if (isset($v->raiseFunds) && !empty($v->raiseFunds)) {
                    $raise_array = $v->raiseFunds;
                    $i = 0;
                    for ($i; $i < count($raise_array); $i++) {
                        if (stripos($raise_array[$i]["times"], ".")) {
                            $raise_array[$i]["times"] = Carbon::createFromFormat("Y.m.d", $raise_array[$i]["times"])->toDateString();
                        } elseif (stripos($raise_array[$i]["times"], ",")) {
                            $raise_array[$i]["times"] = Carbon::createFromFormat("M, Y", $raise_array[$i]["times"])->toDateString();
                        } else {
                        }
//                        dd($this->findNum("¥48.9万"));
                        /**
                         * 融资额格式化,统一为美元*万
                         */
                        if (stripos($raise_array[$i]["amount"], "¥") !== false && stripos($raise_array[$i]["amount"], "万")) {
                            $num = $this->findNum($raise_array[$i]["amount"]);
                            $raise_array[$i]["amount"] = ($num == "" ? $raise_array[$i]["amount"] : $num / 6.7);
                        }
//                        elseif (stripos($raise_array[$i]["amount"], "人民币") !== false && stripos($raise_array[$i]["amount"], "万")) {
//                            $num = $this->findNum($raise_array[$i]["amount"]);
//                            $raise_array[$i]["amount"] = ($num == "" ? $raise_array[$i]["amount"] : $num / 6.7);
//                        } elseif (stripos($raise_array[$i]["amount"], "美元") !== false && stripos($raise_array[$i]["amount"], "万")) {
//                            $num = $this->findNum($raise_array[$i]["amount"]);
//                            $raise_array[$i]["amount"] = ($num == "" ? $raise_array[$i]["amount"] : $num);
//                        } elseif (stripos($raise_array[$i]["amount"], "美元") !== false && stripos($raise_array[$i]["amount"], "亿")) {
//                            $num = $this->findNum($raise_array[$i]["amount"]);
//                            $raise_array[$i]["amount"] = ($num == "" ? $raise_array[$i]["amount"] : $num * 10000);
//                        } elseif (stripos($raise_array[$i]["amount"], "人民币") !== false && stripos($raise_array[$i]["amount"], "亿")) {
//                            $num = $this->findNum($raise_array[$i]["amount"]);
//                            $raise_array[$i]["amount"] = ($num == "" ? $raise_array[$i]["amount"] : $num * 10000 / 6.7);
//                        }
                        elseif (stripos($raise_array[$i]["amount"], "$") !== false && stripos($raise_array[$i]["amount"], "M")) {
                            $num = $this->findNum($raise_array[$i]["amount"]);
                            $raise_array[$i]["amount"] = ($num == "" ? $raise_array[$i]["amount"] : $num * 100);
                        } elseif (stripos($raise_array[$i]["amount"], "$") !== false && stripos($raise_array[$i]["amount"], "B")) {
                            $num = $this->findNum($raise_array[$i]["amount"]);
                            $raise_array[$i]["amount"] = ($num == "" ? $raise_array[$i]["amount"] : $num * 100000);
                        } elseif (stripos($raise_array[$i]["amount"], "$") !== false && stripos($raise_array[$i]["amount"], "k")) {
                            $num = $this->findNum($raise_array[$i]["amount"]);
                            $raise_array[$i]["amount"] = ($num == "" ? $raise_array[$i]["amount"] : $num / 10);
                        } elseif (stripos($raise_array[$i]["amount"], "€") !== false && stripos($raise_array[$i]["amount"], "k")) {
                            $num = $this->findNum($raise_array[$i]["amount"]);
                            $raise_array[$i]["amount"] = ($num == "" ? $raise_array[$i]["amount"] : $num * 1.11 / 10);
                        } elseif (stripos($raise_array[$i]["amount"], "€") !== false && stripos($raise_array[$i]["amount"], "M")) {
                            $num = $this->findNum($raise_array[$i]["amount"]);
                            $raise_array[$i]["amount"] = ($num == "" ? $raise_array[$i]["amount"] : $num * 111);
                        } elseif (stripos($raise_array[$i]["amount"], "€") !== false && stripos($raise_array[$i]["amount"], "B")) {
                            $num = $this->findNum($raise_array[$i]["amount"]);
                            $raise_array[$i]["amount"] = ($num == "" ? $raise_array[$i]["amount"] : $num * 111000);
                        } elseif (stripos($raise_array[$i]["amount"], "£") !== false && stripos($raise_array[$i]["amount"], "k")) {
                            $num = $this->findNum($raise_array[$i]["amount"]);
                            $raise_array[$i]["amount"] = ($num == "" ? $raise_array[$i]["amount"] : $num * 1.23 / 10);
                        } elseif (stripos($raise_array[$i]["amount"], "£") !== false && stripos($raise_array[$i]["amount"], "M")) {
                            $num = $this->findNum($raise_array[$i]["amount"]);
                            $raise_array[$i]["amount"] = ($num == "" ? $raise_array[$i]["amount"] : $num * 123);
                        } elseif (stripos($raise_array[$i]["amount"], "£") !== false && stripos($raise_array[$i]["amount"], "B")) {
                            $num = $this->findNum($raise_array[$i]["amount"]);
                            $raise_array[$i]["amount"] = ($num == "" ? $raise_array[$i]["amount"] : $num * 123000);
                        } elseif (stripos($raise_array[$i]["amount"], "$") !== false && stripos($raise_array[$i]["amount"], "万")) {
                            $num = $this->findNum($raise_array[$i]["amount"]);
                            $raise_array[$i]["amount"] = ($num == "" ? $raise_array[$i]["amount"] : $num);
                        } elseif (stripos($raise_array[$i]["amount"], "$") !== false && stripos($raise_array[$i]["amount"], "亿")) {
                            $num = $this->findNum($raise_array[$i]["amount"]);
                            $raise_array[$i]["amount"] = ($num == "" ? $raise_array[$i]["amount"] : $num * 10000);
                        } elseif (stripos($raise_array[$i]["amount"], "¥") !== false && stripos($raise_array[$i]["amount"], "亿")) {
                            $num = $this->findNum($raise_array[$i]["amount"]);
                            $raise_array[$i]["amount"] = ($num == "" ? $raise_array[$i]["amount"] : $num * 10000 / 6.7);
                        } else {
                        }
                        if (isset($raise_array[$i]["phace"])) {
                            $raise_array[$i]["phase"] = $raise_array[$i]["phace"];
                        }
                    }
                    $v->raiseFunds = $raise_array;
                    var_dump($v);
                    $v->save();

                }

            }
//            dd("ok");
        });
    }

    /**
     * 提取字符串中的数字
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

    /**
     * 已废弃,修补bug用
     */
    public function format_raisefund_re()
    {

        Companies::where('raiseFunds', 'elemMatch', ['amount' => ['$lt' => 1]])->where('referer', 'crunchbase')->chunk(200, function ($tags) {


            foreach ($tags as $k => $v) {
                $raise_array = $v->raiseFunds;
                $i = 0;
//                for($i;$i<count($raise_array);$i++){
//                    if($raise_array[$i]["amount"]<1){
////                        var_dump($raise_array[$i]["amount"]);
//                        $raise_array[$i]["amount"]=$raise_array[$i]["amount"]*10000;
//
////                        echo '<br/><br/>';
//                    }
//                    $v->raiseFunds = $raise_array;
//
//                    $v->save();
//
//
//                }
                var_dump($v);
                echo '<br/><br/>';


            }


        });
    }

    /**
     * 投资日期的格式化,暂时废弃
     */
    public function invest_date_format_crunch()
    {
//
        Companies::where('referer', '=', 'crunchbase')->chunk(200, function ($companys) {

            foreach ($companys as $k => $v) {
                $investments = [];
                if (isset($v->investments) && !empty($v->investments)) {
                    $investments = $v->investments;
                    $i = 0;
                    for ($i; $i < count($investments); $i++) {
                        if (stripos($investments[$i]["date"], ".")) {
                            $investments[$i]["date"] = Carbon::createFromFormat("Y.m.d", $investments[$i]["date"])->toDateString();
                        } elseif (stripos($investments[$i]["date"], ",")) {
                            $investments[$i]["date"] = Carbon::createFromFormat("M, Y", $investments[$i]["date"])->toDateString();
                        } else {
                        }
                    }
//                    dd("OK");
                }
                $v->investments = $investments;
                var_dump($v->investments);
////                $c++;
////                dd($v);
                $v->save();
            }
        });
    }

    /**
     * 并购日期的格式化,暂时废弃
     */
    public function acquisitions_date_format_crunch()
    {
//
        Companies::where('referer', '=', 'crunchbase')->chunk(200, function ($companys) {

            foreach ($companys as $k => $v) {
                $acquisitions = [];
                if (isset($v->acquisitions) && !empty($v->acquisitions)) {
                    $acquisitions = $v->acquisitions;
                    $i = 0;
                    for ($i; $i < count($acquisitions); $i++) {
                        $acquisitions[$i]["date"] = Carbon::createFromTimestamp(strtotime($acquisitions[$i]["date"]), "Asia/shanghai")->format('Y-m-d');
//                        if (stripos($acquisitions[$i]["date"], ".")) {
//                            $acquisitions[$i]["date"] = Carbon::createFromFormat("Y.m.d", $acquisitions[$i]["date"])->toDateString();
//                        } elseif (stripos($acquisitions[$i]["date"], ",")) {
//                            $acquisitions[$i]["date"] = Carbon::createFromFormat("M, Y", $acquisitions[$i]["date"])->toDateString();
//                        } else {
//                        }
                    }
//                    dd("OK");
                }
                $v->acquisitions = $acquisitions;
                var_dump($v->acquisitions);
////                $c++;
////                dd($v);
                $v->save();
            }
        });
    }

    public function index_all()
    {
//        $books = Companies::search("嘉和生物");

        $c = Companies::complexSearch(array(
            'type' => 'companies',
            'body' => array(
                "query" => array(
                    "multi_match" => array(
                        "query" => "人工智能",
                        "type" => "most_fields",
                        "fields" => ["name^9", "detail^2", "slogan^6", "des^6", "fullName^8", "tags^8"],
                        "operator" => "and",
                        "tie_breaker" => 0.3,
                        "minimum_should_match" => "30%"
                    )
                )
            ),
            'size' => 100
        ));
//        $news = DailyNews::complexSearch(array(
//            'type' => 'dailynews',
//            'body' => array(
//                "query" => array(
//                    "bool" => array(
//                        "should" => array(
//                            array(
//                                "match" => array(
//                                    "title" => array(
//                                        "query" => "sanofi",
//                                        "operator" => "and",
//                                        "boost" => 2
//                                    ))
//                            ),
//
//                            array(
//                                "match" => array(
//                                    "title" => array(
//                                        "query" => "赛诺菲",
//                                        "operator" => "and",
//                                        "boost" => 2
//                                    ))
//                            ),
//                            array(
//                                "match" => array(
//                                    "excerpt" => array(
//                                        "query" => "赛诺菲",
//                                        "operator" => "and",
//                                        "boost" => 2
//                                    ))
//                            ),
//                            array(
//                                "match" => array(
//                                    "excerpt" => array(
//                                        "query" => "sanofi",
//                                        "operator" => "and",
//                                        "boost" => 2
//                                    ))
//                            ),
//                        )
//                    )
//                )
//            ),
//            'size' => 100
//        ));
//        foreach ($news->getHits()["hits"] as $k => $v) {
//            $dn = DailyNews::find($v["_id"]);
//            $count = 0;
//            foreach ($dn->company as $vk => $vv) {
//                if ($vv["_id"] == "57e9eb33ddd1d6d803a3aeb7") {
//                    $count = 1;
//                }
//            }
//            if ($count == 0) {
//                $array_add = [[
//                    "_id" => "57e9eb33ddd1d6d803a3aeb7",
//                    "name" => "Sanofi"
//                ]];
//                $dnc = $dn->company;
//                $dn->company = array_merge($dnc, $array_add);
//                $dn->save();
//                dd($dn);
//            }
//
////            continue;
////            dd($v);
//        }
//        dd($c->getHits()["hits"][87]);
        dd($c);
//        DailyNews::addAllToIndex();
    }

    public function format_company()
    {
        DailyNews::where("company", "=", "")->chunk(200, function ($companys) {
            foreach ($companys as $k => $v) {
                if ($v->company == "" || $v->company[0] == "") {
                    echo $v->title;
                    echo "<br/>";
//                dd($v);
//                dd();
//                    $v->company = [];
//                    $v->save();
//                    dd("ok");
                }


            }

        });
    }

    public function format_person()
    {
        DailyNews::where("person", "=", "")->chunk(200, function ($companys) {
            foreach ($companys as $k => $v) {
                if ($v->person == "" || $v->person[0] == "") {
                    echo $v->title;
                    echo "<br/>";
//                dd($v);
//                dd();
//                    $v->person = [];
//                    $v->save();
//                    dd("ok");
                }


            }

        });
    }

}
