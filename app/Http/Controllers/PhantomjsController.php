<?php
/**
 * Created by PhpStorm.
 * User: wangpan
 * Date: 16/11/28
 * Time: 下午12:02
 */

namespace App\Http\Controllers;

use App\DailyNews;

use App\Tags;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use JonnyW\PhantomJs\Client;
use Vinelab\Rss\Rss;
use App\Jobs\CrawlMedia;

//use Illuminate\Support\Facades\Auth;

ini_set('max_execution_time', 0);

include 'simple_html_dom.php';


class PhantomjsController extends Controller
{
    /**
     * 待采集的媒体及期刊网址列表
     * @return array
     */
    public function media_array()
    {
        return [

            "fiercebiotech" => "http://www.fiercebiotech.com/",
            "fiercepharma" => "http://www.fiercepharma.com/",
            "fiercemedicaldevices" => "http://www.fiercebiotech.com/medical-devices",
            "statnews" => "https://www.statnews.com/category/health/",
            "genengnews" => "http://www.genengnews.com/gen-news-highlights",
            "genomeweb" => "https://www.genomeweb.com/breaking-news",
            "spectrum" => "http://spectrum.ieee.org/biomedical",
            "technologyreview" => "https://www.technologyreview.com/c/biomedicine/",
            "nytimes" => "http://www.nytimes.com/section/health",
            "theatlantic" => "http://www.theatlantic.com/health/",
            "wsj" => "http://www.wsj.com/news/business/health-industry",
            "vox" => "http://www.vox.com/science-and-health",
            "scientificamerican" => "https://www.scientificamerican.com/health/",
            "the_scientist" => "http://www.the-scientist.com/?articles.list/categoryNo/2884/category/Daily-News/",
            "sciencenews_gen" => "https://www.sciencenews.org/topic/genes-cells",
            //         "bio_itworld" => "http://www.bio-itworld.com/",
            "theverge" => "http://www.theverge.com/health",
            "livescience" => "http://www.livescience.com/news?type=article&section=health|culture",
            "asianscientist_pharma" => "http://www.asianscientist.com/category/pharma/",
            "sciencealert" => "http://www.sciencealert.com/health",
            "npr" => "http://www.npr.org/sections/health-shots/",
            "eurekalert" => "https://www.eurekalert.org/pubnews.php",
            "sciencedaily" => "https://rss.sciencedaily.com/top/health.xml",
            "techcrunch" => "https://techcrunch.com/health/",
            "mobihealthnews" => "http://www.mobihealthnews.com/",
            "fastcompany" => "https://www.fastcompany.com/technology",
            "singularityhub_health" => "https://singularityhub.com/health/",
            "singularityhub_science" => "https://singularityhub.com/science/",
            "singularityhub_technology" => "https://singularityhub.com/technology/",
            "deepmind" => "https://deepmind.com/blog/feed/basic/",

            //            "popsci" => "http://www.popsci.com/health",
            //            "newswise" => "http://www.newswise.com/articles/list?category=latest",
            //            "mdtmag" => "https://www.mdtmag.com/",
            //重要期刊
//            "nature_news" => "http://www.nature.com/news/",
//            "nature_biological" => "http://www.nature.com/nature/research/biological-sciences.html",
//            "nature_nbt_research" => "http://www.nature.com/nbt/research/index.html",
//            "nature_nbt_news" => "http://www.nature.com/nbt/newsandcomment/index.html",
//            "nature_ng_research" => "http://www.nature.com/ng/research/index.html",
//            "nature_ng_news" => "http://www.nature.com/ng/newsandcomment/index.html",
//            "nature_ni_research" => "http://www.nature.com/ni/research/index.html",
//            "nature_ni_news" => "http://www.nature.com/ni/newsandcomment/index.html",
//            "nature_nm_research" => "http://www.nature.com/nm/research/index.html",
//            "nature_nm_news" => "http://www.nature.com/nm/newsandcomment/index.html",
//            "nature_nmicrobiol_news" => "http://www.nature.com/nmicrobiol/news-and-comment",
//            "nature_nmicrobiol_research" => "http://www.nature.com/nmicrobiol/research",
//            "nature_nnano_news" => "http://www.nature.com/nnano/newsandcomment/index.html",
//            "nature_nnano_research" => "http://www.nature.com/nnano/research/index.html",
//            "nature_neuro_research" => "http://www.nature.com/neuro/research/index.html",
//            "nature_neuro_news" => "http://www.nature.com/neuro/newsandcomment/index.html",
//            "cell" => "http://www.cell.com/cell/newarticles",
//            "sciencemag_news" => "http://www.sciencemag.org/news/latest-news",
//            "sciencemag_advances" => "http://advances.sciencemag.org/",
//            "sciencemag_robotics" => "http://robotics.sciencemag.org/",
//            "sciencemag_stm" => "http://stm.sciencemag.org/",
//            "nejm" => "http://www.nejm.org/medical-articles/research",
//            "thelancet_news" => "http://www.thelancet.com/online-first-news-comment",
//
//            "jamanetwork" => "http://jamanetwork.com/journals/jama/currentissue",
//            "elifesciences" => "https://elifesciences.org/",
//            "bmj_news" => "http://www.bmj.com/news/news?category=News",
//            "bmj_research" => "http://www.bmj.com/research/research",
//            "bmj_research_news" => "http://www.bmj.com/research/research%20news",
//            "thelancet_research" => "http://www.thelancet.com/online-first-research",
//
////            "medcitynews"=>"http://medcitynews.com/",
//            //投融资数据
//            "itjuzi_funding" => "https://www.itjuzi.com/investevents?scope=47",
//            "cyzone" => "http://www.cyzone.cn/event/list-764-3497-1-0-0-0-0/",
        ];
    }
    public function funds_array()
    {
        return [
            //投融资数据
            "itjuzi_funding" => "https://www.itjuzi.com/investevents?scope=47",
            "itjuzi_funding_f" => "https://www.itjuzi.com/investevents/foreign?scope=47",
            "cyzone" => "http://www.cyzone.cn/event/list-764-3497-1-0-0-0-0/",
        ];
    }
    public function journal_array()
    {
        return [
            //重要期刊
            "nature_news" => "http://www.nature.com/news/",
            "nature_biological" => "http://www.nature.com/nature/research/biological-sciences.html",
            "nature_nbt_research" => "http://www.nature.com/nbt/research/index.html",
            "nature_nbt_news" => "http://www.nature.com/nbt/newsandcomment/index.html",
            "nature_ng_research" => "http://www.nature.com/ng/research/index.html",
            "nature_ng_news" => "http://www.nature.com/ng/newsandcomment/index.html",
            "nature_ni_research" => "http://www.nature.com/ni/research/index.html",
            "nature_ni_news" => "http://www.nature.com/ni/newsandcomment/index.html",
            "nature_nm_research" => "http://www.nature.com/nm/research/index.html",
            "nature_nm_news" => "http://www.nature.com/nm/newsandcomment/index.html",
            "nature_nmicrobiol_news" => "http://www.nature.com/nmicrobiol/news-and-comment",
            "nature_nmicrobiol_research" => "http://www.nature.com/nmicrobiol/research",
            "nature_nnano_news" => "http://www.nature.com/nnano/newsandcomment/index.html",
            "nature_nnano_research" => "http://www.nature.com/nnano/research/index.html",
            "nature_neuro_research" => "http://www.nature.com/neuro/research/index.html",
            "nature_neuro_news" => "http://www.nature.com/neuro/newsandcomment/index.html",
            "cell" => "http://www.cell.com/cell/newarticles",
            "sciencemag_news" => "http://www.sciencemag.org/news/latest-news",
            "sciencemag_advances" => "http://advances.sciencemag.org/",
            "sciencemag_robotics" => "http://robotics.sciencemag.org/",
            "sciencemag_stm" => "http://stm.sciencemag.org/",
            "nejm" => "http://www.nejm.org/medical-articles/research",
            "thelancet_news" => "http://www.thelancet.com/online-first-news-comment",

            "jamanetwork" => "http://jamanetwork.com/journals/jama/currentissue",
//            "jamanetwork" => "http://jamanetwork.com/journals/jama/currentissue",
            "elifesciences" => "https://elifesciences.org/",
            "bmj_news" => "http://www.bmj.com/news/news?category=News",
            "bmj_research" => "http://www.bmj.com/research/research",
            "bmj_research_news" => "http://www.bmj.com/research/research%20news",
            "thelancet_research" => "http://www.thelancet.com/online-first-research",
        ];
    }

    /**
     * 任务启动器
     */
    public function starter()
    {
        $funds = $this->funds_array();
        $journal = $this->journal_array();
        $medias = $this->media_array();
        foreach ($medias as $key => $value) {
            $this->dispatch(new CrawlMedia(array("url" => $value, "func" => $key)));
        }
        foreach ($funds as $key => $value) {
            $this->dispatch(new CrawlMedia(array("url" => $value, "func" => $key)));
        }
        foreach ($journal as $key => $value) {
            $this->dispatch(new CrawlMedia(array("url" => $value, "func" => $key)));
        }
    }
    public function media_starter(){

    }

    /**
     * 每日新闻的列表页
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function daily_list()
    {

        $limit = 60;
        $projections = ['*'];
        $dailynews = DailyNews::orderBy('created_at', 'desc')->paginate($limit, $projections);
//        dd($dailynews);
        return view('admin.dailylist', array('dn' => $dailynews));
        dd($dailynews);
    }

    /**
     *
     */
    public function source($source){
        $limit = 60;
        $projections = ['*'];
        $dailynews = DailyNews::where("source","=",$source)->orderBy('created_at', 'desc')->paginate($limit, $projections);
//        dd($dailynews);
        return view('admin.dailylist', array('dn' => $dailynews));
    }

    /**
     * 每日新闻按关键字检索(未使用)
     * @param Request $r
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function daily_list_search(Request $k)
    {
        $key = $k->get("key");
        $limit = 60;
        $projections = ['*'];
        if($key==""){
            $dailynews = DailyNews::orderBy('created_at', 'desc')->paginate($limit, $projections);
        }else{
            $dailynews = DailyNews::where("title", 'like', '%' . $key . '%')->orderBy('updated_at', 'desc')->paginate($limit, $projections);
        }
        return view('admin.dailylist', array('dn' => $dailynews));
    }

    /**
     * 关联每日新闻的公司
     * @param Request $r
     * @return array
     */
    public function daily_news_company(Request $r)
    {
        $_id = $r["_id"];
        $daily_news = DailyNews::find($_id);
        $daily_news->company = $r["company"];
        $o = $daily_news->save();
        $return = ['error' => 0, 'result' => $o];
        return $return;
    }
    /**
     * 关联每日新闻的人
     * @param Request $r
     * @return array
     */
    public function daily_news_person(Request $r)
    {
        $_id = $r["_id"];
        $daily_news = DailyNews::find($_id);
        $daily_news->person = $r["person"];
        $o = $daily_news->save();
        $return = ['error' => 0, 'result' => $o];
        return $return;
    }

    /**
     * 为每日新闻查找Tags
     * @param Request $r
     * @return array
     */
    public function daily_news_tags(Request $r)
    {
        $k = $r["q"];
        $s_n_r = Tags::where('name', 'like', '%' . $k . '%')->get()->take(10);
        $result = array(
            "result" => $s_n_r,
            "k" => $k,
            "error" => 0,
        );

        return $result;
    }

    public function daily_news_date(Request $r)
    {
        var_dump(Auth::user()->_id);
        dd(storage_path('app/a.jpg'));
//        dd(Carbon::now()->format("m.d.Y h:i a"));
//        dd(Carbon::yesterday("Asia/shanghai")->toDateTimeString());
//        dd(Carbon::tomorrow());
//        $date_begin = $r["begin"];
//        dd(DailyNews::find("58423d06c3666e1a072951c8")->updated_at);
        $daily_date = DailyNews::where('created_at', ">", Carbon::yesterday("Asia/shanghai"))->get();
        dd($daily_date);


    }

    /**
     * 关联新闻的tags
     * @param Request $r
     * @return array
     */
    public function daily_news_tags_update(Request $r)
    {
        $_id = $r["_id"];
        $daily_news = DailyNews::find($_id);
        $daily_news->tags = $r["tags"];
//        return $r["tags"];
        $o = $daily_news->save();
        $return = ['error' => 0, 'result' => $o];
        return $return;
    }

    /**
     * 为新闻添加简述,成为每日精选新闻
     * @param Request $r
     * @return array
     */
    public function excerpt(Request $r)
    {
        $_id = $r["_id"];
        $daily_news = DailyNews::find($_id);
        $daily_news->excerpt = $r["excerpt"];
        if (isset($r["excerpt"]) && $r["excerpt"] != "" && $r["excerpt"] != null) {
            $daily_news->is_pub = 1;
            $daily_news->user_id = Auth::user()->id;
        }
        $o = $daily_news->save();
        $return = ['error' => 0, 'result' => $o];
        return $return;

    }

    /**
     * 每日精选新闻列表
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function key_news()
    {
        $limit = 30;
        $projections = ['*'];
        $key_news = DailyNews::where("is_pub", "=", 1)->orderby("updated_at", "desc")->paginate($limit, $projections);
        return view("admin.keynews", ["keynews" => $key_news]);
    }

    /**
     * 人工添加一条新闻
     */
    public function add(Request $r)
    {

        $daily_s = DailyNews::where('link', '=', $r["link"])->where("is_pub", "=", 1)->get();
        if ($daily_s->count() > 0) {
            $return = ['error' => 6, 'result' => $daily_s];
            return $return;
        }
        $title = $r["title"];
        $link = $r["link"];
        $source = $r["source"];
        $created_at = $r["created_at"];
        $created_at = Carbon::createFromFormat("Y-m-d", $created_at);
        $tags = $r["tags"];
        $company = $r["company"];
        $person = $r["person"];
        $excerpt = $r["excerpt"];
        $is_pub = 0;

        if ($excerpt != "") {
            $is_pub = 1;
        }
        $add_array = [
            'title' => $title,
            "link" => $link,
            "source" => $source,
            "company" => $company,
            "created_at" => $created_at,
            "pub_date" => $created_at->toDateTimeString(),
            "tags" => $tags,
            "company" => $company,
            "person" => $person,
            "excerpt" => $excerpt,
            "is_pub" => $is_pub,
            "isread" => 0,
            "flag" => 1,
            "user_id" => Auth::user()->id,
            "priority" => 1
        ];
//        $return = ['error' => 0, 'result' => $add_array];
//        return $return;
        $o = DailyNews::create($add_array);
        $return = ['error' => 0, 'result' => $o];
        return $return;
    }

    public function test(){
        $D = DailyNews::where("excerpt","=","罗氏斥资3.35亿美元收购三代测序公司Genia Technologies")->get();
        dd($D[0]->companies);
    }


    /**
     * @param $html
     * @param $dom_array
     */
    public function common_struct($html, $dom_array)
    {
        $list_d = $dom_array["list"];
        $title_d = $dom_array["title"];
        $link_d = $dom_array["link"];
        $time_d = $dom_array["time"];
        $source = $dom_array["source"];
        foreach ($html->find($list_d) as $list) {
            $title = $list->find($title_d, 0)->innertext;
            $link = $list->find($link_d, 0)->innertext;
            $time = $list->find($time_d, 0)->innertext;
            $source = $source;
        }

    }

}