<?php
/**
 * Created by PhpStorm.
 * User: wangpan
 * Date: 16/11/22
 * Time: 下午2:52
 */

namespace App\Http\Controllers;

use App\UserCollects;
use App\Tags;
use App\DailyNews;
use App\Http\Requests;
use App\Companies;
use App\User;
use App\Jobs;
use App\Notes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController
{
    public function profile()
    {
        $uid = Auth::user()->id;
        $limit = 30;
        $projections = ['*'];
        $collects = UserCollects::where("user", $uid)->orderby("updated_at", 'desc')->paginate($limit, $projections);
        $myjob = Jobs::where("user", $uid)->paginate($limit, $projections);
        $return_param = [
            "jobs" => $myjob,
            "collects" => $collects,
        ];
        return view("admin.user", $return_param);
    }
}