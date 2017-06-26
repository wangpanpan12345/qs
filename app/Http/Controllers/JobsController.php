<?php
/**
 * Created by PhpStorm.
 * User: wangpan
 * Date: 16/11/22
 * Time: 下午2:52
 */

namespace App\Http\Controllers;

use App\Companies;
use App\Jobs;
use App\JobTopic;
use App\Logs;
use App\Tags;
use App\DailyNews;
use App\Http\Requests;
use Illuminate\Routing\Controller;

class JobsController extends Controller
{
    public $user = [
        "zhoulun" => "5847b487e9c046107b05fa81",
        "yingyuyan" => "5858ec53e9c0460445718d62",
        "wangxinkai" => "58b6354fe9c0461d8f46d542",
        "wangren" => "58d8bb07e9c0460f857a0182",
        "chenyahui" => "5847c54de9c046107c26a381",
    ];

    public function addUser()
    {
        $this->user = [
            "zhoulun" => "5847b487e9c046107b05fa81",
            "yingyuyan" => "5858ec53e9c0460445718d62",
            "wangxinkai" => "58b6354fe9c0461d8f46d542",
            "wangren" => "58d8bb07e9c0460f857a0182",
            "chenyahui" => "5847c54de9c046107c26a381",
        ];
    }

    public function getUser($u)
    {
        $uid = array();
        foreach ($u as $k => $v) {
            $uid = $this->user[$v];
        }
        return $uid;
    }

    public function showAll()
    {

    }

    public function show()
    {
        $uid = Auth::user()->id;
        $myjob = Jobs::where("user", $uid)->get();
        return $myjob;
    }

    public function dailyLogs()
    {

    }

//    public function create($table, $selction, $subject, $u_array)
    public function create($table, $subject)
    {
        global $_table, $_subject, $user;
        $_table = $table;
        $_subject = $subject;
//        $user = $this->getUser($u_array);
        $selction = ["医疗信息化", "人工智能"];
        $user = ["5847b487e9c046107b05fa81", "5858ec53e9c0460445718d62"];
        Companies::whereIn("tags", $selction)->chunk(100, function ($companies) {
            global $user, $_table, $_subject;
            $i = count($user);
            $j = 0;
            foreach ($companies as $company) {
                echo $company->name . "</br>";
//                if ($j < $i) {
//                    $jobs = new Jobs();
//                    $jobs->user = $user[$j];
//                    $jobs->jid = $company->id;
//                    $jobs->table = $_table;
//                    $jobs->subject = $_subject;
//                    $jobs->status = 0;
//                    $jobs->save();
//                    $j++;
//                } else {
//                    $j = 0;
//                }
            }
        });
    }

    public function index(){
        $jobs = JobTopic::orderby("updated_at","desc")->get();
        return view("admin.jobs",["jobt"=>$jobs]);
    }
}