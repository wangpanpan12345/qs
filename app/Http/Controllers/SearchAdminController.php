<?php

namespace App\Http\Controllers;

use App\DailyFunds;
use App\Tags;
use App\Companies;
use App\DailyNews;
use App\Founders;
use App\Logs;
use App\Items;
use App\QS;
use App\KnowledgeCanned;
use Carbon\Carbon;
use Elasticquent\ElasticquentTrait;
use Illuminate\Http\Request;
use App\Http\Requests;
//use SphinxClient;
use Illuminate\Support\Facades\Auth;
use PhpParser\Node\Expr\Array_;
use Psy\TabCompletion\Matcher\ObjectAttributesMatcher;

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

//        $x = Companies::complexSearch(array(
//           'type' => 'companies',
//            'body' => array(
//                "query" => array(
//                    "bool" => array(
//                        "must" => array(
//                            "match_phrase" => array(
//                                "_all" => array(
//                                    "query" => $s,
//                                    "slop" => 5
//                                )
//                            )
//                        ),
//                        "should" => array(
//                            "multi_match" => array(
//                                "query" => $s,
//                                "type" => "most_fields",
//                                "fields" => ["name^9", "detail^2", "slogan^6", "des^6", "fullName^8", "tags^8",
//                                    "raiseFunds.organizations.name", "founder^4", "products.name^8"],
//                                "operator" => "and",
//                                "tie_breaker" => 0.3,
//                                "minimum_should_match" => "50%"
//                            )
//                        )
//                    )
//                )
//            ),
//            'size' => 100,
//            'from' => 0
//        ));
//
//        dd($x);


        $c = Companies::complexSearch(array(
            'type' => 'companies',
            'body' => array(
                "query" => array(
                    "bool" => array(
                        "must" => array(
                            "match_phrase" => array(
                                "_all" => array(
                                    "query" => $s,
                                    "slop" => 5
                                )
                            )
                        ),
                        "should" => array(
                            "multi_match" => array(
                                "query" => $s,
                                "type" => "most_fields",
                                "fields" => ["name^9", "detail^2", "slogan^6", "des^6", "fullName^8", "tags^8",
                                    "raiseFunds.organizations.name", "founder^4", "products.name^8"],
                                "operator" => "and",
                                "tie_breaker" => 0.3,
                                "minimum_should_match" => "50%"
                            )
                        )
                    )
                ),
                "highlight" => array(
                    "require_field_match" => false,
                    "fields" => array(
                        '*' => (Object)array()
                    )
                ),

            ),

            'size' => 100,
            'from' => 0
        ));

        $p = Founders::complexSearch(array(
            'type' => 'founders',
            'body' => array(
                "query" => array(
                    "bool" => array(
                        "must" => array(
                            "match_phrase" => array(
                                "_all" => array(
                                    "query" => $s,
                                    "slop" => 10
                                )
                            )
                        ),
                        "should" => array(
                            "multi_match" => array(
                                "query" => $s,
                                "type" => "cross_fields",
                                "fields" => ["name^9", "des^4", "tags^8", "paper.name", "patent.des^4", "patent.name",
                                    "workedCases.title", "workedCases.des", "workedCases.name", "edu_background.degree",
                                    "edu_background.major", "edu_background.name", "s_position"],
                                "operator" => "and",
                                "tie_breaker" => 0.3,
                                "minimum_should_match" => "30%"
                            )
                        )

                    )
                )
            ),
            'size' => 100,
            'from' => 0
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
            'size' => 100,
            'from' => 0
        ));
        $k = KnowledgeCanned::complexSearch(array(
            'type' => 'knowledge_canned',
            'body' => array(
                "query" => array(
                    "bool" => array(
                        "must" => array(
                            "match_phrase" => array(
                                "_all" => array(
                                    "query" => $s,
                                    "slop" => 5
                                )
                            )
                        ),
                        "should" => array(
                            "multi_match" => array(
                                "query" => $s,
                                "type" => "most_fields",
                                "fields" => ["tags^8", "content"],
                                "operator" => "and",
                                "tie_breaker" => 0.3,
                                "minimum_should_match" => "30%"
                            )
                        )
                    )
                )
            ),
            'size' => 100,
            'from' => 0
        ));
        $i = Items::complexSearch(array(
            'type' => 'items',
            'body' => array(
                "query" => array(
                    "bool" => array(
                        "must" => array(
                            "match_phrase" => array(
                                "_all" => array(
                                    "query" => $s,
                                    "slop" => 5
                                )
                            )
                        ),
                        "should" => array(
                            "multi_match" => array(
                                "query" => $s,
                                "type" => "most_fields",
                                "fields" => ["tags^10", "des^6", "name^8", "basic_info^4", "extra_info"],
                                "operator" => "and",
                                "tie_breaker" => 0.3,
                                "minimum_should_match" => "80%",
                            )
                        )
                    )
                )
            ),
            'size' => 100,
            'from' => 0
        ));
        $df = DailyFunds::complexSearch(array(
            'type' => 'dailyfunds',
            'body' => array(
                "query" => array(
                    "bool" => array(
                        "must" => array(
                            "match_phrase" => array(
                                "_all" => array(
                                    "query" => $s,
                                    "slop" => 5
                                )
                            )
                        ),
                        "should" => array(
                            "multi_match" => array(
                                "query" => $s,
                                "type" => "most_fields",
                                "fields" => ["company_df^10", "invest^6", "fund_story^2"],
                                "operator" => "and",
                                "tie_breaker" => 0.3,
                                "minimum_should_match" => "30%"
                            )
                        )
                    )
                )
            ),
            'size' => 100,
            'from' => 0
        ));

        $time_e = time();
        $t = $time_e - $time_s;
        $result_company = $c->getHits();
        $result_news = $n->getHits();
        $result_person = $p->getHits();
        $result_knowledge = $k->getHits();
        $result_item = $i->getHits();
        $result_funds = $df->getHits();
//        dd($result_funds);
        $search_log_user = Auth::user()->_id;
        $search_log_keyword = $s;
        $search_log_result_num = $result_company["total"] + $result_news["total"] + $result_person["total"] +
            $result_knowledge["total"] + $result_item["total"];
//        $search_log_user_want = [];
        $search_log = $search_log_keyword . "\t" . $search_log_result_num . "\t" . $search_log_user;
        $logs = new \App\Lib\QLogs();

        $logs->write_log($search_log);

        $result = array(
            "knowledge" => $result_knowledge,
            "company" => $result_company,
            "person" => $result_person,
            "news" => $result_news,
            "item" => $result_item,
            "funds" => $result_funds,
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
            ->get();
        $item = Items::where('created_at', '>', $today_begin)
            ->where('created_at', '<', $today_end)
            ->get(["name"]);
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
        $item_count = count($item);
        $tags = Tags::All();
        $result = array(
            "count" => $count,
            "companies" => $company,
            "knowledge" => $knowledge,
            "company_new" => $company_static,
            "persons" => $person,
            "item" => $item,
            "person_new" => $person_static,
            "news_new" => $news_static,
            "knowledge_new" => $knowledge_count,
            "item_new" => $item_count,
            "deal_c" => $deal_c,
            'tags' => $tags,
            "logs" => $d_logs,
        );
        return view("admin/index")->with($result);
    }
}
