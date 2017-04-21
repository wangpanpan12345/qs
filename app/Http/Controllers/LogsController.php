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

class LogsController
{
    public function showAll()
    {
        $logs = Logs::All();
    }

    /**
     * 后台操作日志的显示(只列举当天的,昨天18点到今天18点)
     */
    public function dailyLogs()
    {
        $today_begin = Carbon::today()->subHours(6);
        $today_end = Carbon::today()->addHour(18);
        $d_logs = Logs::where('created_at', '>', $today_begin)
            ->where('created_at', '<', $today_end)
            ->orderBy("created_at", "desc")->get();
    }

}