<?php
/**
 * Created by PhpStorm.
 * User: wangpan
 * Date: 16/11/22
 * Time: 下午2:52
 */

namespace App\Http\Controllers;

use App\Tags;
use App\DailyNews;
use App\Http\Requests;
use App\Companies;
use Illuminate\Http\Request;

class TagController
{
    public function showAll()
    {
        $tags = Tags::All();
    }

    public function add(Request $request)
    {
//        return $request->source("source");
        $name = $request->input("name");
        $group_id = $request->input("group_id");
        $alias = $request->input("alias");
        $source = $request->input("source");

        $tags = [
            "name" => $name,
            "group_id" => $group_id,
            "alias" => $alias,
            "source" => $source
        ];
        $add_check = Tags::where("source", "=", "geekheal")->where("name", "=", $name)->get();
        if ($add_check->count()) {
            return ["error" => 6, "result" => $add_check];
        }
        $add = Tags::create($tags);
        if ($add) {
            return ["error" => 0, "result" => $add];
        } else {
            return ["error" => 4, "result" => $add];
        }

    }

    public function tag_search(Request $r)
    {
        $k = $r["q"];
        $s_n_r = Tags::where('name', 'like', '%' . $k . '%')->whereIn("source", ["geekheal", "jianxiu"])->get()->take(10);
        $result = array(
            "result" => $s_n_r,
            "k" => $k,
            "error" => 0,
        );
        return $result;
    }

    public function job_tags(Request $r)
    {
        $k = $r["q"];
        $s_n_r = Tags::where('name', 'like', '%' . $k . '%')->get()->take(10);
        $result = array(
            "result" => $s_n_r,
            "k" => $k,
            "error" => 0,
        );
        return $result;
    }

    public function manage()
    {
        $tags_geekheal = Tags::where("source", "geekheal")->orderBy('group_id')->get();
        $result = ["tags" => $tags_geekheal];
        return view("admin.addtag", $result);
    }

    public function manage_p()
    {
        $tags_geekheal = Tags::where("source", "jianxiu")->orderBy('group_id')->get();
        $result = ["tags" => $tags_geekheal];
        return view("admin.addtag_p", $result);
    }


    public function timeline($tag)
    {
        $tt = Tags::where("name", "=", $tag)->whereIn("source", ["geekheal", "jianxiu"])->first();
        $tag_s = Tags::find($tt->id);
        $tag_a = [];
        $knowledge_tag = Tags::where("group_id", "58a64882e9c0464f253233ec")->get();
        foreach ($tag_s->sub as $k => $v) {
            $tag_a[] = $v["name"];
        }
        $tag_a[] = $tag;
        $timeline_tag = DailyNews::whereIn("tags", $tag_a)->orderBy("created_at", "desc")->get();
        $_ids = [];
        foreach ($timeline_tag as $k => $v) {
            if (!empty($v["company"]) && $v["company"] != "") {
                foreach ($v["company"] as $c => $i) {
                    if($i["_id"]!=""){
                        $_ids[] = $i["_id"];
                    }

                }
            }
        }
        $_ids = array_unique($_ids);
        $companys = Companies::whereIn("_id", $_ids)->get();
        $result = [
            "timeline" => $timeline_tag,
            "tag" => $tt,
            "knowledge_tag" => $knowledge_tag,
            "knowledge" => $tt->knowledge()->get(),
            "companys" => $companys
        ];
        return view("timeline_tag", $result);

    }

    public function package($tag, $pack)
    {
        $package = DailyNews::where("tags", $tag)->where("package", $pack)->get();
        dd($package);
        return $pack . $tag;
    }

}