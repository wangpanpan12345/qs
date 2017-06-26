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
use App\Logs;
use App\KnowledgeCanned;
use App\JobTopic;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Monolog\Formatter\LogstashFormatter;

class UserController
{
    /**
     * 用户个人页的收藏/任务/得分数据
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function profile(Request $r)
    {
        $uid = Auth::user()->id;
        $limit = 30;
        $projections = ['*'];
        $status = 2;
        if ($r["type"] == "topic" && $r["status"] != '2') {
            $status = $r["status"];
//            var_dump($status);
            $jobt = JobTopic::orWhere(function ($query) {
                $uid = Auth::user()->id;
                $query->where("fromuser", $uid)
                    ->orWhere("touser", $uid);

            })->where("status", $status)->get();

//            dd($jobt);
        } else {
            $jobt = JobTopic::where("touser", $uid)->orWhere("fromuser", $uid)->get();
        }

        $collects = UserCollects::where("user", $uid)->where("flag", 1)->orderby("updated_at", 'desc')->paginate($limit, $projections);
        $myjob = Jobs::where("user", $uid)->paginate($limit, $projections);
        $user = User::where("roleid",'9')->get();
        $score = $this->score();
        $return_param = [
            "jobs" => $myjob,
            "collects" => $collects,
            "jobt" => $jobt,
            "score" => $score,
            "status" => $status,
            "user" => $user
        ];
//        dd($user);
        return view("admin.user", $return_param);
    }

    public function important_array()
    {
        return $important = ["nature_news", "nature_biological", "nature_nbt_research",
            "nature_nbt_news", "nature_ng_research", "nature_ng_news", "nature_ni_research",
            "nature_ni_news", "nature_nm_research", "nature_nm_news", "nature_nmicrobiol_news",
            "nature_nmicrobiol_research", "nature_nnano_news", "nature_nnano_research",
            "nature_neuro_research", "nature_neuro_news", "nature_nrcardio", "nature_nrclinonc", "nature_ncomms",
            "nature_nrc", "nature_leu", "aacrjournals_clincancerres", "aacrjournals_clincancerres1", "aacrjournals_cancerdiscovery",
            "aacrjournals_cancerdiscovery1", "cell", "cell_current", "cell_metabolism", "sciencemag_news",
            "sciencemag_advances", "sciencemag_robotics", "nejm", "sciencemag_stm", "nejm_toc", "thelancet_news",
            "jamanetwork", "elifesciences", "bmj_news", "bmj_research", "bmj_research_news", "thelancet_research",
            "ca_cancer", "immunity", "annals", "jco", "jci", "cell_neurosciences", "gastrojournal", "jbjsjournal"
        ];
    }

    /**
     * 计算内容每月的贡献得分
     * @return int
     */
    public function score()
    {
        $l = new Carbon('first day of today', 'Asia/shanghai');
        $e = Carbon::now();
        $uid = Auth::user()->id;
        global $log_score;
        $log_score = 0;
        Logs::where("user_id", $uid)->where('created_at', '>', $l)
            ->where('created_at', '<', $e)->chunk(200, function ($logs) {
                global $log_score;
                foreach ($logs as $log) {
                    $log_score = $log_score + count($log, COUNT_RECURSIVE) * 0.1;
                }
            });
        $knowledge = KnowledgeCanned::where("user_id", $uid)->where('created_at', '>', $l)
            ->where('created_at', '<', $e)->count();
        $k_score = $knowledge * 5;
//        $news = DailyNews::where("user_id", $uid)->where('created_at', '>', $l)
//            ->where('created_at', '<', $e)->count();
        $news = DailyNews::where("user_id", $uid)->where('created_at', '>', $l)
            ->where('created_at', '<', $e)->get();
        $n_score = 0;
        $important = $this->important_array();
        foreach ($news as $K => $V) {
            if (in_array($V->source, $important)) {
                $n_score = $n_score + 3;
            } else {
                $n_score = $n_score + 2;
            }
        }
//        $n_score = $news * 2;
        return $log_score + $k_score + $n_score;

    }

    public function score_list()
    {
        $l = new Carbon('first day of today', 'Asia/shanghai');
//        $l = new Carbon('first day of May', 'Asia/shanghai');
//        dd($l);
        $e = Carbon::now();
//        $e = new Carbon('last day of May', 'Asia/shanghai');
//        $e = $e->addDays(1);
//        dd($e->addDays(1));
//        $uid = Auth::user()->id;
        $u_a = [
            "周伦" => "5847b487e9c046107b05fa81",
            "应语妍" => "5858ec53e9c0460445718d62",
            "王新凯" => "58b6354fe9c0461d8f46d542",
            "王鑫英" => "58e6f820e9c046733142c1d1",
            "朱爽爽" => "58e6f811e9c046049346ef82",
            "李芙蓉" => "59195f7ee9c0460b7f1c202e"
        ];

        foreach ($u_a as $k => $uid) {
            global $log_score;
            $log_score = 0;
            $logs = Logs::where("user_id", $uid)->where('created_at', '>', $l)
                ->where('created_at', '<', $e)->chunk(200, function ($logs) {
                    global $log_score;
                    foreach ($logs as $log) {
                        $log_score = $log_score + count($log, COUNT_RECURSIVE) * 0.1;
                    }
                });
            $knowledge = KnowledgeCanned::where("user_id", $uid)->where('created_at', '>', $l)
                ->where('created_at', '<', $e)->count();
            $k_score = $knowledge * 5;
//            $news = DailyNews::where("user_id", $uid)->where('created_at', '>', $l)
//                ->where('created_at', '<', $e)->count();
            $news = DailyNews::where("user_id", $uid)->where('created_at', '>', $l)
                ->where('created_at', '<', $e)->get();
            $n_score = 0;
            $important = $this->important_array();
            foreach ($news as $K => $V) {
                if (in_array($V->source, $important)) {
                    $n_score = $n_score + 3;
                } else {
                    $n_score = $n_score + 2;
                }
            }
//            $n_score = $news * 2;
            echo $k . "=>" . ($log_score + $k_score + $n_score) . "<br>";
        }
    }
}