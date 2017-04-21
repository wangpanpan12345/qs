<?php

namespace App\Http\Controllers;

use App\Tags;
use App\Companies;
use App\DailyNews;
use App\Founders;
use App\Logs;
use App\KnowledgeCanned;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Requests;
use SphinxClient;

class SearchAdminController extends Controller
{

    public function index()
    {

    }

    public function name_k(Request $r)
    {
//        dd($r);
        if ($r["page"] > 1) {
//            dd("ok");
        }
        $s = $r->input("k");
//        $sphinxClient = new \SphinxClient();
//
//        $sphinxClient->setServer('localhost', 9312);   // server = localhost,port = 9312.
//        $sphinxClient->setMatchMode(SPH_MATCH_EXTENDED2);
//        $sphinxClient->SetLimits(0, 1000, 1000, 0);
//        $sphinxClient->setMaxQueryTime(5000);  // set search time 5  seconds.
//        $result = $sphinxClient->query($s, "xml");
//        $news = $sphinxClient->query($s, 'news');
//
//        $ids = array();
//        $ids_news = array();
//        $result_company = [];
//        $result_news = [];
//        if (isset($result['matches'])) {
//            foreach ($result['matches'] as $k => $v) {
//                $ids[] = $v["attrs"]["_id"];
//            }
//            $limit = 200;
//            $projections = ["*"];
//            $result_company = Companies::whereIn("_id", $ids)->paginate($limit, $projections);
//        } else {
//            abort(404, "未查询到");
//        }
//        if (isset($news['matches'])) {
//            foreach ($news['matches'] as $k => $v) {
//                $ids_news[] = $v["attrs"]["_id"];
//            }
//            $limit = 200;
//            $projections = ["*"];
//            $result_news = DailyNews::whereIn("_id", $ids_news)->paginate($limit, $projections);
//        } else {
////            abort(404, "未查询到");
//        }
        $time_s = time();

        $c = Companies::complexSearch(array(
            'type' => 'companies',
            'body' => array(
                "query" => array(
                    "multi_match" => array(
                        "query" => $s,
                        "type" => "most_fields",
                        "fields" => ["name^9", "detail^2", "slogan^6", "des^6", "fullName^8", "tags^8","raiseFunds.organizations.name"],
                        "operator" => "and",
                        "tie_breaker" => 0.3,
                        "minimum_should_match" => "30%"
                    )
                )
            ),
            'size' => 100
        ));
        $p = Founders::complexSearch(array(
            'type' => 'founders',
            'body' => array(
                "query" => array(
                    "multi_match" => array(
                        "query" => $s,
                        "type" => "cross_fields",
                        "fields" => ["name^9", "des^4", "tags^8", "paper.name^2", "patent.des^4", "patent.name", "workedCases.title", "workedCases.des",
                            "workedCases.name", "edu_background.degree", "edu_background.major", "edu_background.name","s_position"],
                        "operator" => "and",
                        "tie_breaker" => 0.3,
                        "minimum_should_match" => "30%"
                    )
                )
            ),
            'size' => 100
        ));
        $n = DailyNews::complexSearch(array(
            'type' => 'dailynews',
            'body' => array(
                "query" => array(
                    "multi_match" => array(
                        "query" => $s,
                        "type" => "most_fields",
                        "fields" => ["excerpt^9", "title^4", "tags^8", "company.name", "person.name"],
                        "operator" => "and",
                        "tie_breaker" => 0.3,
                        "minimum_should_match" => "30%"
                    )
                )
            ),
            'size' => 100
        ));
        $time_e = time();
        $t = $time_e - $time_s;
        $result_company = $c->getHits();
        $result_news = $n->getHits();
        $result_person = $p->getHits();
//        dd($result_person,$t);


//        dd($result_company);
        $result = array(
            "company" => $result_company,
            "person" => $result_person,
            "news" => $result_news,
            "k" => $s,
        );
//        dd($result);

        return view('admin.search')->with($result);
    }

    public function name_k_dy(Request $r)
    {
        $k = $r["q"];
        $s_n_r = Companies::where('name', 'like', '%' . $k . '%')->get()->take(10);
        $result = array(
            "result" => $s_n_r,
            "k" => $k,
            "error" => 0,
        );

        return $result;
    }

    public function name_k_p_dy(Request $r)
    {
        $k = $r["q"];
//        $k = $r["name"];
        $s_n_r = Founders::where('name', 'like', '%' . $k . '%')->get()->take(10);
        $result = array(
            "result" => $s_n_r,
            "k" => $k,
            "error" => 0,
        );

        return $result;
    }

    public function insert_search(Request $r)
    {
        $k = $r->input("name");
        $s_n_r = Companies::where('name', 'like', '%' . $k . '%')->take(10)->get();
        $result = array(
            "result" => $s_n_r,
            "k" => $k,
        );
        return $result;
    }

    public function today_new_static()
    {
        $count = Companies::count();
        $today_begin = Carbon::today()->subHours(6);
        $today_end = Carbon::today()->addHour(18);
        $company = Companies::where('updated_at', '>', $today_begin)
            ->where('updated_at', '<', $today_end)
            ->orderBy("updated_at", "desc")
            ->options(["allowDiskUse" => true])
            ->get(["name", "avatar"]);
        $person = Founders::where('updated_at', '>', $today_begin)
            ->where('updated_at', '<', $today_end)
            ->orderBy("updated_at", "desc")
            ->options(["allowDiskUse" => true])
            ->get(["_id", "name", "avatar"]);
        $news = DailyNews::where('created_at', '>', $today_begin)
            ->where('created_at', '<', $today_end)
            ->get(["tags", "is_pub", "company", "flag"]);
        $knowledge = KnowledgeCanned::where('created_at', '>', $today_begin)
            ->where('created_at', '<', $today_end)
            ->get(["tags", "type", "_id", "flag"]);
        $d_logs = Logs::where('created_at', '>', $today_begin)
            ->where('created_at', '<', $today_end)
            ->orderBy("created_at", "desc")->get();
        $deal_c = 0;
        foreach ($news as $k => $v) {
            if ((isset($v["tags"]) && !empty($v["tags"])) || $v["is_pub"] == 1 || !empty($v["company"]) || $v["flag"] < 0) {
                $deal_c++;
            }
        }
//        dd($person_static);
        $news_static = count($news);
        $person_static = count($person);
        $company_static = count($company);
        $knowledge_count = count($knowledge);
        $tags = Tags::All();
        $result = array(
            "count" => $count,
            "companies" => $company,
            "knowledge" => $knowledge,
            "company_new" => $company_static,
            "persons" => $person,
            "person_new" => $person_static,
            "news_new" => $news_static,
            "knowledge_new" => $knowledge_count,
            "deal_c" => $deal_c,
            'tags' => $tags,
            "logs" => $d_logs,
        );
        return view("admin/index")->with($result);
    }
}
