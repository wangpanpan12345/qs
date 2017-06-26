<?php
/**
 * Created by PhpStorm.
 * User: wangpan
 * Date: 16/11/28
 * Time: 下午12:02
 */

namespace App\Http\Controllers;

use App\DailyFunds;
use App\DailyNews;

use App\Jobs\CrawlWeMedia;
use App\Media\DealFund;
use App\Tags;
use Carbon\Carbon;
use App\Companies;
use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use JonnyW\PhantomJs\Client;
use Vinelab\Rss\Rss;
use App\Jobs\CrawlMedia;
use App\UserCollects;

//use Illuminate\Support\Facades\Auth;

ini_set('max_execution_time', 0);

include 'simple_html_dom.php';

/**
 * DailyNews 相关的类
 * Class PhantomjsController
 * @package App\Http\Controllers
 */
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
            "forbes" => "http://www.forbes.com/healthcare/",

            "harvard_wyss" => "https://wyss.harvard.edu/news/",
            "fredhutch" => "https://www.fredhutch.org/en/news.html",
            "sanger_news" => "http://www.sanger.ac.uk/news",
            "broadinstitute_news" => "https://www.broadinstitute.org/news",
            "dana_farber" => "http://www.dana-farber.org/Newsroom/News-Releases.aspx",
            "dana_farber_news" => "http://www.dana-farber.org/Newsroom/In-The-News.aspx",
            "medschl" => "https://www.medschl.cam.ac.uk/category/schoolnews/research-news/",
            "mdanderson" => "https://www.mdanderson.org/newsroom.html",
            "mskcc" => "https://www.mskcc.org/blog",
            "hopkinsmedicine" => "http://www.hopkinsmedicine.org/news/media/releases",
            "stanford_med" => "http://med.stanford.edu/news/all-news.html",
            "fda_news" => "https://www.fda.gov/NewsEvents/Newsroom/PressAnnouncements/default.htm",
            "hsci_news" => "https://hsci.harvard.edu/hsci-news",
            "mit_be" => "http://be.mit.edu/news-events/news",
            "mit_wi" => "http://wi.mit.edu/news",
            "mit_news" => "http://news.mit.edu/mit-news",

            //            "popsci" => "http://www.popsci.com/health",
            //            "newswise" => "http://www.newswise.com/articles/list?category=latest",
            //            "mdtmag" => "https://www.mdtmag.com/",
        ];
    }

    public function funds_array()
    {
        return [
            //投融资数据
            "itjuzi_funding" => "https://www.itjuzi.com/investevents?scope=47",
            "itjuzi_funding_f" => "https://www.itjuzi.com/investevents?location=out&scope=47",
            "cyzone" => "http://www.cyzone.cn/event/list-764-3497-1-0-0-0-0/",
//            "vcbeat" => "http://vcbeat.net/Index/Index/ajaxGetEvents"
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
            "nature_nrcardio" => "https://www.nature.com/nrcardio/current_issue/rss",
            "nature_nrclinonc" => "https://www.nature.com/nrclinonc/current_issue/rss",
            "nature_ncomms" => "https://www.nature.com/ncomms/articles",
            "nature_nrc" => "https://www.nature.com/nrc/current_issue/rss",
            "nature_leu" => "http://www.nature.com/leu/current_issue/rss/index.html",
            "aacrjournals_clincancerres" => "http://clincancerres.aacrjournals.org/content/early/recent?utm_source=early&utm_medium=dropdown&utm_campaign=menu",
            "aacrjournals_clincancerres1" => "http://clincancerres.aacrjournals.org/content/23/11?utm_source=current&utm_medium=dropdown&utm_campaign=menu&current-issue=y",
            "aacrjournals_cancerdiscovery" => "http://cancerdiscovery.aacrjournals.org/content/7/6?utm_source=current&utm_medium=dropdown&utm_campaign=menu&current-issue=y",
            "aacrjournals_cancerdiscovery1" => "http://cancerdiscovery.aacrjournals.org/content/early/by/section?utm_source=early&utm_medium=dropdown&utm_campaign=menu",
            "cell" => "http://www.cell.com/cell/newarticles",
            "cell_current" => "http://www.cell.com/cell/current",
            "cell_metabolism" => "http://www.cell.com/cell-metabolism/issue?pii=S1550-4131(16)X0007-1",
            "sciencemag" => "http://science.sciencemag.org/",
            "sciencemag_immunology" => "http://immunology.sciencemag.org",
            "sciencemag_news" => "http://www.sciencemag.org/news/latest-news",
            "sciencemag_advances" => "http://advances.sciencemag.org/",
            "sciencemag_robotics" => "http://robotics.sciencemag.org/",
            "sciencemag_stm" => "http://stm.sciencemag.org/",
            "nejm" => "http://www.nejm.org/medical-articles/research",
            "nejm_toc" => "http://www.nejm.org/toc/nejm/medical-journal",
            "thelancet_news" => "http://www.thelancet.com/online-first-news-comment",

            "jamanetwork" => "http://jamanetwork.com/journals/jama/currentissue",
            "elifesciences" => "https://elifesciences.org/",
            "bmj_news" => "http://www.bmj.com/news/news?category=News",
            "bmj_research" => "http://www.bmj.com/research/research",
            "bmj_research_news" => "http://www.bmj.com/research/research%20news",
            "thelancet_research" => "http://www.thelancet.com/online-first-research",
            //医学
            "ca_cancer" => "http://onlinelibrary.wiley.com/rss/journal/10.3322/(ISSN)1542-4863",
            "immunity" => "http://www.cell.com/immunity/current.rss",
            "annals" => "http://annals.org/rss/site_25/onlineFirst_90.xml",
            "jco" => "http://ascopubs.org/action/showFeed?type=etoc&feed=rss&jc=jco",
            "jci" => "https://www.jci.org/just-published",
            "cell_neurosciences" => "http://www.cell.com/trends/neurosciences/newarticles",
//            "brain_sciences " => "https://www.cambridge.org/core/journals/behavioral-and-brain-sciences/latest-issue",
            "gastrojournal" => "http://www.gastrojournal.org/current.rss",
//            "annals_surgery" => "http://journals.lww.com/annalsofsurgery/pages/default.aspx",
            "jbjsjournal" => "http://journals.lww.com/jbjsjournal/_layouts/15/OAKS.Journals/feed.aspx?FeedType=CurrentIssue",

        ];
    }

    public function policy_array()
    {
        return [
            //卫计委
            "nhfpc_news" => "http://www.nhfpc.gov.cn/yzygj/pqt/new_list.shtml",
            "nhfpc_policy" => "http://www.nhfpc.gov.cn/yzygj/zcwj2/new_zcwj.shtml",
            "nhfpc_trends" => "http://www.nhfpc.gov.cn/yzygj/pgzdt/new_list.shtml",
            "nhfpc_tigs_news" => "http://www.nhfpc.gov.cn/tigs/pqt/new_list.shtml",
            "nhfpc_tigs_policy" => "http://www.nhfpc.gov.cn/tigs/zcwj2/new_zcwj.shtml",
            "nhfpc_tigs_trends" => "http://www.nhfpc.gov.cn/tigs/pgzdt/new_list.shtml",
            "nhfpc_jws_news" => "http://www.nhfpc.gov.cn/jws/pqt/new_list.shtml",
            "nhfpc_jws_policy" => "http://www.nhfpc.gov.cn/jws/zcwj2/new_zcwj.shtml",
            "nhfpc_jws_trends" => "http://www.nhfpc.gov.cn/jws/pgzdt/new_list.shtml",
            "nhfpc_xwfb" => "http://www.nhfpc.gov.cn/zhuz/xwfb/list.shtml",
            "nhfpc_gwyxx" => "http://www.nhfpc.gov.cn/zhuz/gwyxx/list.shtml",
            "nhfpc_zwgk" => "http://www.nhfpc.gov.cn/zwgk/index.shtml",
            //中国网
            "china_zhibo" => "http://www.china.com.cn/zhibo/node_7243160.htm",
            "china_zhibo_guoxin" => "http://www.china.com.cn/zhibo/node_7245314.htm",
            "china_zhibo_chuifeng" => "http://www.china.com.cn/zhibo/node_7243162.htm",
            "china_zhibo_buwei" => "http://www.china.com.cn/zhibo/node_7243172.htm",
            "china_zhibo_renda" => "http://www.china.com.cn/zhibo/node_7243164.htm",
            "china_zhibo_huiyi" => "http://www.china.com.cn/zhibo/node_7243168.htm",
//            深圳卫计委
            "szhfpc_wzx" => "http://www.szhfpc.gov.cn/wzx/",//微资讯
            "szhfpc_tjsj" => "http://www.szhfpc.gov.cn/xxgk/tjsj/zxtjxx/",//统计数据
            "szhfpc_gzdt" => "http://www.szhfpc.gov.cn/gzdt/",//工作动态
            "szhfpc_gzjb" => "http://www.szhfpc.gov.cn/jksz/gzjb/",//工作简报
            "szhfpc_mybh" => "http://www.szhfpc.gov.cn/xxgk/zcfggfxwj/mybh_5/",//医疗监督管理
            "szhfpc_zcjd" => "http://www.szhfpc.gov.cn/xxgk/zcfggfxwj/zcjd/",//政策解读
            "szhfpc_ghjh" => "http://www.szhfpc.gov.cn/xxgk/ghjh/gmjjshfzghjh_3/",//发展规划
            "szhfpc_zxgh" => "http://www.szhfpc.gov.cn/xxgk/ghjh/zxgh/",//专项规划
            "szhfpc_ndgzjh" => "http://www.szhfpc.gov.cn/xxgk/ghjh/ndgzjh/",//年度规划总结
//            上海卫计委
            "wsjsw_n422" => "http://www.wsjsw.gov.cn/wsj/n422/n424/index.html",//新闻发布
            "wsjsw_n429" => "http://www.wsjsw.gov.cn/wsj/n429/n430/index.html",//公开政府信息
            "wsjsw_n432" => "http://www.wsjsw.gov.cn/wsj/n429/n432/n1488/n1489/index.html",//政策解读
//            食药监局
            "sda_CL0004" => "http://www.sda.gov.cn/WS01/CL0004/",
            "sda_CL0051" => "http://www.sda.gov.cn/WS01/CL0051/",
            "sda_CL0011" => "http://www.sda.gov.cn/WS01/CL0011/",
            "sda_CL0006" => "http://www.sda.gov.cn/WS01/CL0006/",
            "sda_CL1748" => "http://www.sda.gov.cn/WS01/CL1748/",
            "sda_CL1913" => "http://www.sda.gov.cn/WS01/CL1913/",
            "sda_CL0412" => "http://www.sda.gov.cn/WS01/CL0412/",
            "sda_CL1026" => "http://www.sda.gov.cn/WS01/CL1026/",
            //中华医学会
            "cma" => "http://www.cma.org.cn/index/index.html",
            "medscape" => "http://www.medscape.com/",
            "ama_assn" => "https://wire.ama-assn.org/",
            //新华网
            "xinhua" => "http://www.news.cn/health/",
            "xinhua_bw" => "http://www.news.cn/health/bwzx.htm",
            "xinhua_cy" => "http://www.news.cn/health/cydt.htm",
            "xinhua_zy" => "http://www.news.cn/health/zyzy.htm",
            "xinhua_ys" => "http://www.news.cn/health/yscy.htm",
            "xinhua_qn" => "http://www.news.cn/health/qnys.htm",
            "xinhua_ft" => "http://www.news.cn/health/jkft.htm",
            //人民网
            "people_bx" => "http://health.people.com.cn/GB/408579/index.html",
            "people_zc" => "http://health.people.com.cn/GB/408564/index.html",
        ];
    }

    /**
     *
     */
    public function expert_policy()
    {
        return [
//            "healthpolicy" => "http://www.healthpolicy.cn/app/search/db/expert/outline.action?page_change=true&page.pageNo=1&page.pageSize=1500&page.licenseCode=&page.trsOrderby=&class_id=&sid=&tid=&dbnames=EXPERT&temp_field=&temp_dbnames=EXPERT&isMultiDb=true&isStat=0&ds=summary&statSubject=&statOrganization=&statTime=&statTutor=&statJournal=&statAuthor=&lastField=&lastValue=&advanced=&sql=&dateBegin=&dateEnd=&logic1=AND&logic2=&logic3=&logic4=&f1=&f2=&f3=&f4=&v1=&v2=&v3=&v4=&flag1=&flag2=&flag3=&flag4=&cn_keywords=&field=default&DEPARTMENT=&value=&ExternalDocsXML=&email=&comments="
//            "gcyuanshi" => "http://www.cae.cn/cae/jsp/qtysmd.jsp?ColumnID=135",
//            "kxyuanshi" => "http://www.casad.cas.cn/chnl/374/index.html",
//            "qrjh_1" => "http://www.1000plan.org/wiki/index.php?category-view-13-1",
            "fellowplus" => "https://api.fellowplus.com/v2/projects/list?trade_name=%E5%8C%BB%E7%96%97%E5%81%A5%E5%BA%B7&page_num=6&web_token=8wOweXLK6YLb5GEDQIhZKzCuqkb7SU8S5SN9GLJtbsA%3D",
        ];
    }

    /**
     * 采集任务启动器
     */
    public function starter()
    {
        $funds = $this->funds_array();
        $journal = $this->journal_array();
        $medias = $this->media_array();
        $policy = $this->policy_array();
//        $expert = $this->expert_policy();
//        foreach ($expert as $key => $value) {
//            $this->dispatch(new CrawlMedia(array("url" => $value, "func" => $key)));
//        }
//        for($i=7;$i<355;$i++){
//            $this->dispatch(new CrawlMedia(array("url" => "https://api.fellowplus.com/v2/projects/list?trade_name=%E5%8C%BB%E7%96%97%E5%81%A5%E5%BA%B7&page_num=".$i."&web_token=8wOweXLK6YLb5GEDQIhZKzCuqkb7SU8S5SN9GLJtbsA%3D", "func" => "fellowplus")));
//        }
        $this->dispatch((new CrawlMedia($medias)));
        $this->dispatch((new CrawlMedia($journal)));
        $this->dispatch((new CrawlMedia($funds)));
        $this->dispatch((new CrawlMedia($policy)));


//        foreach ($medias as $key => $value) {
//            echo $key;
//            $this->parase_log($key);
//            $this->dispatch((new CrawlMedia(array("url" => $value, "func" => $key)))->onQueue('processing'));
//        }
//        foreach ($funds as $key => $value) {
//            echo $key;
//            $this->parase_log($key);
//            $this->dispatch((new CrawlMedia(array("url" => $value, "func" => $key)))->onQueue('funding'));
//        }
//        foreach ($journal as $key => $value) {
//            $this->dispatch(new CrawlMedia(array("url" => $value, "func" => $key)));
//        }
//        foreach ($policy as $key => $value) {
//            $this->dispatch(new CrawlMedia(array("url" => $value, "func" => $key)));
//        }
    }

    /**
     * 每日新闻的列表页(科技)
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function daily_list()
    {

        $limit = 60;
        $projections = ['*'];
        $today_begin = Carbon::today()->subHours(6);
        $today_end = Carbon::today()->addHour(18);
//        $dailynews_pub = DailyNews::where("flag", 1)
//            ->where("is_pub", 1)
//            ->where('updated_at', '>', $today_begin)
//            ->where('updated_at', '<', $today_end)
//            ->orderBy('created_at', 'desc');


        $dailynews = DailyNews::where("flag", 1)->orderBy('created_at', 'desc')->paginate($limit, $projections);

        return view('admin.dailylist', array('dn' => $dailynews));
    }

    /**
     * 每日新闻的列表页(政策)
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function daily_list_p()
    {

        $limit = 60;
        $projections = ['*'];
        $dailynews = DailyNews::where("flag", 2)->orderBy('created_at', 'desc')->paginate($limit, $projections);
//        dd($dailynews);
        return view('admin.dailylistp', array('dn' => $dailynews));
    }

    /**
     * 科技新闻按照来源查看
     */
    public function source($source)
    {
        $limit = 60;
        $projections = ['*'];
        $dailynews = DailyNews::where("flag", 1)->where("source", "=", $source)->orderBy('created_at', 'desc')->paginate($limit, $projections);
//        dd($dailynews);
        return view('admin.dailylist', array('dn' => $dailynews));
    }

    /**
     * 政策新闻按照来源查看
     * @param $source
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function source_p($source)
    {
        $limit = 60;
        $projections = ['*'];
        $dailynews = DailyNews::where("flag", 2)->where("source", "=", $source)->orderBy('pub_date', 'desc')->paginate($limit, $projections);
//        dd($dailynews);
        return view('admin.dailylistp', array('dn' => $dailynews));
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
        if ($key == "") {
            $dailynews = DailyNews::orderBy('created_at', 'desc')->paginate($limit, $projections);
        } else {
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
        if (!$o) {
            $return = ['error' => 1, 'result' => $o];
        } else {
            $return = ['error' => 0, 'result' => $o];
        }
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
        if (!$o) {
            $return = ['error' => 1, 'result' => $o];
        } else {
            event(new \App\Events\PubState(Auth::user()->id, $_id));
            $return = ['error' => 0, 'result' => $o];
        }
        return $return;
    }

    /**
     * 每日新闻页面,一次更新所有关联
     * @param Request $r
     * @return array
     */
    public function up_all(Request $r)
    {
        $_id = $r["_id"];
        $dailynews = DailyNews::find($_id);
        $tags = $r["tags"];
        if (empty($tags)|| is_null($tags))
            $tags = [];
        $person = $r["person"];
        if (empty($person) || is_null($person))
            $person = [];
        $company = $r["company"];
        if (empty($company) || is_null($company))
            $company = [];
        $excerpt = $r["excerpt"];
//        if (!empty($tags)) {
        $dailynews->tags = $tags;
//        }
//        if (!empty($person)) {
        $dailynews->person = $person;
//        }
//        if (!empty($company)) {
        $dailynews->company = $company;
//        }
        if ($excerpt != "" && $excerpt != null) {
            $dailynews->excerpt = $excerpt;
            $dailynews->is_pub = 1;
            $dailynews->user_id = Auth::user()->id;
        }
        $o = $dailynews->save();
        if (!$o) {
            $return = ['error' => 1, 'result' => $o];
        } else {
            event(new \App\Events\PubState(Auth::user()->id, $_id));
            $return = ['error' => 0, 'result' => $o];
        }
        return $return;
    }

    /**
     * 移除该条无效新闻
     * @param Request $r
     * @return array
     */
    public function delete(Request $r)
    {
        $_id = $r["_id"];
        $flag = $r["flag"];
        $daily_news = DailyNews::find($_id);
        $daily_news->flag = $flag;
        $daily_news->user_id = Auth::user()->id;
        $o = $daily_news->save();
        if (!$o) {
            $return = ['error' => 1, 'result' => $o];
        } else {
            $return = ['error' => 0, 'result' => $o];
        }
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
     * 给会员分享的精选
     * @param Request $r
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function key_news_shared(Request $r)
    {
        $_id = $r["_id"];
        $_title = $r["title"];
        $k_news = DailyNews::find($_id);
        $k_news->share_title = $_title;
        $k_news->shared = "1";
        $o = $k_news->save();
        if (!$o) {
            $return = ['error' => 1, 'result' => $o];
        } else {
            $return = ['error' => 0, 'result' => $o];
        }
        return $return;

    }

    /**
     * 给会员分享的精选每日集合
     * @param Request $r
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function key_news_shared_list($date)
    {
        if (strpos($date, "_") == false)
            abort(404);
        $auth = explode("_", $date);
        $date = $auth[1];
        if ($auth[0] != md5($date . "geekqs"))
            abort(404);
        $today_begin = Carbon::parse($date, "Asia/shanghai")->subHours(4);
        $today_end = Carbon::parse($date, "Asia/shanghai")->addHour(20);
//        dd($today_begin,$today_end);
        $key_news = DailyNews::where("is_pub", "=", 1)
            ->where("shared", "1")
            ->where('updated_at', '>', $today_begin)
            ->where('updated_at', '<', $today_end)
            ->get();
        return view("member_news", ["info" => $key_news, "date" => $date]);
    }


    /**
     * 每日精选新闻列表(按作者)
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function key_news_author($author)
    {
        $limit = 30;
        $projections = ['*'];
        $key_news = DailyNews::where("is_pub", "=", 1)->where("user_id", $author)->orderby("created_at", "desc")->paginate($limit, $projections);
        return view("admin.keynews", ["keynews" => $key_news, 'author' => $author]);
    }

    public function get_one(Request $r)
    {
        $_id = $r["_id"];
        $do = DailyNews::find($_id);
        if (empty($do)) {
            $return = ['error' => 1, 'result' => $do];
        } else {
            $return = ['error' => 0, 'result' => $do];
        }
        return $return;
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
        $flag = isset($r["flag"]) ? $r["flag"] : 1;
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
            "flag" => $flag,
            "user_id" => Auth::user()->id,
            "priority" => 1
        ];
        $o = DailyNews::create($add_array);
        if (!$o) {
            $return = ['error' => 1, 'result' => $o];
        } else {
            $return = ['error' => 0, 'result' => $o];
        }
        return $return;
    }

    /**
     * 通知信息,每小时抓取到的新闻的数量
     */
    public function static_notipy()
    {
        $today_begin = Carbon::now()->subHours(1);
        $today_end = Carbon::now();
        $static = DailyNews::where('created_at', '>', $today_begin)
            ->where('created_at', '<', $today_end)
            ->orderBy("created_at", "desc")->count();
        event(new \App\Events\DailyNewsUpdate($static));

    }

    /**
     * 通知信息,编辑的编辑状态通知
     * @param Request $r
     */
    public function edit_notify(Request $r)
    {
        $_id = $r["_id"];
        $_name = $r["name"];
        event(new \App\Events\EditNotify($_name, $_id));

    }

    public function collect(Request $r)
    {
        $check = UserCollects::where("cid", $r["_id"])->where("user", Auth::user()->id)->get();
        if (count($check) > 0) {
            if ($check[0]->flag == 0) {
                $check = $check[0];
                $check->flag = 1;
                $o = $check->save();
                $dn = DailyNews::find($r["_id"]);
                $cuser = (isset($dn->cuser)) ? $dn->cuser : [];
                array_push($cuser, Auth::user()->id);
                $dn->cuser = array_unique($cuser);
                $dn->save();
                if (!$o) {
                    $return = ['error' => 1, 'result' => $dn->cuser];
                } else {
                    $return = ['error' => 0, 'result' => $dn->cuser];
                }
            } else {
                $return = ['error' => 5, 'result' => $check];
            }

        } else {
            $collect = new UserCollects();
            $collect->cid = $r["_id"];
            $collect->type = $r["type"];
            $collect->user = Auth::user()->id;
            $collect->flag = 1;
            $o = $collect->save();
            $dn = DailyNews::find($r["_id"]);
            $cuser = (isset($dn->cuser)) ? $dn->cuser : [];
            array_push($cuser, Auth::user()->id);
            $dn->cuser = array_unique($cuser);
            $dn->save();
            if (!$o) {
                $return = ['error' => 1, 'result' => $dn->cuser];
            } else {
                $return = ['error' => 0, 'result' => $dn->cuser];
            }
        }
        return $return;

    }

    public function cancel_collect(Request $r)
    {
        $check = UserCollects::where("cid", $r["cid"])->where("user", Auth::user()->id)->get();
        if (count($check) == 0) {
            $return = ['error' => 5, 'result' => $check];
        } else {
            $check = $check[0];
            $check->flag = 0;
            $o = $check->save();
            $dn = DailyNews::find($r["cid"]);
            $cuser = (isset($dn->cuser)) ? $dn->cuser : [];
            foreach ($cuser as $key => $value) {
                if ($value === Auth::user()->id)
                    unset($cuser[$key]);
            }
            $dn->cuser = $cuser;
            $dn->save();
            if (!$o) {
                $return = ['error' => 1, 'result' => $dn->cuser];
            } else {
                $return = ['error' => 0, 'result' => $dn->cuser];
            }
        }
        return $return;

    }


    public function test()
    {
        $D = DailyNews::where("company", "!=", "")->get();
        $count = 0;
        foreach ($D as $k => $v) {
            $C = Companies::where("name", $v["company"])->first();
            $id = $C["_id"];
            $name = $C["name"];
            $v->company = [["_id" => $id, "name" => $name]];
            $v->save();
//            dd($v["title"]);
        }
        echo $count;
        dd($D);
    }

    public function today_news()
    {
        $today_begin = Carbon::today()->subHours(6);
        $today_end = Carbon::today()->addHour(18);
        $news = DailyNews::where('created_at', '>', $today_begin)
            ->where('created_at', '<', $today_end)
            ->get(["title"]);
        foreach ($news as $k => $v) {
            $this->parase_log("u'" . $v->title . "',");
        }

    }

    public function parase_log($pam = '')
    {
        $fp = fopen("/usr/local/laravel/storage/app/order_num.txt", "a+"); //文件被清空后再写入
//        $fp = fopen("/usr/local/laravel/storage/app/news_dict.txt", "a+"); //文件被清空后再写入
        if ($fp) {
            $flag = fwrite($fp, $pam . "\r\n");
            if (!$flag) {
                echo "写入文件失败<br>";
            }
        } else {
            echo "打开文件失败";
        }
        fclose($fp);
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

    public function get_vcbeat()
    {
        $ch = curl_init();
        $url = "http://vcbeat.net/Index/Index/ajaxGetEvents";
//        curl_setopt($ch, CURLOPT_URL, "http://vcbeat.net/Index/Index/ajaxGetEvents");
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json; charset=utf-8',
            )
        );
        $output = curl_exec($ch);
//        preg_replace("/{/","{",$output);
        $output = mb_substr($output, 1, -1) . "}";
//        $t = mb_substr($output,1,1);
//        $output = "'".$output."'";
//        preg_replace("/(\s+)/", '', $output);
        echo $output;
//        dd();
        $output_array = json_decode($output, true);
//        echo $errorinfo = json_last_error();  ;
        curl_close($ch);
//        var_dump($output);
        $data = $output_array["data"];
        $this->in_fund_vcbeat($data);

    }

    public function in_fund_vcbeat($data)
    {
        foreach ($data as $k => $v) {
            $is_exist = DailyFunds::where('company_df', 'like', '%' . $v["short_name"] . '%')->count();
//            var_dump($is_exist);
            if ($is_exist) {

            } else {
                $daily = new DailyFunds();
                $daily->company_df = $v["short_name"];
                $daily->link = "";
                $daily->source = "vcbeat";
                $daily->pub_date_f = Carbon::createFromFormat('Y-m-d', $v["event_time"]);
                $daily->amount = $v["amount"] . $v["unit"];
                $daily->round = $v["name"];
                $daily->invest = [];
                $daily->flag = 1;
                $daily->isread = 0;
                $daily->is_pub = 0;
                $daily->priority = isset($dn["priority"]) ? $dn["priority"] : 9;
//                dd($daily);
                $status = $daily->save();
//                dd($status);
            }
        }
    }

    public function show_all_source()
    {
        $all_media = $this->media_array();
        $all_policy = $this->policy_array();
        $all_journal = $this->journal_array();

        return view("admin.source", ["media" => $all_media,
            "policy" => $all_policy,
            "journal" => $all_journal]);
    }
}