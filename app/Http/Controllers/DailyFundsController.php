<?php
/**
 * Created by PhpStorm.
 * User: wangpan
 * Date: 16/12/28
 * Time: 上午10:49
 */

namespace App\Http\Controllers;

use App\DailyFunds;
use App\Jobs\CheckFunding;
use App\Http\Requests;

class DailyFundsController extends Controller
{
    /**
     * 列表显示每日抓取到的融资信息,对应融资信息页
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function list_funds()
    {
        $limit = 30;
        $projections = ['*'];
        $daily_funds = DailyFunds::orderby("pub_date_f", "desc")->paginate($limit, $projections);
        return view("admin.dailyfunds", ["dailyfunds" => $daily_funds]);
    }

    /**
     * 每日融资信息自动入库的任务
     */
    public function indb()
    {
        $t = DailyFunds::where("is_pub",0)->get();
        foreach($t as $k=>$v){
            $this->dispatch(new CheckFunding($v));
        }
    }
}