<?php
/**
 * Created by PhpStorm.
 * User: wangpan
 * Date: 16/12/28
 * Time: 上午10:49
 */

namespace App\Http\Controllers;

use App\DailyFunds;

class DailyFundsController
{
    public function list_funds()
    {
        $limit = 30;
        $projections = ['*'];
        $daily_funds = DailyFunds::orderby("pub_date", "desc")->paginate($limit, $projections);
        return view("admin.dailyfunds", ["dailyfunds" => $daily_funds]);
    }
}