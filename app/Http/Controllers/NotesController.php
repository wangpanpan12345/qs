<?php
/**
 * Created by PhpStorm.
 * User: wangpan
 * Date: 16/11/22
 * Time: 下午2:52
 */

namespace App\Http\Controllers;

use App\Logs;
use App\DailyNews;
use App\Http\Requests;
use App\Notes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use MongoDB\BSON\UTCDateTime;

class NotesController
{
    public function show()
    {
        $limit = 50;
        $projections = ["*"];
        $notes = Notes::where("author", Auth::user()->id)->orderby("updated_at", 'desc')->paginate($limit, $projections);
        return json_encode(["error" => 0, "notes" => $notes]);
    }

    /**
     * 更新笔记
     * @param Request $r
     * @return array
     */
    public function update(Request $r)
    {
        $id = $r->get("_id");
        $content = $r["content"];
        $notes = Notes::find($id);
        $notes->content = $content;
        $o = $notes->save();
        if (!$o) {
            $return = ['error' => 1, 'result' => $o];
        } else {
            $return = ['error' => 0, 'result' => $o];
        }
        return $return;
    }

    public function create(Request $r)
    {
        $insertArray = array();
        $content = $r["content"];
        $notes = new Notes();
        $insertArray["content"] = $content;
        $insertArray["tags"] = ["default"];
        $insertArray["author"] = Auth::user()->id;
        $insertArray["__v"] = 0;
        $insertArray["created_at"] = new UTCDateTime(round(microtime(true) * 1000));
        $insertArray["updated_at"] = new UTCDateTime(round(microtime(true) * 1000));
        $id = $notes->insertGetId($insertArray);
        if (!$id) {
            $return = json_encode(['error' => 1, 'result' => $id]);
        } else {
            $return = json_encode(['error' => 0, 'result' => $id . ""]);
        }
        return $return;
    }

    public function search(Request $r){
        $k = $r["k"];
        $notes = Notes::where("content","like","%$k%")->get()->take(10);
        return json_encode(["error" => 0, "notes" => $notes]);
    }


}