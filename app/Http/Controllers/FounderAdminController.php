<?php

namespace App\Http\Controllers;

use App\Founders;
use App\DailyNews;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Http\Requests;

class FounderAdminController extends Controller
{

    public function index()
    {

    }

    /**
     * 数据库中人的维度的详情页,按照id查询个人信息和该人相关的每日新闻绑定信息
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function founder_detail_id($id)
    {
        $detail = Founders::find($id);
        $time_line = DailyNews::where('person', 'elemMatch', ['_id' => ['$eq' => $id]])->get();
        $result = [
            "detail" => $detail,
            "timeline" => $time_line
        ];
        return view('admin.founder', $result);
    }
}
