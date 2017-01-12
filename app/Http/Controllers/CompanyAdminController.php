<?php

namespace App\Http\Controllers;
ini_set('max_execution_time', 0);
use App\Companies;
use App\Founders;
use App\Invests;
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

    public function company_by_page()
    {
        $limit = 60;
        $projections = ['*'];
        $options = ["allowDiskUse" => true];
//        $projections = ['name', 'avatar','des','complete_score'];
//        $companies = \DB::collection('companies')->paginate($limit, $projections);
//
        $companies = Companies::orderBy('complete_score', 'desc')->options(["allowDiskUse" => true])->paginate($limit, $projections);//
//        dd($companies);
        foreach ($companies as $K => $V) {
//            if (isset($V["complete_score"]) && !empty($V["complete_score"]))
//                continue;
            $score = $this->score_company($V);
            $company = Companies::find($V["_id"]);
            $company->complete_score = $score;
            $company->save();
//            dd($company);
        }
        return view('admin/show', ['cs' => $companies]);
    }

    public function company_detail_id($id)
    {
        $detail = Companies::find($id);
        return view('admin/detail')->with('detail', $detail);
    }

    public function add(Request $request)
    {
        $company = new Companies();
        $company->name = $request->input("name");
        $status = $company->save();
        $score = $this->score_company($company);
        return $status;
    }

    public function update(Request $request)
    {
        $company = Companies::find($request->input("_id"));
        $value = $request->input("key");
        $company->key = $value;
        $status = $company->save();
        $score = $this->score_company($company);
        return $status;
    }

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

    public function update_avatar()
    {
        Companies::chunk(200, function ($companies) {
            foreach ($companies as $company) {

                $pathinfo = pathinfo($company->avatar);
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
                if (isset($pathinfo["dirname"]) && $pathinfo["dirname"] == "http://static.geekheal.net") {

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

        global $now;
        $now = time();
        Companies::where('tags', $tag)->where('raiseFunds', 'elemMatch', ['times' => ['$gt' => '2016-01-01']])->chunk(200, function ($tags) {
            $end = time();
            global $now;
            echo $end - $now;
            dd($tags);
        });

//        global $tag_array;
//        Companies::distinct('tags')->chunk(200, function ($tags) {
//            $ss = Collection::make($tags)->pluck('tags')->all();
//            foreach ($ss as $k => $v) {
//                foreach ($v as $sk => $sv) {
//                    global $tag_array;
//                    $tag_array[] = $sv;
//
//                }
//
//            }
//
//        });
//        $tag = array_unique($tag_array);
////        dd($tag);
//        foreach($tag as $k=>$v){
//            $tags = Tags::create(array('name'=>$v));
//            echo $v."</br>";
////            dd();
//        }
//        dd();
//        dd(array_unique($tag_array));


    }

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

    public function tag_list($tag)
    {

        $limit = 60;
        $projections = ['*'];

        $companies_tag = Companies::where('tags', $tag)->paginate($limit, $projections);
//        Companies::where('tags', $tag)->chunk(200, function ($tags) {
//            global $count;
//            foreach($tags as $k=>$v){
//                $count++;
//            }
//        });
        return view('admin.show', ['cs' => $companies_tag]);


    }

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


}
