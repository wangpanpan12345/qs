<?php
/**
 * Created by PhpStorm.
 * User: wangpan
 * Date: 16/11/28
 * Time: 下午12:02
 */

namespace App\Http\Controllers;

use App\DailyNews;
use App\KnowledgeCanned;
use App\Tags;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;


class KnowledgeController extends Controller
{
    /**
     * 添加一条知识罐头
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function add(Request $k)
    {
        $tags = $k["tags"];
        $type = $k["type"];
        $content = $k["content"];

        $knowledge = new KnowledgeCanned();
        $knowledge->tags = $tags;
        $knowledge->type = $type;
        $knowledge->content = $content;
        $knowledge->flag = 1;
        $knowledge->priority = 1;
        $knowledge->user_id = Auth::user()->id;
        $o = $knowledge->save();
        if (!$o) {
            $return = ['error' => 1, 'result' => $o];
        } else {
            $return = ['error' => 0, 'result' => $o];
        }
        return $return;
    }

    /**
     * 更新一条知识罐头
     * @param Request $k
     * @return array
     */
    public function update(Request $k)
    {
        $_id = $k["_id"];
        $tags = $k["tags"];
        $type = $k["type"];
        $content = $k["content"];

        $knowledge = KnowledgeCanned::find($_id);
        $knowledge->tags = $tags;
        $knowledge->type = $type;
        $knowledge->content = $content;
        $knowledge->flag = 1;
        $knowledge->priority = 1;
//        $knowledge->user_id = Auth::user()->id;
        $o = $knowledge->save();
        if (!$o) {
            $return = ['error' => 1, 'result' => $o];
        } else {
            $return = ['error' => 0, 'result' => $o];
        }
        return $return;
    }
    /**
     * 移除一条知识罐头
     * @param Request $k
     * @return array
     */
    public function delete(Request $k)
    {
        $_id = $k["_id"];
        $knowledge = KnowledgeCanned::find($_id);
        $o = $knowledge->delete();
        if (!$o) {
            $return = ['error' => 1, 'result' => $o];
        } else {
            $return = ['error' => 0, 'result' => $o];
        }
        return $return;
    }



}