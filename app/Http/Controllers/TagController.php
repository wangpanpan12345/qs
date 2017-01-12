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
        $s_n_r = Tags::where('name', 'like', '%' . $k . '%')->where("source", "=", "geekheal")->get()->take(10);
        $result = array(
            "result" => $s_n_r,
            "k" => $k,
            "error" => 0,
        );
        return $result;
    }

    public function manage()
    {
        $tags_geekheal = Tags::where("source", "=", "geekheal")->orderBy('group_id')->get();
        $result = ["tags" => $tags_geekheal];
        return view("admin.addtag", $result);
    }

    public function timeline($tag)
    {
        $tt = Tags::where("name", "=", $tag)->where("source", "=", "geekheal")->first(["id"]);
        $tag_s = Tags::find($tt->id);
        $tag_a = [];
        foreach ($tag_s->sub as $k => $v) {
//            dd($v);
            $tag_a[] = $v["name"];
        }
        $tag_a[] = $tag;
//        dd($tag_a);
        $timeline_tag = DailyNews::whereIn("tags", $tag_a)->orderBy("created_at", "desc")->get();
//        dd($timeline_tag);
        return view("timeline_tag", ["timeline" => $timeline_tag, "tag" => $tag]);

    }

}