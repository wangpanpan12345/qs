<?php
/**
 * Created by PhpStorm.
 * User: wangpan
 * Date: 16/11/22
 * Time: 下午2:52
 */

namespace App\Http\Controllers;

use App\Companies;
use App\JobTopic;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use MongoDB\BSON\UTCDateTime;

class JobTopicController extends Controller
{

    public function create(Request $r)
    {
        $fromuser = Auth::user()->id;;
        $topic = $r["topic"];
        $insertArray = array();
        $jobt = new JobTopic();
        $insertArray["fromuser"] = $fromuser;
        $insertArray["topic"] = $topic;
        $insertArray["status"] = "0";
        $insertArray["flag"] = 1;
        $insertArray["tags"] = [];
        $insertArray["created_at"] = new UTCDateTime(round(microtime(true) * 1000));
        $insertArray["updated_at"] = new UTCDateTime(round(microtime(true) * 1000));
        $id = $jobt->insertGetId($insertArray);
        $result = [
            "id" => $id . "",
            "topic" => $topic
        ];

        if (!$id) {
            $return = json_encode(['error' => 1, 'result' => $id]);
        } else {
            $return = json_encode(['error' => 0, 'result' => $result]);
        }
        return $return;
    }

    public function headline_create(Request $r)
    {

        $id = $r["id"];
        $headline = $r["headline"];
        $headline_array = [
            "title" => $headline,
            "content" => "",
            "des" => "",
            "id" => time()
        ];
        $jobt = JobTopic::find($id);
        if (isset($jobt->headline) && !empty($jobt->headline)) {
            $current = $jobt->headline;
            array_push($current, $headline_array);
            $jobt->headline = $current;
        } else {
            $jobt->headline = [$headline_array];
        }
        $o = $jobt->save();
        if (!$o) {
            $return = ['error' => 1, 'result' => $o];
        } else {
            $return = ['error' => 0, 'result' => $headline_array];
        }
        return $return;

    }

    public function headline_get(Request $r)
    {

        $tid = $r["tid"];
        $id = $r["id"];

        $jobt = JobTopic::find($tid);

        $headline = $jobt->headline;
        $o = "";
        foreach ($headline as $k => $v) {
            if ($v["id"] == $id)
                $o = $v["content"];
        }
        $return = ['error' => 0, 'result' => $o];
        return $return;
    }

    public function headline_edit(Request $r)
    {

        $tid = $r["tid"];
        $id = $r["id"];
        $content = $r["content"];
        $jobt = JobTopic::find($tid);

        $headline = $jobt->headline;
        foreach ($headline as $k => $v) {
            if ($v["id"] == $id)
                $headline[$k]["content"] = $content;
        }

        $jobt->headline = $headline;
        $o = $jobt->save();
        if (!$o) {
            $return = ['error' => 1, 'result' => $o];
        } else {
            $return = ['error' => 0, 'result' => $headline];
        }
        return $return;
    }

    public function touser(Request $r)
    {
        $id = $r["id"];
        $touser = $r["touser"];
        $jobt = JobTopic::find($id);
        $jobt->touser = $touser;
        $o = $jobt->save();
        if (!$o) {
            $return = ['error' => 1, 'result' => $o];
        } else {
            $return = ['error' => 0, 'result' => $o];
        }
        return $return;
    }

    public function status(Request $r)
    {

        $id = $r["_id"];
        $status = $r["status"];

        $jobt = JobTopic::find($id);

        $jobt->status = $status;
        $o = $jobt->save();
        if (!$o) {
            $return = ['error' => 1, 'result' => $o];
        } else {
            $return = ['error' => 0, 'result' => $o];
        }
        return $return;
    }


    public function index()
    {
        return view("admin.jobs");
    }
}