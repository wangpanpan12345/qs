<?php

namespace App\Media;

use App\DailyNews;
use App\DailyFunds;
use JonnyW\PhantomJs\Client;
use JonnyW\PhantomJs\DependencyInjection\ServiceContainer;
use Carbon\Carbon;
use Vinelab\Rss\Rss;
use App\Companies;


class MediaBuilder
{
    public $rss = [
        "sciencedaily",
        "ca_cancer",
        "immunity",
        "deepmind",
        "annals",
        "jco",
        "gastrojournal",
        "jbjsjournal",
        "fellowplus",
    ];

    public function builder($url = "", $func = "")
    {
        $client = Client::getInstance();
        $client->getEngine()->addOption('--load-images=false');
        $client->getEngine()->addOption('--ignore-ssl-errors=true');

//        $client->getProcedureCompiler()->disableCache();
//        $client->getEngine()->addOption('--disk-cache=false');
//        $client->getEngine()->addOption('--proxy=80.90.161.217:80');
//        $client->getEngine()->addOption('--proxy-auth=username:password');
        $client->isLazy();
        if (in_array($func, $this->rss)) {
            $client->setProcedure('http_xml');
        }

        $client->getEngine()->setPath('/usr/local/bin/phantomjs');
        $timeout = 30000;
        $delay = 90;

        /**
         * @see JonnyW\PhantomJs\Http\CaptureRequest
         **/
//        $width  = 800;
//        $height = 600;
//        $top    = 0;
//        $left   = 0;
//        $request = $client->getMessageFactory()->createCaptureRequest($url, 'GET');
//        $request->setOutputFile(storage_path('app/'.$func.".jpg"));
//        $request->setViewportSize($width, $height);
//        $request->setCaptureDimensions($width, $height, $top, $left);


        /**
         * @see JonnyW\PhantomJs\Http\PdfRequest
         **/
//        $request = $client->getMessageFactory()->createPdfRequest($url, 'GET');
//        $request->setOutputFile(storage_path('app/'.$func.".pdf"));
//        $request->setFormat('A4');
//        $request->setOrientation('landscape');
//        $request->setMargin('1cm');

        /**
         * @see JonnyW\PhantomJs\Http\Request
         **/
        $request = $client->getMessageFactory()->createRequest($url, 'GET');
        $request->addHeader("User-Agent", "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_10_5) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/54.0.2840.98 Safari/537.36");
        $request->addHeader("Accept", "text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8");
        $request->addHeader("cookie", "welcomeAd=true; dailyWelcomeCookie=true;");


        $request->setTimeout($timeout);
        $request->setDelay($delay);
//        dd($client->getProcedure());
//
//        /**
//         * @see JonnyW\PhantomJs\Http\Response
//         **/
        $response = $client->getMessageFactory()->createResponse();
//
//        // Send the request

        $client->send($request, $response);
//        echo $response->getContent();
        $html = str_get_html($response->getContent());
        if ($response->getStatus() == 200) {
            call_user_func(array($this, $func), $html);
        } elseif ($response->getStatus() == 408 || $response->getStatus() == 301 || $response->getStatus() == 302 || $response->getStatus() == 307) {
//            $this->parase_log($response->getStatus());
            call_user_func(array($this, $func), $html);
        } else {
//            call_user_func(array($this, $func), $html);
            $this->parase_log($response->getStatus());
        }
    }

    /**
     * http://www.nytimes.com/section/health
     *
     */
    public function nytimes($html)
    {
//        foreach ($html->find(".rank-template ol.story-menu li") as $list) {
//            $link = $list->find(".headline a", 0)->href;
//            $title = $list->find(".headline a", 0)->innertext;
//            $time = $list->find("time", 0)->datetime;
//            $time = Carbon::createFromTimestamp($time, "Asia/shanghai")->format('Y-m-d H:i');
//            $source = "nytimes";
//            $this->parase_log($link);
//        }
        $indb = array();
        try {
            foreach ($html->find("div.stream ol.initial-set li") as $list) {
//                echo $list;
                $link = $list->find(".story-link", 0)->href;
                $title = $list->find("h2.headline", 0)->innertext;
                $time = $list->find("time", 0)->innertext;
                $time = Carbon::createFromTimestamp(strtotime($time), "Asia/shanghai")->format('Y-m-d H:i');
                $source = "nytimes";
                $indb["title"] = trim($title);
                $indb["link"] = $link;
                $indb["time"] = $time;
                $indb["source"] = $source;
                $this->save($indb);
//            $this->parase_log($link);
            }
        } catch (\Exception $e) {

//            $this->parase_log($e);

        }
        $this->parase_log("nytimes-" . Carbon::now()->toDateTimeString());
    }

    /**
     * http://www.newswise.com/articles/list?category=latest
     */
    public function newswise($html)
    {
        $indb = array();
//        echo $html;
        foreach ($html->find('tr') as $tr) {
            if ($tr->find('td.excerpt h3 a', 0) && $tr->find('td form', 0)) {
//                echo $tr;
                $time = trim($tr->find('td.article-info .date', 0)->innertext);
                $link = "http://www.newswise.com" . $tr->find('td form', 0)->action;
                $title = $tr->find('td.excerpt h3 a', 0)->innertext;
                $time = Carbon::createFromTimestamp(strtotime($time), "Asia/shanghai")->format('Y-m-d H:i');
                $source = "newswise";
                $indb["title"] = trim($title);
                $indb["link"] = $link;
                $indb["time"] = $time;
                $indb["source"] = $source;
                $this->save($indb);
//                $this->parase_log($link);
            }
        }
        $this->parase_log("newswise-" . Carbon::now()->toDateTimeString());
    }

    /**
     * https://www.statnews.com/category/health/
     */
    public function statnews($html)
    {
        $indb = array();
        foreach ($html->find("#content .card-grid .card-grid-item") as $list) {
//            echo $list;
            try {
                $indb["link"] = $list->find("h3.post-title a", 0)->href;
                $indb["title"] = trim($list->find("h3.post-title a span", 0)->innertext);
                $indb["source"] = "statnews";
                $indb["time"] = "";
                $this->save($indb);
//                $this->parase_log($indb["link"]);

            } catch (\Exception $e) {

            }


        }
        $this->parase_log("statnews-" . Carbon::now()->toDateTimeString());

    }

    /**
     * https://www.genomeweb.com/breaking-news
     */
    public function genomeweb($html)
    {
        $indb = array();
        foreach ($html->find("#block-system-main .views-group .views-row") as $list) {
            $time = $list->find('.date-display-single', 0)->innertext;
            $link = 'http://www.genomeweb.com' . $list->find('.node-title a', 0)->href;
            $title = $list->find('.node-title a', 0)->innertext;
            $time = Carbon::createFromTimestamp(strtotime($time), "Asia/shanghai")->format('Y-m-d H:i');
            $source = "genomeweb";
            $indb["title"] = trim($title);
            $indb["link"] = $link;
            $indb["time"] = $time;
            $indb["source"] = $source;
            $this->save($indb);
//            $this->parase_log($link);
        }
        $this->parase_log("genomeweb-" . Carbon::now()->toDateTimeString());
    }

    /**
     * http://www.genengnews.com/gen-news-highlights
     */
    public function genengnews($html)
    {
        $count = 1;
        $indb = array();
        foreach ($html->find("ul.a_contentlist li") as $list) {
            if ($count == 1) {
                $count = $count + 1;
                $date = $list->find('.news_date', 0)->innertext;
                continue;
            }
            if ($list->class == "contenttype_subhead") {
                break;
            }

            $time = substr(trim($list->find('.subhead_span4', 0)->innertext), 1, -1);
            $link = 'http://www.genengnews.com' . $list->find('a', 0)->href;
            $title = $list->find('a', 0)->innertext;
            $time = Carbon::createFromTimestamp(strtotime($date . $time), "Asia/shanghai")->format('Y-m-d H:i');
            $source = "genengnews";
            $indb["title"] = trim($title);
            $indb["link"] = $link;
            $indb["time"] = $time;
            $indb["source"] = $source;
            $this->save($indb);
//            $this->parase_log($link);
        }
        $this->parase_log("genengnews-" . Carbon::now()->toDateTimeString());

    }

    /**
     * http://spectrum.ieee.org/biomedical
     */
    public function spectrum($html)
    {
        $indb = array();
        foreach ($html->find("article.biomedical") as $list) {
            $link = 'http://spectrum.ieee.org' . $list->find('a', 0)->href;
            $title = $list->find('.article-title', 0)->innertext;
            $time = $list->find('time', 0)->innertext;
            $source = "spectrum";
            $indb["title"] = trim($title);
            $indb["link"] = $link;
            $indb["time"] = $time;
            $indb["source"] = $source;
            $this->save($indb);
//            $this->parase_log($link);

        }
        $this->parase_log("spectrum-" . Carbon::now()->toDateTimeString());
    }

    /**
     * https://www.technologyreview.com/c/biomedicine/
     */
    public function technologyreview($html)
    {
        $indb = array();
        try {
            $link = 'https://www.technologyreview.com' . $html->find(".l-grid-tz__inner--top .grid-tz .grid-tz__title a", 0)->href;
            $title = $html->find(".l-grid-tz__inner--top .grid-tz .grid-tz__title a", 0)->innertext;
            $time = "";
            $source = "technologyreview";
            $indb["title"] = trim($title);
            $indb["link"] = $link;
            $indb["time"] = $time;
            $indb["source"] = $source;
            $this->save($indb);
//        $this->parase_log($link);
            foreach ($html->find(".l-grid-tz__inner--row .grid-tz") as $list) {
                $link = 'https://www.technologyreview.com' . $list->find(".grid-tz__title a", 0)->href;
                $title = $list->find('.grid-tz__title a', 0)->innertext;
                $source = "technologyreview";
                $indb["title"] = trim($title);
                $indb["link"] = $link;
                $indb["time"] = $time;
                $indb["source"] = $source;
                $this->save($indb);
//            $this->parase_log($link);

            }
        } catch (\Exception $e) {

        }

        $this->parase_log("technologyreview-" . Carbon::now()->toDateTimeString());

    }

    /**
     * http://www.fiercebiotech.com/
     * @param $html
     */
    public function  fiercebiotech($html)
    {
        $indb = array();
        foreach ($html->find('#main') as $tr) {
            $link = "http://www.fiercebiotech.com" . $tr->find('.main-image a', 0)->href;
            $title = $tr->find('.main-image .list-title a', 0)->innertext;
            $source = "fiercebiotech";
            $time = "";
            $indb["title"] = trim($title);
            $indb["link"] = $link;
            $indb["time"] = $time;
            $indb["source"] = $source;
            $this->save($indb);
//            $this->parase_log($link);
            foreach ($tr->find('.grid-list .list-title a') as $a) {
                $link = 'http://www.fiercebiotech.com' . $a->href;
                $title = $a->innertext;
                $source = "fiercebiotech";
                $indb["title"] = trim($title);
                $indb["link"] = $link;
                $indb["time"] = $time;
                $indb["source"] = $source;
                $this->save($indb);
//                $this->parase_log($link);
            }

        }
        foreach ($html->find(".list-group div.card") as $list) {
            $link = 'http://www.fiercebiotech.com' . $list->find('.list-title a', 0)->href;
            $title = $list->find('.list-title a', 0)->innertext;
            $time = $list->find('time', 0)->innertext . ' EST';
            $time = Carbon::createFromTimestamp(strtotime($time), "Asia/shanghai")->format('Y-m-d H:i');
            $source = "fiercebiotech";
            $indb["title"] = trim($title);
            $indb["link"] = $link;
            $indb["time"] = $time;
            $indb["source"] = $source;
            $this->save($indb);
//            $this->parase_log($link);

        }
        $this->parase_log("fiercebiotech-" . Carbon::now()->toDateTimeString());
    }

    /**
     * http://www.fiercepharma.com/
     * @param $html
     */
    public function  fiercepharma($html)
    {
        $indb = array();
        foreach ($html->find('#main') as $tr) {
            $link = "http://www.fiercepharma.com" . $tr->find('.main-image a', 0)->href;
            $title = $tr->find('.main-image .list-title a', 0)->innertext;
            $source = "fiercepharma";
            $time = "";
            $indb["title"] = trim($title);
            $indb["link"] = $link;
            $indb["time"] = $time;
            $indb["source"] = $source;
            $this->save($indb);
//            $this->parase_log($link);
            foreach ($tr->find('.grid-list .list-title a') as $a) {
                $link = 'http://www.fiercepharma.com' . $a->href;
                $title = $a->innertext;
                $source = "fiercepharma";
                $indb["title"] = trim($title);
                $indb["link"] = $link;
                $indb["time"] = $time;
                $indb["source"] = $source;
                $this->save($indb);
//                $this->parase_log($link);
            }

        }
        foreach ($html->find(".list-group div.card") as $list) {
            $link = 'http://www.fiercepharma.com' . $list->find('.list-title a', 0)->href;
            $title = $list->find('.list-title a', 0)->innertext;
            $time = $list->find('time', 0)->innertext . ' EST';
            $time = Carbon::createFromTimestamp(strtotime($time), "Asia/shanghai")->format('Y-m-d H:i');
            $source = "fiercepharma";
            $indb["title"] = trim($title);
            $indb["link"] = $link;
            $indb["time"] = $time;
            $indb["source"] = $source;
            $this->save($indb);
//            $this->parase_log($link);

        }
        $this->parase_log("fiercepharma-" . Carbon::now()->toDateTimeString());
    }

    /**
     * http://www.fiercemedicaldevices.com/
     * @param $html
     */
    public function  fiercemedicaldevices($html)
    {
        $indb = array();
        foreach ($html->find('#main') as $tr) {
            $link = "http://www.fiercebiotech.com" . $tr->find('.main-image a', 0)->href;
            $title = $tr->find('.main-image .list-title a', 0)->innertext;
            $source = "fiercebiotech";
            $time = "";
            $indb["title"] = trim($title);
            $indb["link"] = $link;
            $indb["time"] = $time;
            $indb["source"] = $source;
            $this->save($indb);
//            $this->parase_log($link);
            foreach ($tr->find('.grid-list .list-title a') as $a) {
                $link = 'http://www.fiercebiotech.com' . $a->href;
                $title = $a->innertext;
                $source = "fiercebiotech";
                $indb["title"] = trim($title);
                $indb["link"] = $link;
                $indb["time"] = $time;
                $indb["source"] = $source;
                $this->save($indb);
//                $this->parase_log($link);
            }

        }
        foreach ($html->find(".list-group div.card") as $list) {
            $link = 'http://www.fiercebiotech.com' . $list->find('.list-title a', 0)->href;
            $title = $list->find('.list-title a', 0)->innertext;
            $time = $list->find('time', 0)->innertext . ' EST';
            $time = Carbon::createFromTimestamp(strtotime($time), "Asia/shanghai")->format('Y-m-d H:i');
            $source = "fiercebiotech";
            $indb["title"] = trim($title);
            $indb["link"] = $link;
            $indb["time"] = $time;
            $indb["source"] = $source;
            $this->save($indb);
//            $this->parase_log($link);
        }
        $this->parase_log("fiercemedicaldevices-" . Carbon::now()->toDateTimeString());
    }

    /**
     * http://www.theatlantic.com/health/
     * @param $html
     */
    public function theatlantic($html)
    {

        $indb = array();
        $header = $html->find(".river-body .hero", 0);
        $link = "http://www.theatlantic.com" . $header->find("a", 0)->href;
        $title = $header->find("h2.hed", 0)->innertext;
        $time = Carbon::createFromTimestamp(strtotime($header->find("time", 0)->datetime), "Asia/shanghai")->format('Y-m-d H:i');
        $source = "theatlantic";
        $indb["title"] = trim($title);
        $indb["link"] = $link;
        $indb["time"] = $time;
        $indb["source"] = $source;
        $this->save($indb);
//        $this->parase_log($link.$title.$time);
        foreach ($html->find(".river-body ul.river li.blog-article") as $list) {
            $link = "http://www.theatlantic.com" . $list->find("a", 0)->href;
            $title = $list->find("h2.hed", 0)->innertext;
            $time = Carbon::createFromTimestamp(strtotime($list->find("time", 0)->datetime), "Asia/shanghai")->format('Y-m-d H:i');
            $source = "theatlantic";
            $indb["title"] = trim($title);
            $indb["link"] = $link;
            $indb["time"] = $time;
            $indb["source"] = $source;
//            $this->parase_log($link.$title.$time);
            $this->save($indb);
        }
        $this->parase_log("theatlantic-" . Carbon::now()->toDateTimeString());
    }

    /**
     * http://www.wsj.com/news/business/health-industry
     */
    public function wsj($html)
    {

        $indb = array();
        foreach ($html->find("ul.hedSumm li") as $list) {
            $link = $list->find(".headline a", 0)->href;
            $title = $list->find(".headline a", 0)->innertext;
            $time = preg_replace("/(\s+)/", ' ', $list->find("time .time-container", 0)->innertext);
            $time = Carbon::createFromTimestamp(strtotime($time), "Asia/shanghai")->format('Y-m-d H:i');
            $source = "wsj";
            $indb["title"] = trim($title);
            $indb["link"] = $link;
            $indb["time"] = $time;
            $indb["source"] = $source;
//            $this->parase_log($link.$title.$time);
            $this->save($indb);
        }
        $this->parase_log("wsj-" . Carbon::now()->toDateTimeString());

    }

    /**
     * http://www.vox.com/science-and-health
     */
    public function vox($html)
    {

        $indb = array();
        foreach ($html->find("div.l-chunk .article") as $list) {
            $link = $list->find("h3 a", 0)->href;
            $title = $list->find("h3 a", 0)->innertext;
            $time = "";
//            $time = Carbon::createFromTimestamp(strtotime($time));
            $source = "vox";
            $indb["title"] = trim($title);
            $indb["link"] = $link;
            $indb["time"] = $time;
            $indb["source"] = $source;
//            $this->parase_log($link.$title.$time);
            $this->save($indb);
        }
        $this->parase_log("vox-" . Carbon::now()->toDateTimeString());

    }

    /**
     * https://www.scientificamerican.com/health/
     */
    public function scientificamerican($html)
    {
        $indb = array();
        try {
            $header = $html->find(".latest-articles-outer h1.t_feature-title", 0);
            $link = $header->find("a", 0)->href;
            $title = $header->find("a", 0)->innertext;
            $time = "";
//            $time = Carbon::createFromTimestamp(strtotime($time));
            $source = "scientificamerican";
            $indb["title"] = trim($title);
            $indb["link"] = $link;
            $indb["time"] = $time;
            $indb["source"] = $source;
//        $this->parase_log($link.$title.$time);
            $this->save($indb);
            foreach ($html->find(".latest-articles-outer h2.t_small-listing-title") as $list) {
                $link = $list->find("a", 0)->href;
                $title = $list->find("a", 0)->innertext;
                $time = "";
//            $time = Carbon::createFromTimestamp(strtotime($time));
                $source = "scientificamerican";
                $indb["title"] = trim($title);
                $indb["link"] = $link;
                $indb["time"] = $time;
                $indb["source"] = $source;
//            $this->parase_log($link.$title.$time);
                $this->save($indb);
            }
        } catch (\Exception $e) {

        }

        $this->parase_log("scientificamerican-" . Carbon::now()->toDateTimeString());
    }

    /**
     * http://www.the-scientist.com/?articles.list/categoryNo/2884/category/Daily-News/
     */
    public function the_scientist($html)
    {
        $indb = array();
        foreach ($html->find(".articleList div.article") as $list) {
            $link = "http://www.the-scientist.com" . $list->find("h4 a", 0)->href;
            $title = $list->find("h4 a", 0)->innertext;
            $time = $list->find(".date", 0)->innertext;
            $time = Carbon::createFromTimestamp(strtotime($time), "Asia/shanghai")->format('Y-m-d H:i');
            $source = "the_scientist";
            $indb["title"] = trim($title);
            $indb["link"] = $link;
            $indb["time"] = $time;
            $indb["source"] = $source;
//            $this->parase_log($link . $title . $time);
            $this->save($indb);
        }
        $this->parase_log("the_scientist-" . Carbon::now()->toDateTimeString());

    }

    /**
     * https://www.sciencenews.org/topic/genes-cells
     * https://www.sciencenews.org/topic/body-brain
     */
    public function sciencenews_gen($html)
    {
        $indb = array();
        foreach ($html->find(".view-newsrail-river .item-list ul li.node-type-openpublish-article") as $list) {
            $link = "https://www.sciencenews.org" . $list->find(".field-content a", 0)->href;
            $title = $list->find(".field-content a", 0)->innertext;
            $time = $list->find(".views-field-published-at span", 0)->innertext;
            $time = Carbon::createFromTimestamp(strtotime($time), "Asia/shanghai")->format('Y-m-d H:i');
            $source = "sciencenews_gen";
            $indb["title"] = trim($title);
            $indb["link"] = $link;
            $indb["time"] = $time;
            $indb["source"] = $source;
//            $this->parase_log($link . $title . $time);
            $this->save($indb);
        }
        $this->parase_log("sciencenews_gen-" . Carbon::now()->toDateTimeString());
    }

    /**
     * http://www.bio-itworld.com/
     */
    public function bio_itworld($html)
    {
        $indb = array();
        foreach ($html->find(".topHeadlines #tabs-1 .top_headline") as $list) {
            echo $list;
            $link = "http://www.bio-itworld.com" . $list->find("a", 0)->href;
            $title = $list->find("a", 0)->innertext;
            $time = $list->find(".ContentDate", 0)->innertext;
            $h_time = Carbon::now()->year;
            $time = $time . ", $h_time";
            $time = Carbon::createFromTimestamp(strtotime($time), "Asia/shanghai")->format('Y-m-d H:i');
            $source = "bio_itworld";
            $indb["title"] = trim($title);
            $indb["link"] = $link;
            $indb["time"] = $time;
            $indb["source"] = $source;
            $this->parase_log($link . $title . $time);
//            $this->save($indb);

        }
        $this->parase_log("bio_itworld-" . Carbon::now()->toDateTimeString());

    }

    /**
     * http://www.theverge.com/health
     */
    public function theverge($html)
    {
        $indb = array();
        foreach ($html->find(".l-col__main .c-river .c-river__entry") as $list) {
            $link = $list->find(".c-entry-box__title a", 0)->href;
            $title = $list->find(".c-entry-box__title a", 0)->innertext;
            $time = $list->find("time.c-byline__item", 0)->innertext;
            $time = Carbon::createFromTimestamp(strtotime(trim($time)), "Asia/shanghai")->format('Y-m-d H:i');
            $source = "theverge";
            $indb["title"] = trim($title);
            $indb["link"] = $link;
            $indb["time"] = $time;
            $indb["source"] = $source;
//            $this->parase_log($link . $title . $time);
            $this->save($indb);

        }
        $this->parase_log("theverge-" . Carbon::now()->toDateTimeString());
    }

    /**
     * http://www.livescience.com/news?type=article&section=health|culture
     */
    public function livescience($html)
    {
//        echo $html;
        $indb = array();
        try {
            foreach ($html->find(".contentListing ul.mod li.search-item") as $list) {
//            echo $list;
                $link = "http://www.livescience.com" . $list->find(".pure-u-3-4 h2 a", 0)->href;
                $title = $list->find(".pure-u-3-4 h2 a", 0)->innertext;
                $time = $list->find("div.date-posted", 0)->innertext;
                $time = explode("|", $time);
                $time = Carbon::createFromTimestamp(strtotime(trim($time[0])), "Asia/shanghai")->format('Y-m-d H:i');
                $source = "livescience";
                $indb["title"] = trim($title);
                $indb["link"] = $link;
                $indb["time"] = $time;
                $indb["source"] = $source;
//            $this->parase_log($link . $title . $time);
                $this->save($indb);

            }

        } catch (\Exception $e) {

        }

        $this->parase_log("livescience-" . Carbon::now()->toDateTimeString());
    }

    /**
     * http://www.asianscientist.com/category/pharma/
     * http://www.asianscientist.com/category/health/
     */
    public function asianscientist_pharma($html)
    {
        $indb = array();
        foreach ($html->find(".au-article-list ul li") as $list) {
            $link = $list->find("h1.inner-title a", 0)->href;
            $title = $list->find("h1.inner-title a", 0)->innertext;
            $time = $list->find("p.au-a-ctrl", 0)->plaintext;
            $time = explode("|", $time);
            $time = Carbon::createFromTimestamp(strtotime(trim($time[1])), "Asia/shanghai")->format('Y-m-d H:i');
            $source = "asianscientist_pharma";
            $indb["title"] = trim($title);
            $indb["link"] = $link;
            $indb["time"] = $time;
            $indb["source"] = $source;
//            $this->parase_log($link . $title . $time);
            $this->save($indb);

        }
        $this->parase_log("asianscientist_pharma-" . Carbon::now()->toDateTimeString());

    }

    /**
     * https://www.mdtmag.com/
     */
    public function mdtmag($html)
    {

        $indb = array();
        foreach ($html->find(".panels-flexible-region-first .pane-front-page-content-listing-grid div.views-row") as $list) {
            $link = "https://www.mdtmag.com" . $list->find(".listing-page-title a", 0)->href;
            $title = $list->find(".listing-page-title a", 0)->innertext;
            $time = $list->find(".listing-page-time", 0)->innertext;
            $date = $list->find(".listing-page-date", 0)->innertext;
            $time = trim($date . " $time");
            $time = Carbon::createFromFormat("m.d.Y h:ia", $time, "Asia/shanghai")->format('Y-m-d H:i');
//            $time = Carbon::createFromTimestamp(strtotime(trim($date." $time")))->format('Y-m-d H:i');
            $source = "mdtmag";
            $indb["title"] = trim($title);
            $indb["link"] = $link;
            $indb["time"] = $time;
            $indb["source"] = $source;
//            $this->parase_log($link . $title . $time);
            $this->save($indb);

        }
        $this->parase_log("mdtmag-" . Carbon::now()->toDateTimeString());
    }

    /**
     * http://www.sciencealert.com/health
     */
    public function sciencealert($html)
    {

        $indb = array();
        foreach ($html->find(".section-container-category .article-container-height") as $list) {
//            echo $list;
            $link = "http://www.sciencealert.com" . $list->find(".title-time-container a", 0)->href;
            $title = $list->find(".title-time-container a", 0)->innertext;
            $time = "";
//            $date = $list->find(".listing-page-date", 0)->innertext;
//            $time = trim($date . " $time");
//            $time = Carbon::createFromFormat("m.d.Y h:ia", $time, "Asia/shanghai")->format('Y-m-d H:i');
//            $time = Carbon::createFromTimestamp(strtotime(trim($date." $time")))->format('Y-m-d H:i');
            $source = "sciencealert";
            $indb["title"] = trim($title);
            $indb["link"] = $link;
            $indb["time"] = $time;
            $indb["source"] = $source;
//            $this->parase_log($link . $title . $time);
            $this->save($indb);

        }
        $this->parase_log("sciencealert-" . Carbon::now()->toDateTimeString());
    }

    /**
     * http://www.npr.org/sections/health-shots/
     */
    public function npr($html)
    {
        $indb = array();
        foreach ($html->find("#overflow .item") as $list) {
//            echo $list;
            $link = $list->find("h2.title a", 0)->href;
            $title = $list->find("h2.title a", 0)->innertext;
            $time = "";
//            $date = $list->find(".listing-page-date", 0)->innertext;
//            $time = trim($date . " $time");
//            $time = Carbon::createFromFormat("m.d.Y h:ia", $time, "Asia/shanghai")->format('Y-m-d H:i');
//            $time = Carbon::createFromTimestamp(strtotime(trim($date." $time")))->format('Y-m-d H:i');
            $source = "npr";
            $indb["title"] = trim($title);
            $indb["link"] = $link;
            $indb["time"] = $time;
            $indb["source"] = $source;
//            $this->parase_log($link . $title . $time);
            $this->save($indb);

        }
        $this->parase_log("npr-" . Carbon::now()->toDateTimeString());
    }

    /**
     * http://www.popsci.com/health
     */
    public function popsci($html)
    {
        $indb = array();
        $list_base = $html->find(".field-page-sections", 0);
        foreach ($list_base->find(".item-list ul li") as $list) {
//            echo $list;
            $link = "http://www.popsci.com" . $list->find("h3.pane-node-title a", 0)->href;
            $title = $list->find("h3.pane-node-title a", 0)->innertext;
            $time = "";
//            $date = $list->find(".listing-page-date", 0)->innertext;
//            $time = trim($date . " $time");
//            $time = Carbon::createFromFormat("m.d.Y h:ia", $time, "Asia/shanghai")->format('Y-m-d H:i');
//            $time = Carbon::createFromTimestamp(strtotime(trim($date." $time")))->format('Y-m-d H:i');
            $source = "popsci";
            $indb["title"] = trim($title);
            $indb["link"] = $link;
            $indb["time"] = $time;
            $indb["source"] = $source;
//            $this->parase_log($link . $title . $time);
            $this->save($indb);

        }
        $this->parase_log("popsci-" . Carbon::now()->toDateTimeString());
    }

    /**
     * https://www.eurekalert.org/pubnews.php
     */
    public function eurekalert($html)
    {
        $indb = array();
        foreach ($html->find(".main .post") as $list) {
            $link = "https://www.eurekalert.org" . $list->find("a", 0)->href;
            $title = $list->find("h2.post_title", 0)->innertext;
            $time = "";
            $source = "eurekalert";
            $indb["title"] = trim($title);
            $indb["link"] = $link;
            $indb["time"] = $time;
            $indb["source"] = $source;
//            echo $link . $title . $time;
//            $this->parase_log($link . $title . $time);
            $this->save($indb);

        }
        $this->parase_log("eurekalert-" . Carbon::now()->toDateTimeString());
    }

    /**
     * https://techcrunch.com/health/
     */
    public function techcrunch($html)
    {
        $indb = array();
        foreach ($html->find("ul.river li.river-block") as $list) {
//            echo $list;
            $link = $list->find("h2.post-title a", 0)->href;
            $title = $list->find("h2.post-title a", 0)->innertext;
            $time = $list->find("time", 0)->datetime;
            $source = "techcrunch";
            $indb["title"] = trim($title);
            $indb["link"] = $link;
            $indb["time"] = $time;
            $indb["source"] = $source;
//            echo $link . $title . $time;
//            $this->parase_log($link . $title . $time);
            $this->save($indb);

        }
        $this->parase_log("techcrunch-" . Carbon::now()->toDateTimeString());
    }

    /**
     * http://www.mobihealthnews.com/
     */
    public function mobihealthnews($html)
    {
        $indb = array();
        foreach ($html->find(".ds-left .views-row") as $list) {
            $link = "http://www.mobihealthnews.com" . $list->find(".views-field-title .field-content a", 0)->href;
            $title = $list->find(".views-field-title .field-content a", 0)->innertext;
            $time = "";
            $source = "mobihealthnews";
            $indb["title"] = trim($title);
            $indb["link"] = $link;
            $indb["time"] = $time;
            $indb["source"] = $source;
//            echo $link . $title . $time;
//            $this->parase_log($link . $title . $time);
            $this->save($indb);

        }
        $this->parase_log("mobihealthnews-" . Carbon::now()->toDateTimeString());
    }

    /**
     * https://www.fastcompany.com/technology
     */
    public function fastcompany($html)
    {
        $indb = array();
        try {
            $head = $html->find(".homepage-header .homepage-header__article a", 0);
//        echo $html->find(".homepage-header__article a",0);
            $h_link = "https:" . $head->href;
            $h_title = $head->find(".homepage-header__article__title-container__title span", 0)->innertext;
            $time = "";
            $source = "fastcompany";
            $indb["title"] = trim($h_title);
            $indb["link"] = $h_link;
            $indb["time"] = $time;
            $indb["source"] = $source;
//        echo $h_link . $h_title . $time;
//        $this->parase_log($h_link . $h_title . $time);
            $this->save($indb);
            foreach ($html->find(".top-stories-container a") as $list) {
//            echo $list;
                $link = "https:" . $list->href;
                $title = $list->find(".vert-align-title h3.title", 0)->innertext;
                $time = "";
                $source = "fastcompany";
                $indb["title"] = trim($title);
                $indb["link"] = $link;
                $indb["time"] = $time;
                $indb["source"] = $source;
//            echo $link . $title . $time;
//            $this->parase_log($link . $title . $time);
                $this->save($indb);
//
            }
        } catch (\Exception $e) {

        }

        $this->parase_log("fastcompany-" . Carbon::now()->toDateTimeString());
    }

    /**
     * https://singularityhub.com/health/
     */
    public function singularityhub_health($html)
    {
        $indb = array();
        foreach ($html->find(".td_block_wrap .td_module_mx12") as $list) {
//            echo $list;
            $link = $list->find("h3.entry-title a", 0)->href;
            $title = $list->find("h3.entry-title a", 0)->innertext;
            $time = $list->find("time", 0)->innertext;
            $time = Carbon::createFromTimestamp(strtotime(trim($time)), "Asia/shanghai")->format('Y-m-d H:i');
            $source = "singularityhub_health";
            $indb["title"] = trim($title);
            $indb["link"] = $link;
            $indb["time"] = $time;
            $indb["source"] = $source;
//            echo $link . $title . $time;
//            $this->parase_log($link . $title . $time);
            $this->save($indb);
//
        }
        $this->parase_log("singularityhub_health-" . Carbon::now()->toDateTimeString());
    }

    /**
     * https://singularityhub.com/science/
     */
    public function singularityhub_science($html)
    {
        $indb = array();
        foreach ($html->find(".td_block_wrap .td_module_mx12") as $list) {
//            echo $list;
            $link = $list->find("h3.entry-title a", 0)->href;
            $title = $list->find("h3.entry-title a", 0)->innertext;
            $time = $list->find("time", 0)->innertext;
            $time = Carbon::createFromTimestamp(strtotime(trim($time)), "Asia/shanghai")->format('Y-m-d H:i');
            $source = "singularityhub_science";
            $indb["title"] = trim($title);
            $indb["link"] = $link;
            $indb["time"] = $time;
            $indb["source"] = $source;
//            echo $link . $title . $time;
//            $this->parase_log($link . $title . $time);
            $this->save($indb);
//
        }
        $this->parase_log("singularityhub_science-" . Carbon::now()->toDateTimeString());
    }

    /**
     * https://singularityhub.com/technology/
     */
    public function singularityhub_technology($html)
    {
        $indb = array();
        foreach ($html->find(".td_block_wrap .td_module_mx12") as $list) {
//            echo $list;
            $link = $list->find("h3.entry-title a", 0)->href;
            $title = $list->find("h3.entry-title a", 0)->innertext;
            $time = $list->find("time", 0)->innertext;
            $time = Carbon::createFromTimestamp(strtotime(trim($time)), "Asia/shanghai")->format('Y-m-d H:i');
            $source = "singularityhub_technology";
            $indb["title"] = trim($title);
            $indb["link"] = $link;
            $indb["time"] = $time;
            $indb["source"] = $source;
//            echo $link . $title . $time;
//            $this->parase_log($link . $title . $time);
            $this->save($indb);
//
        }
        $this->parase_log("singularityhub_technology-" . Carbon::now()->toDateTimeString());
    }

    /**
     * https://deepmind.com/blog/
     * https://deepmind.com/blog/feed/basic/
     */
    public function deepmind($html)
    {
        $indb = array();
        foreach ($html->find("item") as $list) {
            $link = $list->find("guid", 0)->innertext;
            $title = $list->find("title", 0)->innertext;
            $time = $list->find("pubDate", 0)->innertext;
            $time = Carbon::createFromTimestamp(strtotime(trim($time)))->format('Y-m-d H:i');
            $source = "deepmind";
            $indb["title"] = trim($title);
            $indb["link"] = $link;
            $indb["time"] = $time;
            $indb["source"] = $source;
            $indb["priority"] = 1;
//            $this->parase_log($link . $title . $time);
//            echo $link . $title . $time . "<br/>";
            $this->save($indb);
        }
        $this->parase_log("deepmind-" . Carbon::now()->toDateTimeString());
    }


    /**
     * http://medcitynews.com/
     */
    public function medcitynews($html)
    {
        $indb = array();
        $primary = $html->find(".features .primary", 0);
        echo $primary;
//        $link = $primary->find("h2.post_title a", 0)->href;
//        $title = $primary->find("h2.post_title a", 0)->innertext;
//        $time = "";
//        $source = "medcitynews";
//        $indb["title"] = trim($title);
//        $indb["link"] = $link;
//        $indb["time"] = $time;
//        $indb["source"] = $source;
//        foreach ($html->find(".features .secondary li") as $list) {
//            $link = $list->find("p.title a", 0)->href;
//            $title = $list->find("p.title a", 0)->innertext;
//            $time = "";
//            $source = "medcitynews";
//            $indb["title"] = trim($title);
//            $indb["link"] = $link;
//            $indb["time"] = $time;
//            $indb["source"] = $source;
//            $this->parase_log($link . $title . $time);
////            $this->save($indb);

//        }
        $this->parase_log("medcitynews-" . Carbon::now()->toDateTimeString());
    }

    /**
     * http://www.nature.com/news/
     * @param $html
     */
    public function nature_news($html)
    {
        $indb = array();
        foreach ($html->find(".left ul.article-list .item-group-1") as $list) {
            $link = $list->find(".standard-teaser h3 a", 0)->href;
            $title = $list->find(".standard-teaser h3 a", 0)->plaintext;
            $time = "";
            $source = "nature_news";
            $indb["title"] = trim($title);
            $indb["link"] = $link;
            $indb["time"] = $time;
            $indb["source"] = $source;
            $indb["priority"] = 1;
//            $this->parase_log($link . $title . $time);
            $this->save($indb);

        }
        $this->parase_log("nature_news-" . Carbon::now()->toDateTimeString());
    }

    /*
     * http://www.nature.com/nature/research/biological-sciences.html
     */
    public function nature_biological($html)
    {
        $indb = array();
        foreach ($html->find("#issues ul.article-list li article") as $list) {
            $link = $list->find(".standard-teaser h1 a", 0)->href;
            $title = $list->find(".standard-teaser h1 a", 0)->plaintext;
            $time = $list->find(".time", 0)->innertext;;
            $time = Carbon::createFromTimestamp(strtotime(trim($time)))->format('Y-m-d H:i');
            $source = "nature_biological";
            $indb["title"] = trim($title);
            $indb["link"] = $link;
            $indb["time"] = $time;
            $indb["source"] = $source;
            $indb["priority"] = 1;
//            $this->parase_log($link . $title . $time);
            $this->save($indb);

        }
        $this->parase_log("nature_biological-" . Carbon::now()->toDateTimeString());
    }

    /**
     * http://www.nature.com/nbt/research/index.html
     * @param $html
     */
    public function nature_nbt_research($html)
    {
        $indb = array();
        foreach ($html->find(".content-body ol li article") as $list) {
            $link = $list->find(".standard-teaser h1 a", 0)->href;
            $title = $list->find(".standard-teaser h1 a", 0)->plaintext;
            $time = $list->find("time", 0)->innertext;
            $time = Carbon::createFromTimestamp(strtotime(trim($time)))->format('Y-m-d H:i');
            $source = "nature_nbt_research";
            $indb["title"] = trim($title);
            $indb["link"] = $link;
            $indb["time"] = $time;
            $indb["source"] = $source;
            $indb["priority"] = 1;
//            $this->parase_log($link.$title.$time);
            $this->save($indb);
        }
        $this->parase_log("nature_nbt_research-" . Carbon::now()->toDateTimeString());
    }

    /**
     * http://www.nature.com/nbt/newsandcomment/index.html
     * @param $html
     */
    public function nature_nbt_news($html)
    {
        $indb = array();
        foreach ($html->find(".content-body ol li article") as $list) {
            $link = $list->find(".standard-teaser h1 a", 0)->href;
            $title = $list->find(".standard-teaser h1 a", 0)->plaintext;
            $time = $list->find("time", 0)->innertext;
            $time = Carbon::createFromTimestamp(strtotime(trim($time)))->format('Y-m-d H:i');
            $source = "nature_nbt_news";
            $indb["title"] = trim($title);
            $indb["link"] = $link;
            $indb["time"] = $time;
            $indb["source"] = $source;
            $indb["priority"] = 1;
//            $this->parase_log($link.$title.$time);
            $this->save($indb);
        }
        $this->parase_log("nature_nbt_news-" . Carbon::now()->toDateTimeString());
    }

    /**
     * http://www.nature.com/ng/research/index.html
     * @param $html
     */
    public function nature_ng_research($html)
    {
        $indb = array();
        foreach ($html->find(".content-body ol li article") as $list) {
            $link = $list->find(".standard-teaser h1 a", 0)->href;
            $title = $list->find(".standard-teaser h1 a", 0)->plaintext;
            $time = $list->find("time", 0)->innertext;
            $time = Carbon::createFromTimestamp(strtotime(trim($time)))->format('Y-m-d H:i');
            $source = "nature_ng_research";
            $indb["title"] = trim($title);
            $indb["link"] = $link;
            $indb["time"] = $time;
            $indb["source"] = $source;
            $indb["priority"] = 1;
//            $this->parase_log($link.$title.$time);
            $this->save($indb);
        }
        $this->parase_log("nature_ng_research-" . Carbon::now()->toDateTimeString());
    }

    /**
     * http://www.nature.com/ng/newsandcomment/index.html
     * @param $html
     */
    public function nature_ng_news($html)
    {
        $indb = array();
        foreach ($html->find(".content-body ol li article") as $list) {
            $link = $list->find(".standard-teaser h1 a", 0)->href;
            $title = $list->find(".standard-teaser h1 a", 0)->plaintext;
            $time = $list->find("time", 0)->innertext;
            $time = Carbon::createFromTimestamp(strtotime(trim($time)))->format('Y-m-d H:i');
            $source = "nature_ng_news";
            $indb["title"] = trim($title);
            $indb["link"] = $link;
            $indb["time"] = $time;
            $indb["source"] = $source;
            $indb["priority"] = 1;
//            $this->parase_log($link.$title.$time);
            $this->save($indb);
        }
        $this->parase_log("nature_ng_news-" . Carbon::now()->toDateTimeString());
    }

    /**
     * http://www.nature.com/ni/research/index.html
     * @param $html
     */
    public function nature_ni_research($html)
    {
        $indb = array();
        foreach ($html->find(".content-body ol li article") as $list) {
            $link = $list->find(".standard-teaser h1 a", 0)->href;
            $title = $list->find(".standard-teaser h1 a", 0)->plaintext;
            $time = $list->find("time", 0)->innertext;
            $time = Carbon::createFromTimestamp(strtotime(trim($time)))->format('Y-m-d H:i');
            $source = "nature_ni_research";
            $indb["title"] = trim($title);
            $indb["link"] = $link;
            $indb["time"] = $time;
            $indb["source"] = $source;
            $indb["priority"] = 1;
//            $this->parase_log($link.$title.$time);
            $this->save($indb);
        }
        $this->parase_log("nature_ni_research-" . Carbon::now()->toDateTimeString());
    }

    /**
     * http://www.nature.com/ni/newsandcomment/index.html
     * @param $html
     */
    public function nature_ni_news($html)
    {
        $indb = array();
        foreach ($html->find(".content-body ol li article") as $list) {
            $link = $list->find(".standard-teaser h1 a", 0)->href;
            $title = $list->find(".standard-teaser h1 a", 0)->plaintext;
            $time = $list->find("time", 0)->innertext;
            $time = Carbon::createFromTimestamp(strtotime(trim($time)))->format('Y-m-d H:i');
            $source = "nature_ni_news";
            $indb["title"] = trim($title);
            $indb["link"] = $link;
            $indb["time"] = $time;
            $indb["source"] = $source;
            $indb["priority"] = 1;
//            $this->parase_log($link.$title.$time);
            $this->save($indb);
        }
        $this->parase_log("nature_ni_news-" . Carbon::now()->toDateTimeString());
    }

    /**
     * http://www.nature.com/nm/research/index.html
     * @param $html
     */
    public function nature_nm_research($html)
    {
        $indb = array();
        foreach ($html->find(".content-body ol li article") as $list) {
            $link = $list->find(".standard-teaser h1 a", 0)->href;
            $title = $list->find(".standard-teaser h1 a", 0)->plaintext;
            $time = $list->find("time", 0)->innertext;
            $time = Carbon::createFromTimestamp(strtotime(trim($time)))->format('Y-m-d H:i');
            $source = "nature_nm_research";
            $indb["title"] = trim($title);
            $indb["link"] = $link;
            $indb["time"] = $time;
            $indb["source"] = $source;
            $indb["priority"] = 1;
//            $this->parase_log($link.$title.$time);
            $this->save($indb);
        }
        $this->parase_log("nature_nm_research-" . Carbon::now()->toDateTimeString());
    }

    /**
     * http://www.nature.com/nm/newsandcomment/index.html
     * @param $html
     */
    public function nature_nm_news($html)
    {
        $indb = array();
        foreach ($html->find(".content-body ol li article") as $list) {
            $link = $list->find(".standard-teaser h1 a", 0)->href;
            $title = $list->find(".standard-teaser h1 a", 0)->plaintext;
            $time = $list->find("time", 0)->innertext;
            $time = Carbon::createFromTimestamp(strtotime(trim($time)))->format('Y-m-d H:i');
            $source = "nature_nm_news";
            $indb["title"] = trim($title);
            $indb["link"] = $link;
            $indb["time"] = $time;
            $indb["source"] = $source;
            $indb["priority"] = 1;
//            $this->parase_log($link.$title.$time);
            $this->save($indb);
        }
        $this->parase_log("nature_nm_news-" . Carbon::now()->toDateTimeString());
    }

    /**
     * http://www.nature.com/nmicrobiol/news-and-comment
     * @param $html
     */
    public function nature_nmicrobiol_news($html)
    {
        $indb = array();
        foreach ($html->find(".content ul.ma0 li article") as $list) {
            $link = "http://www.nature.com" . $list->find("h3.mb10 a", 0)->href;
            $title = $list->find("h3.mb10 a", 0)->plaintext;
            $time = $list->find("time", 0)->innertext;
            $time = Carbon::createFromTimestamp(strtotime(trim($time)))->format('Y-m-d H:i');
            $source = "nature_nmicrobiol_news";
            $indb["title"] = trim($title);
            $indb["link"] = $link;
            $indb["time"] = $time;
            $indb["source"] = $source;
            $indb["priority"] = 1;
//            $this->parase_log($link.$title.$time);
            $this->save($indb);
        }
        $this->parase_log("nature_nmicrobiol_news-" . Carbon::now()->toDateTimeString());
    }

    /**
     * http://www.nature.com/nmicrobiol/research
     * @param $html
     */
    public function nature_nmicrobiol_research($html)
    {
        $indb = array();
        foreach ($html->find(".content ul.ma0 li article") as $list) {
            $link = "http://www.nature.com" . $list->find("h3.mb10 a", 0)->href;
            $title = $list->find("h3.mb10 a", 0)->plaintext;
            $time = $list->find("time", 0)->innertext;
            $time = Carbon::createFromTimestamp(strtotime(trim($time)))->format('Y-m-d H:i');
            $source = "nature_nmicrobiol_research";
            $indb["title"] = trim($title);
            $indb["link"] = $link;
            $indb["time"] = $time;
            $indb["source"] = $source;
            $indb["priority"] = 1;
//            $this->parase_log($link.$title.$time);
            $this->save($indb);
        }
        $this->parase_log("nature_nmicrobiol_research-" . Carbon::now()->toDateTimeString());
    }

    /**
     * http://www.nature.com/nnano/newsandcomment/index.html
     * @param $html
     */
    public function nature_nnano_news($html)
    {
        $indb = array();
        foreach ($html->find(".content-body ol li article") as $list) {
            $link = $list->find(".standard-teaser h1 a", 0)->href;
            $title = $list->find(".standard-teaser h1 a", 0)->plaintext;
            $time = $list->find("time", 0)->innertext;
            $time = Carbon::createFromTimestamp(strtotime(trim($time)))->format('Y-m-d H:i');
            $source = "nature_nnano_news";
            $indb["title"] = trim($title);
            $indb["link"] = $link;
            $indb["time"] = $time;
            $indb["source"] = $source;
            $indb["priority"] = 1;
//            $this->parase_log($link.$title.$time);
            $this->save($indb);
        }
        $this->parase_log("nature_nnano_news-" . Carbon::now()->toDateTimeString());
    }

    /**
     * http://www.nature.com/nnano/research/index.html
     * @param $html
     */
    public function nature_nnano_research($html)
    {
        $indb = array();
        foreach ($html->find(".content-body ol li article") as $list) {
            $link = $list->find(".standard-teaser h1 a", 0)->href;
            $title = $list->find(".standard-teaser h1 a", 0)->plaintext;
            $time = $list->find("time", 0)->innertext;
            $time = Carbon::createFromTimestamp(strtotime(trim($time)))->format('Y-m-d H:i');
            $source = "nature_nnano_research";
            $indb["title"] = trim($title);
            $indb["link"] = $link;
            $indb["time"] = $time;
            $indb["source"] = $source;
            $indb["priority"] = 1;
//            $this->parase_log($link.$title.$time);
            $this->save($indb);
        }
        $this->parase_log("nature_nnano_research-" . Carbon::now()->toDateTimeString());
    }

    /**
     * http://www.nature.com/neuro/research/index.html
     * @param $html
     */
    public function nature_neuro_research($html)
    {
        $indb = array();
        foreach ($html->find(".content-body ol li article") as $list) {
            $link = $list->find(".standard-teaser h1 a", 0)->href;
            $title = $list->find(".standard-teaser h1 a", 0)->plaintext;
            $time = $list->find("time", 0)->innertext;
            $time = Carbon::createFromTimestamp(strtotime(trim($time)))->format('Y-m-d H:i');
            $source = "nature_neuro_research";
            $indb["title"] = trim($title);
            $indb["link"] = $link;
            $indb["time"] = $time;
            $indb["source"] = $source;
            $indb["priority"] = 1;
//            $this->parase_log($link.$title.$time);
            $this->save($indb);
        }
        $this->parase_log("nature_neuro_research-" . Carbon::now()->toDateTimeString());
    }

    /**
     * http://www.nature.com/neuro/newsandcomment/index.html
     * @param $html
     */
    public function nature_neuro_news($html)
    {
        $indb = array();
        foreach ($html->find(".content-body ol li article") as $list) {
            $link = $list->find(".standard-teaser h1 a", 0)->href;
            $title = $list->find(".standard-teaser h1 a", 0)->plaintext;
            $time = $list->find("time", 0)->innertext;
            $time = Carbon::createFromTimestamp(strtotime(trim($time)))->format('Y-m-d H:i');
            $source = "nature_neuro_news";
            $indb["title"] = trim($title);
            $indb["link"] = $link;
            $indb["time"] = $time;
            $indb["source"] = $source;
            $indb["priority"] = 1;
//            $this->parase_log($link.$title.$time);
            $this->save($indb);
        }
        $this->parase_log("nature_neuro_news-" . Carbon::now()->toDateTimeString());
    }

    /**
     * http://www.cell.com/cell/newarticles
     * @param $html
     */
    public function cell($html)
    {
        $indb = array();
//        echo $html;
        foreach ($html->find("ul.articleCitations .articleCitation") as $list) {
            $link = "http://www.cell.com" . $list->find("h2.title a", 0)->href;
            $title = $list->find("h2.title a", 0)->plaintext;
            $time = $list->find(".published-online", 0)->innertext;
            $time = explode(":", $time);
            $time = Carbon::createFromTimestamp(strtotime(trim($time[1])))->format('Y-m-d H:i');
            $source = "cell";
            $indb["title"] = trim($title);
            $indb["link"] = $link;
            $indb["time"] = $time;
            $indb["source"] = $source;
            $indb["priority"] = 1;
//            $this->parase_log($link . $title . $time);
            $this->save($indb);
        }
        $this->parase_log("cell-" . Carbon::now()->toDateTimeString());
    }

    public function cell_current($html)
    {
        $indb = array();
        $time = $html->find("h1.headings .date", 0)->innertext;
        $time = Carbon::createFromTimestamp(strtotime(trim($time)))->format('Y-m-d H:i');
        foreach ($html->find("ul.articleCitations .articleCitation") as $list) {
            $link = "http://www.cell.com" . $list->find("h4.title a", 0)->href;
            $title = $list->find("h4.title a", 0)->plaintext;
            $source = "cell";
            $indb["title"] = trim($title);
            $indb["link"] = $link;
            $indb["time"] = $time;
            $indb["source"] = $source;
            $indb["priority"] = 1;
//            echo $link . $title . $time . "</br>";
//            $this->parase_log($link . $title );
            $this->save($indb);
        }
        $this->parase_log("cell_current-" . Carbon::now()->toDateTimeString());
    }

    /**
     * http://www.sciencemag.org/news/latest-news
     * @param $html
     */
    public function sciencemag_news($html)
    {
        $indb = array();
        try {
            foreach ($html->find("ul.headline-list article.media") as $list) {
                $link = "http://www.sciencemag.org" . $list->find("h2.media__headline a", 0)->href;
                $title = $list->find("h2.media__headline a", 0)->plaintext;
                $time = $list->find("time", 0)->innertext;
                $time = Carbon::createFromTimestamp(strtotime(trim($time)))->format('Y-m-d H:i');
                $source = "sciencemag_news";
                $indb["title"] = trim($title);
                $indb["link"] = $link;
                $indb["time"] = $time;
                $indb["source"] = $source;
                $indb["priority"] = 1;
//            $this->parase_log($link . $title . $time);
                $this->save($indb);
            }
        } catch (\Exception $e) {

        }

        $this->parase_log("sciencemag_news-" . Carbon::now()->toDateTimeString());
    }

    /**
     * http://advances.sciencemag.org/
     * @param $html
     */
    public function sciencemag_advances($html)
    {
        $indb = array();
        try {
            foreach ($html->find("ul.headline-list article.media") as $list) {
                $link = "http://advances.sciencemag.org" . $list->find("h3.media__headline a", 0)->href;
                $title = $list->find("h3.media__headline a .media__headline__title", 0)->plaintext;
                $time = $list->find("time", 0)->innertext;
                $time = Carbon::createFromTimestamp(strtotime(trim($time)))->format('Y-m-d H:i');
                $source = "sciencemag_advances";
                $indb["title"] = trim($title);
                $indb["link"] = $link;
                $indb["time"] = $time;
                $indb["source"] = $source;
                $indb["priority"] = 1;
//            $this->parase_log($link . $title . $time);
                $this->save($indb);
            }
        } catch (\Exception $e) {

        }

        $this->parase_log("sciencemag_advances-" . Carbon::now()->toDateTimeString());
    }

    /**
     * http://robotics.sciencemag.org/
     * @param $html
     */
    public function sciencemag_robotics($html)
    {
        $indb = array();
        try {
            foreach ($html->find("ul.issue-toc article.media") as $list) {
                $link = "http://robotics.sciencemag.org" . $list->find("h3.media__headline a", 0)->href;
                $title = $list->find("h3.media__headline a .media__headline__title", 0)->plaintext;
                $time = $list->find("time", 0)->innertext;
                $time = Carbon::createFromTimestamp(strtotime(trim($time)))->format('Y-m-d H:i');
                $source = "sciencemag_robotics";
                $indb["title"] = trim($title);
                $indb["link"] = $link;
                $indb["time"] = $time;
                $indb["source"] = $source;
                $indb["priority"] = 1;
//            $this->parase_log($link . $title . $time);
                $this->save($indb);
            }
        } catch (\Exception $e) {

        }

        $this->parase_log("sciencemag_robotics-" . Carbon::now()->toDateTimeString());
    }

    /**
     * http://stm.sciencemag.org/
     * @param $html
     */
    public function sciencemag_stm($html)
    {
        $indb = array();
        foreach ($html->find("ul.issue-toc article.media") as $list) {
            $link = "http://stm.sciencemag.org" . $list->find("h3.media__headline a", 0)->href;
            $title = $list->find("h3.media__headline a .media__headline__title", 0)->plaintext;
            $time = $list->find("time", 0)->innertext;
            $time = Carbon::createFromTimestamp(strtotime(trim($time)))->format('Y-m-d H:i');
            $source = "sciencemag_stm";
            $indb["title"] = trim($title);
            $indb["link"] = $link;
            $indb["time"] = $time;
            $indb["source"] = $source;
            $indb["priority"] = 1;
//            $this->parase_log($link . $title . $time);
            $this->save($indb);
        }
        $this->parase_log("sciencemag_stm-" . Carbon::now()->toDateTimeString());
    }

    /**
     * http://www.nejm.org/medical-articles/research
     * @param $html
     */
    public function nejm($html)
    {
        $indb = array();
        foreach ($html->find("ul.searchResults .articleEntry") as $list) {
            $link = "http://www.nejm.org" . $list->find("h2.articleLink a", 0)->href;
            $title = $list->find("h2.articleLink a", 0)->plaintext;
            $time = $list->find("ul.meta li.firstChild", 0)->innertext;
            $time = Carbon::createFromTimestamp(strtotime(trim($time)))->format('Y-m-d H:i');
            $source = "nejm";
            $indb["title"] = trim($title);
            $indb["link"] = $link;
            $indb["time"] = $time;
            $indb["source"] = $source;
            $indb["priority"] = 1;
//            $this->parase_log($link . $title . $time);
            $this->save($indb);
        }
        $this->parase_log("nejm-" . Carbon::now()->toDateTimeString());
    }

    public function nejm_toc($html)
    {
        $indb = array();
        foreach ($html->find(".articleGrouping .articleEntry") as $list) {
            $link = "http://www.nejm.org" . $list->find(".articleLink a", 0)->href;
            $title = $list->find(".articleLink a", 0)->plaintext;
            $time = Carbon::now()->format('Y-m-d H:i');
//            $time = Carbon::createFromTimestamp(strtotime(trim($time)))->format('Y-m-d H:i');
            $source = "nejm";
            $indb["title"] = trim($title);
            $indb["link"] = $link;
            $indb["time"] = $time;
            $indb["source"] = $source;
            $indb["priority"] = 1;
//            $this->parase_log($link . $title . $time);
            $this->save($indb);
        }
        $this->parase_log("nejm_toc-" . Carbon::now()->toDateTimeString());
    }


    /**
     * http://www.thelancet.com/online-first-news-comment
     * @param $html
     */
    public function thelancet_news($html)
    {
        $indb = array();
        foreach ($html->find("ul.articleCitations .articleCitation") as $list) {
            $link = "http://www.thelancet.com" . $list->find("h4.title a", 0)->href;
            $title = $list->find("h4.title a", 0)->plaintext;
            $time = $list->find(".published-online", 0)->innertext;
            $time = explode(":", $time);
            $time = Carbon::createFromTimestamp(strtotime(trim($time[1])))->format('Y-m-d H:i');
            $source = "thelancet_news";
            $indb["title"] = trim($title);
            $indb["link"] = $link;
            $indb["time"] = $time;
            $indb["source"] = $source;
            $indb["priority"] = 1;
//            $this->parase_log($link . $title . $time);
            $this->save($indb);
        }
        $this->parase_log("thelancet_news-" . Carbon::now()->toDateTimeString());
    }

    /**
     * http://www.thelancet.com/online-first-research
     * @param $html
     */
    public function thelancet_research($html)
    {
        $indb = array();
        try {
            $html_s = $html->find("section.mostRecent", 0);
//            echo $html;
            foreach ($html_s->find("ul.articleCitations .articleCitation") as $list) {
//            echo $list;
                $link = "http://www.thelancet.com" . $list->find("h4.title a", 0)->href;
                $title = $list->find("h4.title a", 0)->plaintext;
                $time = $list->find(".published-online", 0)->innertext;
                $time = explode(":", $time);
                $time = Carbon::createFromTimestamp(strtotime(trim($time[1])))->format('Y-m-d H:i');
                $source = "thelancet_research";
                $indb["title"] = trim($title);
                $indb["link"] = $link;
                $indb["time"] = $time;
                $indb["source"] = $source;
                $indb["priority"] = 1;
//            $this->parase_log($link . $title . $time);
                $this->save($indb);
            }
        } catch (\Exception $e) {

        }
        $this->parase_log("thelancet_research-" . Carbon::now()->toDateTimeString());
    }

    /**
     * http://jamanetwork.com/journals/jama/currentissue
     * @param $html
     */
    public function jamanetwork($html)
    {
        $indb = array();
//        echo $html;
        foreach ($html->find(".issue-view-tab--contents .issue-group-articles .article") as $list) {
            $link = $list->find("h3.article--title a", 0)->href;
            $title = $list->find("h3.article--title a", 0)->plaintext;
            $time = "";
            $source = "jamanetwork";
            $indb["title"] = trim($title);
            $indb["link"] = $link;
            $indb["time"] = $time;
            $indb["source"] = $source;
            $indb["priority"] = 1;
//            $this->parase_log($link . $title . $time);
            $this->save($indb);
        }
        $this->parase_log("jamanetwork-" . Carbon::now()->toDateTimeString());
    }

    /**
     * https://elifesciences.org/
     * @param $html
     */
    public function elifesciences($html)
    {
        $indb = array();
        foreach ($html->find(".view-content .home-article-listing__list-item") as $list) {
            $link = "https://elifesciences.org" . $list->find("h2.article-teaser__title a", 0)->href;
            $title = $list->find("h2.article-teaser__title a", 0)->plaintext;
            $time = $list->find("time", 0)->datetime;
            $time = Carbon::createFromTimestamp(strtotime(trim($time)))->format('Y-m-d H:i');
            $source = "elifesciences";
            $indb["title"] = trim($title);
            $indb["link"] = $link;
            $indb["time"] = $time;
            $indb["source"] = $source;
            $indb["priority"] = 1;
//            $this->parase_log($link . $title . $time);
            $this->save($indb);
        }
        $this->parase_log("elifesciences-" . Carbon::now()->toDateTimeString());
    }

    /**
     * http://www.bmj.com/news/news?category=News
     * @param $html
     */
    public function bmj_news($html)
    {
        $indb = array();
        foreach ($html->find("ul.highwire-views-col-1 li.views-row") as $list) {
            $link = "http://www.bmj.com" . $list->find("h3.channel-featured-title a", 0)->href;
            $title = $list->find("h3.channel-featured-title a", 0)->plaintext;
            $time = $list->find(".views-field-field-highwire-a-epubdate .field-content", 0)->innertext;
            $time = Carbon::createFromTimestamp(strtotime(trim($time)))->format('Y-m-d H:i');
            $source = "bmj_news";
            $indb["title"] = trim($title);
            $indb["link"] = $link;
            $indb["time"] = $time;
            $indb["source"] = $source;
            $indb["priority"] = 1;
//            $this->parase_log($link . $title . $time);
            $this->save($indb);
        }
        $this->parase_log("bmj_news-" . Carbon::now()->toDateTimeString());
    }

    /**
     * http://www.bmj.com/research/research
     * @param $html
     */
    public function bmj_research($html)
    {
        $indb = array();
        foreach ($html->find("ul.highwire-views-col-1 li.views-row") as $list) {
            $link = "http://www.bmj.com" . $list->find("h3.channel-featured-title a", 0)->href;
            $title = $list->find("h3.channel-featured-title a", 0)->plaintext;
            $time = $list->find(".views-field-field-highwire-a-epubdate .field-content", 0)->innertext;
            $time = Carbon::createFromTimestamp(strtotime(trim($time)))->format('Y-m-d H:i');
            $source = "bmj_research";
            $indb["title"] = trim($title);
            $indb["link"] = $link;
            $indb["time"] = $time;
            $indb["source"] = $source;
            $indb["priority"] = 1;
//            $this->parase_log($link . $title . $time);
            $this->save($indb);
        }
        $this->parase_log("bmj_research-" . Carbon::now()->toDateTimeString());
    }

    /**
     * http://www.bmj.com/research/research%20news
     * @param $html
     */
    public function bmj_research_news($html)
    {
        $indb = array();
        foreach ($html->find("ul.highwire-views-col-1 li.views-row") as $list) {
            $link = "http://www.bmj.com" . $list->find("h3.channel-featured-title a", 0)->href;
            $title = $list->find("h3.channel-featured-title a", 0)->innertext;
            $time = $list->find(".views-field-field-highwire-a-epubdate .field-content", 0)->innertext;
            $time = Carbon::createFromTimestamp(strtotime(trim($time)))->format('Y-m-d H:i');
            $source = "bmj_research_news";
            $indb["title"] = trim($title);
            $indb["link"] = $link;
            $indb["time"] = $time;
            $indb["source"] = $source;
            $indb["priority"] = 1;
//            $this->parase_log($link . $title . $time);
            $this->save($indb);
        }
        $this->parase_log("bmj_research_news-" . Carbon::now()->toDateTimeString());
    }

    //医学
    public function ca_cancer($html)
    {
        $indb = array();
        foreach ($html->find("item") as $list) {
            $link = $list->find("prism:url", 0)->innertext;
            $title = $list->find("title", 0)->innertext;
            $time = $list->find("dc:date", 0)->innertext;
            $time = Carbon::createFromTimestamp(strtotime(trim($time)))->format('Y-m-d H:i');
            $source = "ca_cancer";
            $indb["title"] = trim($title);
            $indb["link"] = $link;
            $indb["time"] = $time;
            $indb["source"] = $source;
            $indb["priority"] = 1;
//            $this->parase_log($link . $title . $time);
//            echo $link . $title . $time . "<br/>";
            $this->save($indb);
        }
        $this->parase_log("ca_cancer-" . Carbon::now()->toDateTimeString());
    }

    public function immunity($html)
    {
        $indb = array();
        foreach ($html->find("item") as $list) {

            $link_t = trim($list->innertext);
            $pos_s = strpos($link_t, "<link>");
            $pos_e = strpos($link_t, "</link>");
            $link = substr($link_t, $pos_s + 6, $pos_e - $pos_s - 6);
            $title = $list->find("title", 0)->innertext;
            $time = $list->find("dc:date", 0)->innertext;
            $time = Carbon::createFromTimestamp(strtotime(trim($time)))->format('Y-m-d H:i');
            $source = "immunity";
            $indb["title"] = trim($title);
            $indb["link"] = trim($link);
            $indb["time"] = $time;
            $indb["source"] = $source;
            $indb["priority"] = 1;
//            $this->parase_log($link . $title . $time);
//            echo $link . $title . $time . "<br/>";
            $this->save($indb);
        }
        $this->parase_log("immunity-" . Carbon::now()->toDateTimeString());
    }

    public function annals($html)
    {
        $indb = array();
        foreach ($html->find("item") as $list) {
            $link = $list->find("guid", 0)->innertext;
            $title = $list->find("title", 0)->innertext;
            $time = $list->find("pubDate", 0)->innertext;
            $time = Carbon::createFromTimestamp(strtotime(trim($time)))->format('Y-m-d H:i');
            $source = "Annals_of_Internal_Medicine";
            $indb["title"] = trim($title);
            $indb["link"] = $link;
            $indb["time"] = $time;
            $indb["source"] = $source;
            $indb["priority"] = 1;
//            $this->parase_log($link . $title . $time);
//            echo $link . $title . $time . "<br/>";
            $this->save($indb);
        }
        $this->parase_log("annals-" . Carbon::now()->toDateTimeString());
    }

    public function jco($html)
    {
        $indb = array();
        foreach ($html->find("rss:item") as $list) {
            $link = $list->find("rss:link", 0)->innertext;
            $title = $list->find("rss:title", 0)->innertext;
            $time = $list->find("dc:date", 0)->innertext;
            $time = Carbon::createFromTimestamp(strtotime(trim($time)))->format('Y-m-d H:i');
            $source = "Journal_of_Clinical_Oncology";
            $indb["title"] = trim($title);
            $indb["link"] = $link;
            $indb["time"] = $time;
            $indb["source"] = $source;
            $indb["priority"] = 1;
//            $this->parase_log($link . $title . $time);
//            echo $link . $title . $time . "<br/>";
            $this->save($indb);
        }
        $this->parase_log("jco-" . Carbon::now()->toDateTimeString());
    }

    public function gastrojournal($html)
    {
        $indb = array();

        foreach ($html->find("item") as $list) {
            try {
                $title = $list->find("title", 0)->innertext;

                $link_t = $list->innertext;
                $pos_s = strpos($link_t, "<link>");
                $pos_e = strpos($link_t, "</link>");
                $link = substr($link_t, $pos_s + 6, $pos_e - $pos_s - 6);

                $time = $list->find("dc:date", 0)->innertext;
                $time = Carbon::createFromTimestamp(strtotime(trim($time)))->format('Y-m-d H:i');
                $source = "gastrojournal";
                $indb["title"] = trim($title);
                $indb["link"] = trim($link);
                $indb["time"] = $time;
                $indb["source"] = $source;
                $indb["priority"] = 1;
//            $this->parase_log($link . $title . $time);
//                echo $link . $title . $time . "<br/>";
                $this->save($indb);
            } catch (\Exception $e) {

            }
        }


        $this->parase_log("gastrojournal-" . Carbon::now()->toDateTimeString());
    }

    public function annals_surgery($html)
    {
        $indb = array();

        foreach ($html->find("ul.articleCitations .articleCitation") as $list) {
            try {
//                echo $list;
                $title = $list->find("h5.title a", 0)->plaintext;
                $link = "http://www.gastrojournal.org" . $list->find("h5.title a", 0)->href;
                $time = $list->find(".published-online", 0)->innertext;
                $time = explode(":", $time);
                $time = Carbon::createFromTimestamp(strtotime(trim($time[1])))->format('Y-m-d H:i');
                $source = "annals_surgery";
                $indb["title"] = trim($title);
                $indb["link"] = $link;
                $indb["time"] = $time;
                $indb["source"] = $source;
                $indb["priority"] = 1;
//            $this->parase_log($link . $title . $time);
//                echo $link . $title . $time . "<br/>";
                $this->save($indb);
            } catch (\Exception $e) {

            }
        }
        $this->parase_log("annals_surgery-" . Carbon::now()->toDateTimeString());
    }

    public function jci($html)
    {
        $indb = array();
        foreach ($html->find(".content-wrapper .row .medium-9") as $list) {
//            echo $list;
            $link = "https://www.jci.org" . $list->find("h5.article-title a", 0)->href;
            $title = $list->find("h5.article-title a", 0)->innertext;
            $time = $list->find(".article-published-at", 0)->innertext;
            $time = substr($time, 10);
            $time = Carbon::createFromTimestamp(strtotime(trim($time)))->format('Y-m-d H:i');
            $source = "Journal_of_Clinical_Investigation";
            $indb["title"] = trim($title);
            $indb["link"] = $link;
            $indb["time"] = $time;
            $indb["source"] = $source;
            $indb["priority"] = 1;
//            $this->parase_log($link . $title . $time);
//            echo $link . $title . $time . "<br/>";
            $this->save($indb);
        }
        $this->parase_log("jci-" . Carbon::now()->toDateTimeString());
    }

    public function cell_neurosciences($html)
    {
        $indb = array();
//        echo $html;
        foreach ($html->find("ul.articleCitations .articleCitation") as $list) {
            $link = "http://www.cell.com" . $list->find("h2.title a", 0)->href;
            $title = $list->find("h2.title a", 0)->plaintext;
            $time = $list->find(".published-online", 0)->innertext;
            $time = explode(":", $time);
            $time = Carbon::createFromTimestamp(strtotime(trim($time[1])))->format('Y-m-d H:i');
            $source = "cell_neurosciences";
            $indb["title"] = trim($title);
            $indb["link"] = $link;
            $indb["time"] = $time;
            $indb["source"] = $source;
            $indb["priority"] = 1;
//            $this->parase_log($link . $title . $time);
//            echo $link . $title . $time . "<br/>";
            $this->save($indb);
        }
        $this->parase_log("cell_neurosciences-" . Carbon::now()->toDateTimeString());

    }

    public function brain_sciences($html)
    {
        $indb = array();
        foreach ($html->find(".overview .details") as $list) {
            $link = "https://www.cambridge.org" . $list->find(".title a", 0)->href;
            $title = $list->find(".title a", 0)->plaintext;
            $time = $list->find(".published .date", 0)->innertext;
            $time = Carbon::createFromTimestamp(strtotime(trim($time)))->format('Y-m-d H:i');
            $source = "behavioral_brain_sciences";
            $indb["title"] = trim($title);
            $indb["link"] = $link;
            $indb["time"] = $time;
            $indb["source"] = $source;
            $indb["priority"] = 1;
//            $this->parase_log($link . $title . $time);
//            echo $link . $title . $time . "<br/>";
//            $this->save($indb);
        }
        $this->parase_log("behavioral_brain_sciences-" . Carbon::now()->toDateTimeString());
    }

    public function jbjsjournal($html)
    {
        $indb = array();
        foreach ($html->find("item") as $list) {
//            echo $list;
            $link_t = $list->innertext;
            $pos_s = strpos($link_t, "<link>");
            $pos_e = strpos($link_t, "</link>");
            $link = substr($link_t, $pos_s + 6, $pos_e - $pos_s - 6);

            $title = substr($list->find("title", 0)->plaintext, 9, -3);
            $time = $list->find("pubDate", 0)->innertext;
            $time = Carbon::createFromTimestamp(strtotime(trim($time)))->format('Y-m-d H:i');
            $source = "jbjsjournal";
            $indb["title"] = trim($title);
            $indb["link"] = trim($link);
            $indb["time"] = $time;
            $indb["source"] = $source;
            $indb["priority"] = 1;
//            $this->parase_log($link . $title . $time);
//            echo $link . "</br>" . $title . $time . "</br>";
            $this->save($indb);
        }
        $this->parase_log("jbjsjournal-" . Carbon::now()->toDateTimeString());
    }


    /**
     * https://www.sciencedaily.com/news/top/health/
     * @param $html
     */
    public function sciencedaily($html)
    {
        $indb = array();
        foreach ($html->find("item") as $list) {
            $link = $list->find("guid", 0)->innertext;
            $title = $list->find("title", 0)->innertext;
            $time = $list->find("pubDate", 0)->innertext;
            $time = Carbon::createFromTimestamp(strtotime(trim($time)))->format('Y-m-d H:i');
            $source = "sciencedaily";
            $indb["title"] = trim($title);
            $indb["link"] = $link;
            $indb["time"] = $time;
            $indb["source"] = $source;
            $indb["priority"] = 1;
//            $this->parase_log($link . $title . $time);
//            echo $link . $title . $time . "<br/>";
            $this->save($indb);
        }
        $this->parase_log("sciencedaily-" . Carbon::now()->toDateTimeString());
    }

    public function fellowplus($html)
    {
        $indb = array();
//        var_dump($html);
        foreach ($html->find("item") as $list) {
            try{

                $id = $list->find("id", 0)->innertext;
                $fid = $list->find("fellow_data_id", 0)->innertext;

                $ulink = $list->find("unique_str", 0)->innertext;
                $name = $list->find("name", 0)->innertext;
                $des = $list->find("introduce", 0)->innertext;
                $logo = $list->find("logo", 0)->innertext;
                $city = $list->find("city", 0)->innertext;
                $trades = $list->find("trades", 0)->innertext;
                $tags = $list->find("tags", 0)->innertext;
                $round = $list->find("turn", 0)->innertext;
                $money = $list->find("funding_money", 0)->innertext;
                $ftime = $list->find("funding_time", 0)->innertext;

                $C = Companies::where("name","like","%".$name."%")->get();
                if(count($C)==0){
                    $link = "https://fellowplus.com/data/index.html#!/main/project/".$ulink."/0/0";
                    $this->parase_log($link,"fellow");
//                    var_dump($name);
                }

            }
            catch(\Exception $e){

            }



//            $this->parase_log($link . $title . $time);
//            echo $link . $title . $time . "<br/>";
//            $this->save($indb);
        }
//        $this->parase_log("sciencedaily-" . Carbon::now()->toDateTimeString());
    }

    /**
     *  http://www.forbes.com/healthcare/
     */
    public function forbes($html)
    {
        $indb = array();
        $hero = $html->find(".csr-hero", 0);
        $link = $hero->find(".csr-hero-content a", 0)->href;
        $title = $hero->find(".csr-hero-content h2.fs-h2 span", 0)->innertext;
        $time = "";
        $source = "forbes";
        $indb["title"] = trim($title);
        $indb["link"] = $link;
        $indb["time"] = $time;
        $indb["source"] = $source;
        $indb["priority"] = 1;
        $this->save($indb);

        $related = $html->find("ul.related-articles li.related-article");
        foreach ($related as $list) {
            $link = $list->find("a", 0)->href;
            $title = $list->find("h3.related-article-headline span", 0)->innertext;
            $time = "";
            $source = "forbes";
            $indb["title"] = trim($title);
            $indb["link"] = $link;
            $indb["time"] = $time;
            $indb["source"] = $source;
            $indb["priority"] = 1;
            $this->save($indb);
        }

        foreach ($html->find("ul.edittools-stream li.et-promoblock-star-item") as $list) {
            $link = $list->find("a", 0)->href;
            $title = $list->find("h2.article-headline span", 0)->innertext;
            $time = $list->find("time.article-time", 0)->innertext;
            $time = Carbon::createFromTimestamp(strtotime(trim($time)))->format('Y-m-d H:i');
            $source = "forbes";
            $indb["title"] = trim($title);
            $indb["link"] = $link;
            $indb["time"] = $time;
            $indb["source"] = $source;
            $indb["priority"] = 1;
//            $this->parase_log($link . $title . $time);
//            echo $link . $title . $time . "<br/>";
            $this->save($indb);
        }
        $this->parase_log("forbes-" . Carbon::now()->toDateTimeString());
    }


    /**
     *
     * @param $html
     */

    public function itjuzi_funding($html)
    {
        $indb = array();
        $count = 1;
        foreach ($html->find(".list-main-eventset li") as $list) {
            if ($count == 1) {
                $count++;
                continue;
            }
            $link = $list->find(".maincell .title a", 0)->href;
            $company = trim($list->find(".maincell .title span", 0)->innertext);
            $time = trim($list->find(".date span", 0)->innertext);
            $round = trim($list->find(".round a span", 0)->innertext);
            $amount = trim($list->find(".money", 0)->innertext);
            $invest = trim($list->find(".investorset", 0)->innertext);
            $invest_ = explode("<br>", $invest);
            $invest = [];
            foreach ($invest_ as $k => $v) {
                if ($v != "") {
                    $invest[] = strip_tags($v);
                }
            }
//            $invest = preg_replace("/(\s+)/", ' ', $invest);
//            $invest = explode(" ", $invest);
            $indb["company"] = trim($company);
            $indb["link"] = $link;
//            echo $link.$company.$time.$round.$amount;
            $indb["time"] = Carbon::createFromFormat('Y.m.d', $time);
            $indb["source"] = "it桔子";
            $indb["priority"] = 1;
            $indb["round"] = $round;
            $indb["amount"] = $amount;
            $indb["invest"] = $invest;
//            echo $link.$company.$time.$round.$amount;
            $this->save_funds($indb);

        }
        $this->parase_log("itjuzi-" . Carbon::now()->toDateTimeString());
    }

    public function itjuzi_funding_f($html)
    {
        $indb = array();
        $count = 1;
        foreach ($html->find(".list-main-eventset li") as $list) {
//            echo $list;
            if ($count == 1) {
                $count++;
                continue;
            }
            $link = $list->find(".maincell .title a", 0)->href;
            $company = trim($list->find(".maincell .title span", 0)->innertext);
            $time = trim($list->find(".date span", 0)->innertext);
            $round = trim($list->find(".round a span", 0)->innertext);
            $amount = trim($list->find(".money", 0)->innertext);
            $invest = trim($list->find(".investorset", 0)->innertext);
            $invest_ = explode("<br>", $invest);
            $invest = [];
            foreach ($invest_ as $k => $v) {
                if ($v != "") {
                    $invest[] = strip_tags($v);
                }
            }
            $indb["company"] = trim($company);
            $indb["link"] = $link;
//            echo $link.$company.$time.$round.$amount;
            $indb["time"] = Carbon::createFromFormat('Y.m.d', $time);
            $indb["source"] = "it桔子";
            $indb["priority"] = 1;
            $indb["round"] = $round;
            $indb["amount"] = $amount;
            $indb["invest"] = $invest;
            $this->save_funds($indb);
        }
        $this->parase_log("itjuzif-" . Carbon::now()->toDateTimeString());
    }

    /**
     *  http://www.cyzone.cn/event/list-764-3497-1-0-0-0-0/
     * @param $html
     */

    public function cyzone($html)
    {
        $indb = array();
        $count = 1;

        foreach ($html->find(".list-table3 tr.table-plate3") as $list) {
//            echo $list;

            $link = $list->find(".tp2_tit a", 0)->href;
            $company = trim($list->find(".tp2_tit a", 0)->innertext);
            $round = $list->find(".tp-mean", 0)->next_sibling()->innertext;
            $time = $list->find(".tp3", 0)->next_sibling()->next_sibling()->innertext;
            $amount = trim($list->find(".money", 0)->innertext);
            $invest = trim($list->find(".tp3", 0)->plaintext);
            $invest = preg_replace("/(\s+)/", ' ', $invest);
            $invest = explode(" ", $invest);
            $indb["company"] = trim($company);
            $indb["link"] = $link;
            $indb["time"] = Carbon::createFromFormat('Y-m-d', $time);
            $indb["source"] = "创业邦";
            $indb["priority"] = 1;
            $indb["round"] = $round;
            $indb["amount"] = $amount;
            $indb["invest"] = $invest;
//            var_dump($invest);
//            var_dump($time);
//            $out = "|" . $company . "|" . $round . "|" . $amount . "|";
//            $this->parase_log($out);
            $this->save_funds($indb);

        }
        $this->parase_log("创业邦-" . Carbon::now()->toDateTimeString());
    }

    public function healthpolicy($html)
    {
        foreach ($html->find(".singleresult") as $list) {
            $link = "http://www.healthpolicy.cn" . $list->find(".summarytitle a", 0)->href;
            echo $link . "</br>";
            $this->parase_log($link, "policy");
        }
        $this->parase_log("policy-" . Carbon::now()->toDateTimeString());
    }
    //http://www.nhfpc.gov.cn/yzygj/pqt/new_list.shtml
    /**
     * @param $html
     */
    public function nhfpc_news($html)
    {
        $indb = array();
        foreach ($html->find("ul.zxxx_list li") as $list) {
            $link = $list->find("a", 0)->href;
            $link = "http://www.nhfpc.gov.cn" . substr($link, 5);
            $title = $list->find("a", 0)->innertext;
            $time = $list->find(".ml", 0)->innertext;
            $time = Carbon::createFromTimestamp(strtotime(trim($time)))->format('Y-m-d H:i');
            $source = "卫计委医政医管局最新信息";
            $indb["title"] = trim($title);
            $indb["link"] = $link;
            $indb["time"] = $time;
            $indb["source"] = $source;
//            $indb["priority"] = 1;
//            $this->parase_log($link . $title . $time);
//            echo $link . $title . $time . "<br/>";
            $this->save_p($indb);
        }
        $this->parase_log("nhfpc_news-" . Carbon::now()->toDateTimeString());
    }

    /**
     * @param $html
     */
    public function nhfpc_policy($html)
    {
        $indb = array();
        foreach ($html->find("ul.zxxx_list li") as $list) {
            $link = $list->find("a", 0)->href;
            $link = "http://www.nhfpc.gov.cn" . substr($link, 5);
            $title = $list->find("a", 0)->innertext;
            $time = $list->find(".ml", 0)->innertext;
            $time = Carbon::createFromTimestamp(strtotime(trim($time)))->format('Y-m-d H:i');
            $source = "卫计委医政医管局政策文件";
            $indb["title"] = trim($title);
            $indb["link"] = $link;
            $indb["time"] = $time;
            $indb["source"] = $source;
//            $indb["priority"] = 1;
//            $this->parase_log($link . $title . $time);
//            echo $link . $title . $time . "<br/>";
            $this->save_p($indb);
        }
        $this->parase_log("nhfpc_policy-" . Carbon::now()->toDateTimeString());
    }

    /**
     * @param $html
     */
    public function nhfpc_trends($html)
    {
        $indb = array();
        foreach ($html->find("ul.zxxx_list li") as $list) {
            $link = $list->find("a", 0)->href;
            $link = "http://www.nhfpc.gov.cn" . substr($link, 5);
            $title = $list->find("a", 0)->innertext;
            $time = $list->find(".ml", 0)->innertext;
            $time = Carbon::createFromTimestamp(strtotime(trim($time)))->format('Y-m-d H:i');
            $source = "卫计委医政医管局工作动态";
            $indb["title"] = trim($title);
            $indb["link"] = $link;
            $indb["time"] = $time;
            $indb["source"] = $source;
//            $indb["priority"] = 1;
//            $this->parase_log($link . $title . $time);
//            echo $link . $title . $time . "<br/>";
            $this->save_p($indb);
        }
        $this->parase_log("nhfpc_trends-" . Carbon::now()->toDateTimeString());
    }

    /**
     * @param $html
     */
    public function nhfpc_tigs_news($html)
    {
        $indb = array();
        foreach ($html->find("ul.zxxx_list li") as $list) {
            $link = $list->find("a", 0)->href;
            $link = "http://www.nhfpc.gov.cn" . substr($link, 5);
            $title = $list->find("a", 0)->innertext;
            $time = $list->find(".ml", 0)->innertext;
            $time = Carbon::createFromTimestamp(strtotime(trim($time)))->format('Y-m-d H:i');
            $source = "卫计委体制改革司最新消息";
            $indb["title"] = trim($title);
            $indb["link"] = $link;
            $indb["time"] = $time;
            $indb["source"] = $source;
//            $indb["priority"] = 1;
//            $this->parase_log($link . $title . $time);
//            echo $link . $title . $time . "<br/>";
            $this->save_p($indb);
        }
        $this->parase_log("nhfpc_tigs_news-" . Carbon::now()->toDateTimeString());
    }

    /**
     * @param $html
     */
    public function nhfpc_tigs_policy($html)
    {
        $indb = array();
        foreach ($html->find("ul.zxxx_list li") as $list) {
            $link = $list->find("a", 0)->href;
            $link = "http://www.nhfpc.gov.cn" . substr($link, 5);
            $title = $list->find("a", 0)->innertext;
            $time = $list->find(".ml", 0)->innertext;
            $time = Carbon::createFromTimestamp(strtotime(trim($time)))->format('Y-m-d H:i');
            $source = "卫计委体制改革司政策文件";
            $indb["title"] = trim($title);
            $indb["link"] = $link;
            $indb["time"] = $time;
            $indb["source"] = $source;
//            $indb["priority"] = 1;
//            $this->parase_log($link . $title . $time);
//            echo $link . $title . $time . "<br/>";
            $this->save_p($indb);
        }
        $this->parase_log("nhfpc_tigs_policy-" . Carbon::now()->toDateTimeString());
    }

    /**
     * @param $html
     */
    public function nhfpc_tigs_trends($html)
    {
        $indb = array();
        foreach ($html->find("ul.zxxx_list li") as $list) {
            $link = $list->find("a", 0)->href;
            $link = "http://www.nhfpc.gov.cn" . substr($link, 5);
            $title = $list->find("a", 0)->innertext;
            $time = $list->find(".ml", 0)->innertext;
            $time = Carbon::createFromTimestamp(strtotime(trim($time)))->format('Y-m-d H:i');
            $source = "卫计委体制改革司工作动态";
            $indb["title"] = trim($title);
            $indb["link"] = $link;
            $indb["time"] = $time;
            $indb["source"] = $source;
//            $indb["priority"] = 1;
//            $this->parase_log($link . $title . $time);
//            echo $link . $title . $time . "<br/>";
            $this->save_p($indb);
        }
        $this->parase_log("nhfpc_tigs_trends-" . Carbon::now()->toDateTimeString());
    }

    /**
     * @param $html
     */
    public function nhfpc_jws_policy($html)
    {
        $indb = array();
        foreach ($html->find("ul.zxxx_list li") as $list) {
            $link = $list->find("a", 0)->href;
            $link = "http://www.nhfpc.gov.cn" . substr($link, 5);
            $title = $list->find("a", 0)->innertext;
            $time = $list->find(".ml", 0)->innertext;
            $time = Carbon::createFromTimestamp(strtotime(trim($time)))->format('Y-m-d H:i');
            $source = "卫计委基层卫生司政策文件";
            $indb["title"] = trim($title);
            $indb["link"] = $link;
            $indb["time"] = $time;
            $indb["source"] = $source;
//            $indb["priority"] = 1;
//            $this->parase_log($link . $title . $time);
//            echo $link . $title . $time . "<br/>";
            $this->save_p($indb);
        }
        $this->parase_log("nhfpc_jws_policy-" . Carbon::now()->toDateTimeString());
    }

    /**
     * @param $html
     */
    public function nhfpc_jws_news($html)
    {
        $indb = array();
        foreach ($html->find("ul.zxxx_list li") as $list) {
            $link = $list->find("a", 0)->href;
            $link = "http://www.nhfpc.gov.cn" . substr($link, 5);
            $title = $list->find("a", 0)->innertext;
            $time = $list->find(".ml", 0)->innertext;
            $time = Carbon::createFromTimestamp(strtotime(trim($time)))->format('Y-m-d H:i');
            $source = "卫计委基层卫生司最新消息";
            $indb["title"] = trim($title);
            $indb["link"] = $link;
            $indb["time"] = $time;
            $indb["source"] = $source;
//            $indb["priority"] = 1;
//            $this->parase_log($link . $title . $time);
//            echo $link . $title . $time . "<br/>";
            $this->save_p($indb);
        }
        $this->parase_log("nhfpc_jws_news-" . Carbon::now()->toDateTimeString());
    }


    /**
     * @param $html
     */
    public function nhfpc_jws_trends($html)
    {
        $indb = array();
        foreach ($html->find("ul.zxxx_list li") as $list) {
            $link = $list->find("a", 0)->href;
            $link = "http://www.nhfpc.gov.cn" . substr($link, 5);
            $title = $list->find("a", 0)->innertext;
            $time = $list->find(".ml", 0)->innertext;
            $time = Carbon::createFromTimestamp(strtotime(trim($time)))->format('Y-m-d H:i');
            $source = "卫计委基层卫生司工作动态";
            $indb["title"] = trim($title);
            $indb["link"] = $link;
            $indb["time"] = $time;
            $indb["source"] = $source;
//            $indb["priority"] = 1;
//            $this->parase_log($link . $title . $time);
//            echo $link . $title . $time . "<br/>";
            $this->save_p($indb);
        }
        $this->parase_log("nhfpc_jws_trends-" . Carbon::now()->toDateTimeString());
    }

    public function nhfpc_gwyxx($html)
    {
        $indb = array();
        foreach ($html->find("ul.zxxx_list li") as $list) {
            $link = $list->find("a", 0)->href;
            $link = "http://www.nhfpc.gov.cn" . substr($link, 5);
            $title = $list->find("a", 0)->innertext;
            $time = $list->find(".ml", 0)->innertext;
            $time = Carbon::createFromTimestamp(strtotime(trim($time)))->format('Y-m-d H:i');
            $source = "卫计委";
            $indb["title"] = trim($title);
            $indb["link"] = $link;
            $indb["time"] = $time;
            $indb["source"] = $source;
//            $indb["priority"] = 1;
//            $this->parase_log($link . $title . $time);
//            echo $link . $title . $time . "<br/>";
            $this->save_p($indb);
        }
        $this->parase_log("nhfpc_gwyxx-" . Carbon::now()->toDateTimeString());
    }

    public function nhfpc_xwfb($html)
    {
        $indb = array();
        foreach ($html->find("ul.zxxx_list li") as $list) {
            $link = $list->find("a", 0)->href;
            $link = "http://www.nhfpc.gov.cn" . substr($link, 5);
            $title = $list->find("a", 0)->innertext;
            $time = $list->find(".ml", 0)->innertext;
            $time = Carbon::createFromTimestamp(strtotime(trim($time)))->format('Y-m-d H:i');
            $source = "卫计委";
            $indb["title"] = trim($title);
            $indb["link"] = $link;
            $indb["time"] = $time;
            $indb["source"] = $source;
//            $indb["priority"] = 1;
//            $this->parase_log($link . $title . $time);
//            echo $link . $title . $time . "<br/>";
            $this->save_p($indb);
        }
        $this->parase_log("nhfpc_xwfb-" . Carbon::now()->toDateTimeString());
    }

    public function nhfpc_zwgk($html)
    {
        $indb = array();
        foreach ($html->find("ul.index_zcfg_con li") as $list) {
            $link = $list->find("a", 0)->href;
            $link = "http://www.nhfpc.gov.cn/" . substr($link, 3);
            $title = $list->find("a", 0)->title;
            $time = "";
            $source = "卫计委政务公开";
            $indb["title"] = trim($title);
            $indb["link"] = $link;
            $indb["time"] = $time;
            $indb["source"] = $source;
//            $indb["priority"] = 1;
//            $this->parase_log($link . $title . $time);
//            echo $link . $title . $time . "<br/>";
            $this->save_p($indb);
        }
        $this->parase_log("nhfpc_zwgk-" . Carbon::now()->toDateTimeString());
    }

    /**
     * @param $html
     */
    public function china_zhibo($html)
    {
        $indb = array();
        foreach ($html->find("ul.newsList li") as $list) {
            $link = $list->find("a", 0)->href;
            $link = "http://www.china.com.cn/zhibo/" . $link;
            $title = $list->find("a", 0)->innertext;
            $time = $list->find("span", 0)->innertext;
//            $y = Carbon::today()->year;
            $time = Carbon::createFromTimestamp(strtotime(trim($time)))->format('Y-m-d H:i');
            $source = "中国网直播";
            $indb["title"] = trim($title);
            $indb["link"] = $link;
            $indb["time"] = $time;
            $indb["source"] = $source;
//            $indb["priority"] = 1;
//            $this->parase_log($link . $title . $time);
//            echo $link . $title . $time . "<br/>";
            $this->save_p($indb);
        }
        $this->parase_log("china_zhibo-" . Carbon::now()->toDateTimeString());
    }

    /**
     * @param $html
     */
    public function china_zhibo_guoxin($html)
    {
        $indb = array();
        foreach ($html->find("ul.newsList li") as $list) {
            $link = $list->find("a", 0)->href;
            $link = "http://www.china.com.cn/zhibo/" . $link;
            $title = $list->find("a", 0)->innertext;
            $time = $list->find("span", 0)->innertext;
            $time = Carbon::createFromTimestamp(strtotime(trim($time)))->format('Y-m-d H:i');
            $source = "中国网国新办直播";
            $indb["title"] = trim($title);
            $indb["link"] = $link;
            $indb["time"] = $time;
            $indb["source"] = $source;
//            $indb["priority"] = 1;
//            $this->parase_log($link . $title . $time);
//            echo $link . $title . $time . "<br/>";
            $this->save_p($indb);
        }
        $this->parase_log("china_zhibo_guoxin-" . Carbon::now()->toDateTimeString());
    }

    /**
     * @param $html
     */
    public function china_zhibo_chuifeng($html)
    {
        $indb = array();
        foreach ($html->find("ul.newsList li") as $list) {
            $link = $list->find("a", 0)->href;
            $link = "http://www.china.com.cn/zhibo/" . $link;
            $title = $list->find("a", 0)->innertext;
            $time = $list->find("span", 0)->innertext;
            $y = Carbon::today()->year;

            $time = Carbon::createFromTimestamp(strtotime(trim($y . "-" . $time)))->format('Y-m-d H:i');
            $source = "中国网吹风会直播";
            $indb["title"] = trim($title);
            $indb["link"] = $link;
            $indb["time"] = $time;
            $indb["source"] = $source;
//            $indb["priority"] = 1;
//            $this->parase_log($link . $title . $time);
//            echo $link . $title . $time . "<br/>";
            $this->save_p($indb);
        }
        $this->parase_log("china_zhibo_chuifeng-" . Carbon::now()->toDateTimeString());
    }

    /**
     * @param $html
     */
    public function china_zhibo_buwei($html)
    {
        $indb = array();
        foreach ($html->find("ul.newsList li") as $list) {
            $link = $list->find("a", 0)->href;
            $link = "http://www.china.com.cn/zhibo/" . $link;
            $title = $list->find("a", 0)->innertext;
            $time = $list->find("span", 0)->innertext;
            $time = Carbon::createFromTimestamp(strtotime(trim($time)))->format('Y-m-d H:i');
            $source = "中国网部委办直播";
            $indb["title"] = trim($title);
            $indb["link"] = $link;
            $indb["time"] = $time;
            $indb["source"] = $source;
//            $indb["priority"] = 1;
//            $this->parase_log($link . $title . $time);
//            echo $link . $title . $time . "<br/>";
            $this->save_p($indb);
        }
        $this->parase_log("china_zhibo_buwei-" . Carbon::now()->toDateTimeString());
    }

    /**
     * @param $html
     */
    public function china_zhibo_renda($html)
    {
        $indb = array();
        foreach ($html->find("ul.newsList li") as $list) {
            $link = $list->find("a", 0)->href;
            $link = "http://www.china.com.cn/zhibo/" . $link;
            $title = $list->find("a", 0)->innertext;
            $time = $list->find("span", 0)->innertext;
            $y = Carbon::today()->year;

            $time = Carbon::createFromTimestamp(strtotime(trim($y . '-' . $time)))->format('Y-m-d H:i');
            $source = "中国网全国人大直播";
            $indb["title"] = trim($title);
            $indb["link"] = $link;
            $indb["time"] = $time;
            $indb["source"] = $source;
//            $indb["priority"] = 1;
//            $this->parase_log($link . $title . $time);
//            echo $link . $title . $time . "<br/>";
            $this->save_p($indb);
        }
        $this->parase_log("china_zhibo_renda-" . Carbon::now()->toDateTimeString());
    }

    /**
     * @param $html
     */
    public function china_zhibo_huiyi($html)
    {
        $indb = array();
        foreach ($html->find("ul.newsList li") as $list) {
            $link = $list->find("a", 0)->href;
            $link = "http://www.china.com.cn/zhibo/" . $link;
            $title = $list->find("a", 0)->innertext;
            $time = $list->find("span", 0)->innertext;
            $y = Carbon::today()->year;
            $time = Carbon::createFromTimestamp(strtotime(trim($y . '-' . $time)))->format('Y-m-d H:i');
            $source = "中国网会议活动直播";
            $indb["title"] = trim($title);
            $indb["link"] = $link;
            $indb["time"] = $time;
            $indb["source"] = $source;
//            $indb["priority"] = 1;
//            $this->parase_log($link . $title . $time);
//            echo $link . $title . $time . "<br/>";
            $this->save_p($indb);
        }
        $this->parase_log("china_zhibo_huiyi-" . Carbon::now()->toDateTimeString());
    }

//
    public function szhfpc_wzx($html)
    {
        $indb = array();
        foreach ($html->find(".gl-right ul li") as $list) {
            $link = $list->find("a", 0)->href;
            $link = "http://www.szhfpc.gov.cn/wzx/" . substr($link, 2);
            $title = $list->find("a", 0)->innertext;
            $time = $list->find("span", 0)->innertext;
            $time = Carbon::createFromTimestamp(strtotime(trim($time)))->format('Y-m-d H:i');
            $source = "深圳卫计委微资讯";
            $indb["title"] = trim($title);
            $indb["link"] = $link;
            $indb["time"] = $time;
            $indb["source"] = $source;
//            $indb["priority"] = 1;
//            $this->parase_log($link . $title . $time);
//            echo $link . $title . $time . "<br/>";
            $this->save_p($indb);
        }
        $this->parase_log("szhfpc_wzx-" . Carbon::now()->toDateTimeString());
    }

    //
    public function szhfpc_tjsj($html)
    {
        $indb = array();
        foreach ($html->find(".gl-right ul li") as $list) {
            $link = $list->find("a", 0)->href;
            $link = "http://www.szhfpc.gov.cn/xxgk/tjsj/zxtjxx/" . substr($link, 2);
            $title = $list->find("a", 0)->innertext;
            $time = $list->find("span", 0)->innertext;
            $time = Carbon::createFromTimestamp(strtotime(trim($time)))->format('Y-m-d H:i');
            $source = "深圳卫计委统计数据";
            $indb["title"] = trim($title);
            $indb["link"] = $link;
            $indb["time"] = $time;
            $indb["source"] = $source;
//            $indb["priority"] = 1;
//            $this->parase_log($link . $title . $time);
//            echo $link . $title . $time . "<br/>";
            $this->save_p($indb);
        }
        $this->parase_log("szhfpc_tjsj-" . Carbon::now()->toDateTimeString());
    }

    //
    public function szhfpc_gzdt($html)
    {
        $indb = array();
        foreach ($html->find(".gl-right ul li") as $list) {
            $link = $list->find("a", 0)->href;
            $link = "http://www.szhfpc.gov.cn/gzdt/" . substr($link, 2);
            $title = $list->find("a", 0)->innertext;
            $time = $list->find("span", 0)->innertext;
            $time = Carbon::createFromTimestamp(strtotime(trim($time)))->format('Y-m-d H:i');
            $source = "深圳卫计委工作动态";
            $indb["title"] = trim($title);
            $indb["link"] = $link;
            $indb["time"] = $time;
            $indb["source"] = $source;
//            $indb["priority"] = 1;
//            $this->parase_log($link . $title . $time);
//            echo $link . $title . $time . "<br/>";
            $this->save_p($indb);
        }
        $this->parase_log("szhfpc_gzdt-" . Carbon::now()->toDateTimeString());
    }

    //
    public function szhfpc_gzjb($html)
    {
        $indb = array();
        foreach ($html->find(".gl-right ul li") as $list) {
            $link = $list->find("a", 0)->href;
            $link = "http://www.szhfpc.gov.cn/jksz/gzjb/" . substr($link, 2);
            $title = $list->find("a", 0)->innertext;
            $time = $list->find("span", 0)->innertext;
            $time = Carbon::createFromTimestamp(strtotime(trim($time)))->format('Y-m-d H:i');
            $source = "深圳卫计委工作简报";
            $indb["title"] = trim($title);
            $indb["link"] = $link;
            $indb["time"] = $time;
            $indb["source"] = $source;
//            $indb["priority"] = 1;
//            $this->parase_log($link . $title . $time);
//            echo $link . $title . $time . "<br/>";
            $this->save_p($indb);
        }
        $this->parase_log("szhfpc_gzjb-" . Carbon::now()->toDateTimeString());
    }

    //
    public function szhfpc_mybh($html)
    {
        $indb = array();
        foreach ($html->find(".gl-right ul li") as $list) {
            $link = $list->find("a", 0)->href;
            $link = "http://www.szhfpc.gov.cn/xxgk/zcfggfxwj/mybh_5/" . substr($link, 2);
            $title = $list->find("a", 0)->innertext;
            $time = $list->find("span", 0)->innertext;
            $time = Carbon::createFromTimestamp(strtotime(trim($time)))->format('Y-m-d H:i');
            $source = "深圳卫计委医疗监督";
            $indb["title"] = trim($title);
            $indb["link"] = $link;
            $indb["time"] = $time;
            $indb["source"] = $source;
//            $indb["priority"] = 1;
//            $this->parase_log($link . $title . $time);
//            echo $link . $title . $time . "<br/>";
            $this->save_p($indb);
        }
        $this->parase_log("szhfpc_mybh-" . Carbon::now()->toDateTimeString());
    }

    //
    public function szhfpc_zcjd($html)
    {
        $indb = array();
        foreach ($html->find(".gl-right ul li") as $list) {
            $link = $list->find("a", 0)->href;
            $link = "http://www.szhfpc.gov.cn/xxgk/zcfggfxwj/zcjd/" . substr($link, 2);
            $title = $list->find("a", 0)->innertext;
            $time = $list->find("span", 0)->innertext;
            $time = Carbon::createFromTimestamp(strtotime(trim($time)))->format('Y-m-d H:i');
            $source = "深圳卫计委政策解读";
            $indb["title"] = trim($title);
            $indb["link"] = $link;
            $indb["time"] = $time;
            $indb["source"] = $source;
//            $indb["priority"] = 1;
//            $this->parase_log($link . $title . $time);
//            echo $link . $title . $time . "<br/>";
            $this->save_p($indb);
        }
        $this->parase_log("szhfpc_zcjd-" . Carbon::now()->toDateTimeString());
    }

    //
    public function szhfpc_ghjh($html)
    {
        $indb = array();
        foreach ($html->find(".gl-right ul li") as $list) {
            $link = $list->find("a", 0)->href;
            $link = "http://www.szhfpc.gov.cn/xxgk/ghjh/gmjjshfzghjh_3/" . substr($link, 2);
            $title = $list->find("a", 0)->innertext;
            $time = $list->find("span", 0)->innertext;
            $time = Carbon::createFromTimestamp(strtotime(trim($time)))->format('Y-m-d H:i');
            $source = "深圳卫计委发展规划";
            $indb["title"] = trim($title);
            $indb["link"] = $link;
            $indb["time"] = $time;
            $indb["source"] = $source;
//            $indb["priority"] = 1;
//            $this->parase_log($link . $title . $time);
//            echo $link . $title . $time . "<br/>";
            $this->save_p($indb);
        }
        $this->parase_log("szhfpc_ghjh-" . Carbon::now()->toDateTimeString());
    }

    //
    public function szhfpc_zxgh($html)
    {
        $indb = array();
        foreach ($html->find(".gl-right ul li") as $list) {
            $link = $list->find("a", 0)->href;
            $link = "http://www.szhfpc.gov.cn/xxgk/ghjh/zxgh/" . substr($link, 2);
            $title = $list->find("a", 0)->innertext;
            $time = $list->find("span", 0)->innertext;
            $time = Carbon::createFromTimestamp(strtotime(trim($time)))->format('Y-m-d H:i');
            $source = "深圳卫计委专项规划";
            $indb["title"] = trim($title);
            $indb["link"] = $link;
            $indb["time"] = $time;
            $indb["source"] = $source;
//            $indb["priority"] = 1;
//            $this->parase_log($link . $title . $time);
//            echo $link . $title . $time . "<br/>";
            $this->save_p($indb);
        }
        $this->parase_log("szhfpc_zxgh-" . Carbon::now()->toDateTimeString());
    }

    public function szhfpc_ndgzjh($html)
    {
        $indb = array();
        foreach ($html->find(".gl-right ul li") as $list) {
            $link = $list->find("a", 0)->href;
            $link = "http://www.szhfpc.gov.cn/xxgk/ghjh/ndgzjh/" . substr($link, 2);
            $title = $list->find("a", 0)->innertext;
            $time = $list->find("span", 0)->innertext;
            $time = Carbon::createFromTimestamp(strtotime(trim($time)))->format('Y-m-d H:i');
            $source = "深圳卫计委年度规划";
            $indb["title"] = trim($title);
            $indb["link"] = $link;
            $indb["time"] = $time;
            $indb["source"] = $source;
//            $indb["priority"] = 1;
//            $this->parase_log($link . $title . $time);
//            echo $link . $title . $time . "<br/>";
            $this->save_p($indb);
        }
        $this->parase_log("szhfpc_ndgzjh-" . Carbon::now()->toDateTimeString());
    }

    //上海
    public function wsjsw_n422($html)
    {
        $indb = array();
        foreach ($html->find("ul.list li") as $list) {
            $link = $list->find("a", 0)->href;
            $link = "http://www.wsjsw.gov.cn" . $link;
            $tmp = $list->find("a", 0)->innertext;
            $title = substr($tmp, 0, -12);
            $time = substr($tmp, -11, -1);
            $time = Carbon::createFromTimestamp(strtotime(trim($time)))->format('Y-m-d H:i');
            $source = "上海卫计委";
            $indb["title"] = trim($title);
            $indb["link"] = $link;
            $indb["time"] = $time;
            $indb["source"] = $source;
//            $indb["priority"] = 1;
//            $this->parase_log($link . $title . $time);
//            echo $link . $title . $time . "<br/>";
            $this->save_p($indb);
        }
        $this->parase_log("wsjsw_n422-" . Carbon::now()->toDateTimeString());
    }

    //上海
    public function wsjsw_n429($html)
    {
        $indb = array();
        foreach ($html->find("ul.list li") as $list) {
            $link = $list->find("a", 0)->href;
            $link = "http://www.wsjsw.gov.cn" . $link;
            $tmp = $list->find("a", 0)->innertext;
            $title = substr($tmp, 0, -12);
            $time = substr($tmp, -11, -1);
            $time = Carbon::createFromTimestamp(strtotime(trim($time)))->format('Y-m-d H:i');
            $source = "上海卫计委";
            $indb["title"] = trim($title);
            $indb["link"] = $link;
            $indb["time"] = $time;
            $indb["source"] = $source;
//            $indb["priority"] = 1;
//            $this->parase_log($link . $title . $time);
//            echo $link . $title . $time . "<br/>";
            $this->save_p($indb);
        }
        $this->parase_log("wsjsw_n429-" . Carbon::now()->toDateTimeString());
    }

    //上海
    public function wsjsw_n432($html)
    {
        $indb = array();
        foreach ($html->find("ul.list li") as $list) {
            $link = $list->find("a", 0)->href;
            $link = "http://www.wsjsw.gov.cn" . $link;
            $tmp = $list->find("a", 0)->innertext;
            $title = substr($tmp, 0, -12);
            $time = substr($tmp, -11, -1);
            $time = Carbon::createFromTimestamp(strtotime(trim($time)))->format('Y-m-d H:i');
            $source = "上海卫计委";
            $indb["title"] = trim($title);
            $indb["link"] = $link;
            $indb["time"] = $time;
            $indb["source"] = $source;
//            $indb["priority"] = 1;
//            $this->parase_log($link . $title . $time);
//            echo $link . $title . $time . "<br/>";
            $this->save_p($indb);
        }
        $this->parase_log("wsjsw_n432-" . Carbon::now()->toDateTimeString());
    }

    //食药
    public function sda_CL0004($html)
    {
        $indb = array();
        foreach ($html->find("td.ListColumnClass15") as $list) {
            $link = $list->find("a", 0)->href;
            $link = "http://www.sda.gov.cn/WS01" . substr($link, 2);
            $title = $list->find("a", 0)->plaintext;
            $tmp = $list->find("span.listtddate15", 0)->innertext;
            $time = substr($tmp, 1, -2);
            $time = Carbon::createFromTimestamp(strtotime(trim($time)))->format('Y-m-d H:i');
            $source = "食药监局";
            $indb["title"] = trim($title);
            $indb["link"] = $link;
            $indb["time"] = $time;
            $indb["source"] = $source;
            $indb["priority"] = 1;
//            $this->parase_log($link . $title . $time);
//            echo $link . $title . $time . "<br/>";
            $this->save_p($indb);
        }
        $this->parase_log("sda_CL0004-" . Carbon::now()->toDateTimeString());
    }

    /**
     * http://www.sda.gov.cn/WS01/CL0051/
     */
    public function sda_CL0051($html)
    {
        $indb = array();
        foreach ($html->find("td.ListColumnClass15") as $list) {
            $link = $list->find("a", 0)->href;
            $link = "http://www.sda.gov.cn/WS01" . substr($link, 2);
            $title = $list->find("a", 0)->plaintext;
            $tmp = $list->find("span.listtddate15", 0)->innertext;
            $time = substr($tmp, 1, -2);
            $time = Carbon::createFromTimestamp(strtotime(trim($time)))->format('Y-m-d H:i');
            $source = "食药监局";
            $indb["title"] = trim($title);
            $indb["link"] = $link;
            $indb["time"] = $time;
            $indb["source"] = $source;
            $indb["priority"] = 1;
//            $this->parase_log($link . $title . $time);
//            echo $link . $title . $time . "<br/>";
            $this->save_p($indb);
        }
        $this->parase_log("sda_CL0051-" . Carbon::now()->toDateTimeString());
    }

    public function sda_CL0011($html)
    {
        $indb = array();
        foreach ($html->find("td.ListColumnClass15") as $list) {
            $link = $list->find("a", 0)->href;
            $link = "http://www.sda.gov.cn/WS01" . substr($link, 2);
            $title = $list->find("a", 0)->plaintext;
            $tmp = $list->find("span.listtddate15", 0)->innertext;
            $time = substr($tmp, 1, -2);
            $time = Carbon::createFromTimestamp(strtotime(trim($time)))->format('Y-m-d H:i');
            $source = "食药监局";
            $indb["title"] = trim($title);
            $indb["link"] = $link;
            $indb["time"] = $time;
            $indb["source"] = $source;
            $indb["priority"] = 1;
//            $this->parase_log($link . $title . $time);
//            echo $link . $title . $time . "<br/>";
            $this->save_p($indb);
        }
        $this->parase_log("sda_CL0011-" . Carbon::now()->toDateTimeString());
    }

    public function sda_CL0006($html)
    {
        $indb = array();
        foreach ($html->find("td.ListColumnClass15") as $list) {
            $link = $list->find("a", 0)->href;
            $link = "http://www.sda.gov.cn/WS01" . substr($link, 2);
            $title = $list->find("a", 0)->plaintext;
            $tmp = $list->find("span.listtddate15", 0)->innertext;
            $time = substr($tmp, 1, -2);
            $time = Carbon::createFromTimestamp(strtotime(trim($time)))->format('Y-m-d H:i');
            $source = "食药监局";
            $indb["title"] = trim($title);
            $indb["link"] = $link;
            $indb["time"] = $time;
            $indb["source"] = $source;
            $indb["priority"] = 1;
//            $this->parase_log($link . $title . $time);
//            echo $link . $title . $time . "<br/>";
            $this->save_p($indb);
        }
        $this->parase_log("sda_CL0006-" . Carbon::now()->toDateTimeString());
    }

    public function sda_CL1748($html)
    {
        $indb = array();
        foreach ($html->find("td.ListColumnClass15") as $list) {
            $link = $list->find("a", 0)->href;
            $link = "http://www.sda.gov.cn/WS01" . substr($link, 2);
            $title = $list->find("a", 0)->plaintext;
            $tmp = $list->find("span.listtddate15", 0)->innertext;
            $time = substr($tmp, 1, -2);
            $time = Carbon::createFromTimestamp(strtotime(trim($time)))->format('Y-m-d H:i');
            $source = "食药监局";
            $indb["title"] = trim($title);
            $indb["link"] = $link;
            $indb["time"] = $time;
            $indb["source"] = $source;
            $indb["priority"] = 1;
//            $this->parase_log($link . $title . $time);
//            echo $link . $title . $time . "<br/>";
            $this->save_p($indb);
        }
        $this->parase_log("sda_CL1748-" . Carbon::now()->toDateTimeString());

    }

    public function sda_CL1913($html)
    {
        $indb = array();
        foreach ($html->find("td.ListColumnClass15") as $list) {
            $link = $list->find("a", 0)->href;
            $link = "http://www.sda.gov.cn/WS01" . substr($link, 2);
            $title = $list->find("a", 0)->plaintext;
            $tmp = $list->find("span.listtddate15", 0)->innertext;
            $time = substr($tmp, 1, -2);
            $time = Carbon::createFromTimestamp(strtotime(trim($time)))->format('Y-m-d H:i');
            $source = "食药监局";
            $indb["title"] = trim($title);
            $indb["link"] = $link;
            $indb["time"] = $time;
            $indb["source"] = $source;
            $indb["priority"] = 1;
//            $this->parase_log($link . $title . $time);
//            echo $link . $title . $time . "<br/>";
            $this->save_p($indb);
        }
        $this->parase_log("sda_CL1913-" . Carbon::now()->toDateTimeString());
    }

    public function sda_CL0412($html)
    {
        $indb = array();
        foreach ($html->find("td.ListColumnClass2") as $list) {
            $link = $list->find("a", 0)->href;
            $link = "http://www.sda.gov.cn/WS01" . substr($link, 2);
            $title = $list->find("a", 0)->plaintext;
            $tmp = $list->find("span.listtddate2", 0)->innertext;
            $time = substr($tmp, 1, -2);
            $time = Carbon::createFromTimestamp(strtotime(trim($time)))->format('Y-m-d H:i');
            $source = "食药监局";
            $indb["title"] = trim($title);
            $indb["link"] = $link;
            $indb["time"] = $time;
            $indb["source"] = $source;
            $indb["priority"] = 1;
//            $this->parase_log($link . $title . $time);
//            echo $link . $title . $time . "<br/>";
            $this->save_p($indb);
        }
        $this->parase_log("sda_CL0412-" . Carbon::now()->toDateTimeString());
    }

    public function sda_CL1026($html)
    {
        $indb = array();
        foreach ($html->find("td.ListColumnClass2") as $list) {
            $link = $list->find("a", 0)->href;
            $link = "http://www.sda.gov.cn/WS01" . substr($link, 2);
            $title = $list->find("a", 0)->plaintext;
            $tmp = $list->find("span.listtddate2", 0)->innertext;
            $time = substr($tmp, 1, -2);
            $time = Carbon::createFromTimestamp(strtotime(trim($time)))->format('Y-m-d H:i');
            $source = "食药监局";
            $indb["title"] = trim($title);
            $indb["link"] = $link;
            $indb["time"] = $time;
            $indb["source"] = $source;
            $indb["priority"] = 1;
            $this->save_p($indb);
        }
        $this->parase_log("sda_CL1026-" . Carbon::now()->toDateTimeString());
    }

    /**
     *
     * @param $html
     */
    public function cma($html)
    {
        $indb = array();
        foreach ($html->find("div.pubcon ul li") as $list) {
            $link = $list->find("a", 0)->href;
            $title = $list->find("a", 0)->innertext;
            $time = $list->find("span", 0)->innertext;
            $time = Carbon::createFromTimestamp(strtotime(trim($time)))->format('Y-m-d H:i');
            $source = "中华医学会";
            $indb["title"] = trim($title);
            $indb["link"] = $link;
            $indb["time"] = $time;
            $indb["source"] = $source;
            $this->save_p($indb);
        }
        $this->parase_log("cma-" . Carbon::now()->toDateTimeString());
    }

    public function medscape($html)
    {
        $indb = array();
        foreach ($html->find(".bucketContent .col2 ul li,.bucketContent .col2Feature ul li") as $list) {
            $link = "http://www.medscape.com" . $list->find("a.title", 0)->href;
            $title = $list->find("a.title", 0)->innertext;
            $time = $list->find(".byline", 0)->innertext;
            $tmp = explode(",", $time);
            $time = $tmp[1] . ', ' . $tmp[2];
            $time = Carbon::createFromTimestamp(strtotime(trim($time)))->format('Y-m-d H:i');
            $source = "medscape";
            $indb["title"] = trim($title);
            $indb["link"] = $link;
            $indb["time"] = $time;
            $indb["source"] = $source;
            $this->save_p($indb);
        }
        $this->parase_log("medscape-" . Carbon::now()->toDateTimeString());
    }

    public function ama_assn($html)
    {
        $indb = array();
        foreach ($html->find(".view-frontpage-view .view-content .views-row") as $list) {
            $link = "https://wire.ama-assn.org" . $list->find("h3.field-content a", 0)->href;
            $title = $list->find("h3.field-content a", 0)->innertext;
            $time = $list->find(".views-field-created div.field-content", 0)->innertext;
            $time = Carbon::createFromTimestamp(strtotime(trim($time)))->format('Y-m-d H:i');
            $source = "ama_assn";
            $indb["title"] = trim($title);
            $indb["link"] = $link;
            $indb["time"] = $time;
            $indb["source"] = $source;
            $this->save_p($indb);
        }
        $this->parase_log("ama_assn-" . Carbon::now()->toDateTimeString());
    }

    //新华网
    public function xinhua($html)
    {
        $indb = array();

        foreach ($html->find("#showData0 li.clearfix , #hideData li.clearfix,#hideData1 li.clearfix,#showData4>#hideData3 li.clearfix") as $list) {
            try {
                $link = $list->find("a", 0)->href;
                $title = $list->find("a", 0)->innertext;
                $time = $list->find(".time", 0)->innertext;
                $time = Carbon::createFromTimestamp(strtotime(trim($time)))->format('Y-m-d H:i');
                $source = "新华网";
                $indb["title"] = trim($title);
                $indb["link"] = $link;
                $indb["time"] = $time;
                $indb["source"] = $source;
                $this->save_p($indb);

            } catch (\Exception $e) {
                echo $list;
            }

        }
        $this->parase_log("xinhua-" . Carbon::now()->toDateTimeString());
    }

    public function xinhua_bw($html)
    {
        $indb = array();

        foreach ($html->find("#showData0 li.clearfix") as $list) {
            try {
                $link = $list->find("a", 0)->href;
                $title = $list->find("a", 0)->innertext;
                $time = $list->find(".time", 0)->innertext;
                $time = Carbon::createFromTimestamp(strtotime(trim($time)))->format('Y-m-d H:i');
                $source = "新华网";
                $indb["title"] = trim($title);
                $indb["link"] = $link;
                $indb["time"] = $time;
                $indb["source"] = $source;
                $this->save_p($indb);

            } catch (\Exception $e) {
                echo $list;
            }

        }
        $this->parase_log("xinhua_bw-" . Carbon::now()->toDateTimeString());
    }

    public function xinhua_cy($html)
    {
        $indb = array();

        foreach ($html->find("#showData0 li.clearfix") as $list) {
            try {
                $link = $list->find("a", 0)->href;
                $title = $list->find("a", 0)->innertext;
                $time = $list->find(".time", 0)->innertext;
                $time = Carbon::createFromTimestamp(strtotime(trim($time)))->format('Y-m-d H:i');
                $source = "新华网";
                $indb["title"] = trim($title);
                $indb["link"] = $link;
                $indb["time"] = $time;
                $indb["source"] = $source;
                $this->save_p($indb);

            } catch (\Exception $e) {

            }

        }
        $this->parase_log("xinhua_cy-" . Carbon::now()->toDateTimeString());
    }

    public function xinhua_zy($html)
    {
        $indb = array();

        foreach ($html->find("#showData0 li.clearfix") as $list) {
            try {
                $link = $list->find("a", 0)->href;
                $title = $list->find("a", 0)->innertext;
                $time = $list->find(".time", 0)->innertext;
                $time = Carbon::createFromTimestamp(strtotime(trim($time)))->format('Y-m-d H:i');
                $source = "新华网";
                $indb["title"] = trim($title);
                $indb["link"] = $link;
                $indb["time"] = $time;
                $indb["source"] = $source;
                $this->save_p($indb);

            } catch (\Exception $e) {

            }
        }
        $this->parase_log("xinhua_zy-" . Carbon::now()->toDateTimeString());
    }

    public function xinhua_ys($html)
    {
        $indb = array();

        foreach ($html->find("#showData0 li.clearfix") as $list) {
            try {
                $link = $list->find("a", 0)->href;
                $title = $list->find("a", 0)->innertext;
                $time = $list->find(".time", 0)->innertext;
                $time = Carbon::createFromTimestamp(strtotime(trim($time)))->format('Y-m-d H:i');
                $source = "新华网";
                $indb["title"] = trim($title);
                $indb["link"] = $link;
                $indb["time"] = $time;
                $indb["source"] = $source;
                $this->save_p($indb);

            } catch (\Exception $e) {

            }
        }
        $this->parase_log("xinhua_ys-" . Carbon::now()->toDateTimeString());
    }

    public function xinhua_qn($html)
    {
        $indb = array();
        foreach ($html->find("#showData0 li.clearfix") as $list) {
            try {
                $link = $list->find("a", 0)->href;
                $title = $list->find("a", 0)->innertext;
                $time = $list->find(".time", 0)->innertext;
                $time = Carbon::createFromTimestamp(strtotime(trim($time)))->format('Y-m-d H:i');
                $source = "新华网";
                $indb["title"] = trim($title);
                $indb["link"] = $link;
                $indb["time"] = $time;
                $indb["source"] = $source;
                $this->save_p($indb);
            } catch (\Exception $e) {
                echo $list;
            }
        }
        $this->parase_log("xinhua_qn-" . Carbon::now()->toDateTimeString());
    }

    public function xinhua_ft($html)
    {
        $indb = array();

        foreach ($html->find("#showData0 li.clearfix") as $list) {
            try {
                $link = $list->find("a", 0)->href;
                $title = $list->find("a", 0)->innertext;
                $time = $list->find(".time", 0)->innertext;
                $time = Carbon::createFromTimestamp(strtotime(trim($time)))->format('Y-m-d H:i');
                $source = "新华网";
                $indb["title"] = trim($title);
                $indb["link"] = $link;
                $indb["time"] = $time;
                $indb["source"] = $source;
                $this->save_p($indb);

            } catch (\Exception $e) {
                echo $list;
            }
        }
        $this->parase_log("xinhua_ft-" . Carbon::now()->toDateTimeString());
    }

//人民网
    public function people_bx($html)
    {
        $indb = array();

        foreach ($html->find(".columWrap .newsItems") as $list) {
            try {
                $link = "http://health.people.com.cn" . $list->find("a", 0)->href;
                $title = $list->find("a", 0)->innertext;
                $time = $list->find(".n_time", 0)->innertext;
                $time = preg_replace("/(年|月)/", '-', $time);
                $time = preg_replace("/(日)/", '', $time);
                $source = "人民健康网";
                $indb["title"] = trim($title);
                $indb["link"] = $link;
                $indb["time"] = trim($time);
                $indb["source"] = $source;
//                echo $link.$title.$time."</br>";
                $this->save_p($indb);

            } catch (\Exception $e) {
                echo $list;
            }
        }
        $this->parase_log("people_bx-" . Carbon::now()->toDateTimeString());
    }

    public function people_zc($html)
    {
        $indb = array();

        foreach ($html->find(".columWrap .newsItems") as $list) {
            try {
                $link = "http://health.people.com.cn" . $list->find("a", 0)->href;
                $title = $list->find("a", 0)->innertext;
                $time = $list->find(".n_time", 0)->innertext;
                $time = preg_replace("/(年|月)/", '-', $time);
                $time = preg_replace("/(日)/", '', $time);
                $source = "人民健康网";
                $indb["title"] = trim($title);
                $indb["link"] = $link;
                $indb["time"] = trim($time);
                $indb["source"] = $source;
                echo $link . $title . $time . "</br>";
//                $this->save_p($indb);

            } catch (\Exception $e) {
                echo $list;
            }
        }
        $this->parase_log("people_zc-" . Carbon::now()->toDateTimeString());
    }

    public function gcyuanshi($html)
    {
        $t = $html->find(".ysxx_namelist", 21);
        $s = $html->find(".ysxx_namelist", 22);
        foreach ($t->find(".name_list") as $list) {
            try {
                $link = "http://www.cae.cn" . $list->find("a", 0)->href;
                $name = $list->find("a", 0)->innertext;
                echo $link . "</br>";

            } catch (\Exception $e) {
                echo $list;
            }
        }
        foreach ($s->find(".name_list") as $list) {
            try {
                $link = "http://www.cae.cn" . $list->find("a", 0)->href;
                $name = $list->find("a", 0)->innertext;
                echo $link . "</br>";

            } catch (\Exception $e) {
                echo $list;
            }
        }
        $this->parase_log("gcyuanshi-" . Carbon::now()->toDateTimeString());
    }

    public function kxyuanshi($html)
    {

        foreach ($html->find("#allNameBar dd span") as $list) {
            try {
                $link = $list->find("a", 0)->href;
                $name = $list->find("a", 0)->innertext;
                echo $link . "</br>";

            } catch (\Exception $e) {
                echo $list;
            }
        }
        $this->parase_log("kxyuanshi-" . Carbon::now()->toDateTimeString());
    }

    public function qrjh($html)
    {

        foreach ($html->find(".col-dl") as $list) {
            try {
                $link = "http://www.1000plan.org/wiki/" . $list->find(".h2 a", 0)->href;
                $name = $list->find(".h2 a", 0)->innertext;
                echo $link . $name . "</br>";
                $this->parase_log($link);

            } catch (\Exception $e) {
                echo $list;
            }
        }
        $this->parase_log("qrjh-" . Carbon::now()->toDateTimeString());
    }


    /**
     *
     * @param string $pam
     * @param string $level
     */
    public function parase_log($pam = '', $level = "info")
    {
        $exist = storage_path('/app/exist.txt');
        $policy = storage_path('/app/policy.txt');
        $fellowplus = storage_path('/app/fellow.txt');
        if ($level == "error") {
            $fp = fopen($exist, "a+"); //文件被清空后再写入
        } elseif ($level == "policy") {
            $fp = fopen($policy, "a+");
        } elseif ($level == "fellow") {
            $fp = fopen($fellowplus, "a+");
        }else {
            $fp = fopen($exist, "a+"); //文件被清空后再写入
        }

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

    public function save($dn)
    {

        $daily_s = DailyNews::where('link', '=', $dn["link"])->get();

        if ($daily_s->count() == 0) {
            $daily = new DailyNews();
            $daily->title = $dn["title"];
            $daily->link = $dn["link"];
            $daily->source = $dn["source"];
            $daily->pub_date = $dn["time"];
            $daily->company = [];
            $daily->tags = [];
            $daily->flag = 1;
            $daily->isread = 0;
            $daily->is_pub = 0;
            $daily->priority = isset($dn["priority"]) ? $dn["priority"] : 9;
            $daily->group = 't';
            $status = $daily->save();
            if ($status) {

            } else {
                $this->parase_log($dn["link"]);
            }
        }
    }

    public function save_p($dn)
    {

        $daily_s = DailyNews::where('link', '=', $dn["link"])->get();

        if ($daily_s->count() == 0) {
            $daily = new DailyNews();
            $daily->title = $dn["title"];
            $daily->link = $dn["link"];
            $daily->source = $dn["source"];
            $daily->pub_date = $dn["time"];
            $daily->company = "";
            $daily->tags = [];
            $daily->flag = 2;
            $daily->isread = 0;
            $daily->is_pub = 0;
            $daily->priority = isset($dn["priority"]) ? $dn["priority"] : 9;
            $daily->group = 'policy';
            $status = $daily->save();
            if ($status) {

            } else {
                $this->parase_log($dn["link"]);
            }
        }
    }

    public function save_funds($dn)
    {

        $daily_s = DailyFunds::where('link', '=', $dn["link"])->orWhere('company_df', 'like', '%' . $dn["company"] . '%')->take(1000)->get();

        if ($daily_s->count() == 0) {
            $daily = new DailyFunds();
            $daily->company_df = $dn["company"];
            $daily->link = $dn["link"];
            $daily->source = $dn["source"];
            $daily->pub_date_f = $dn["time"];
            $daily->amount = $dn["amount"];
            $daily->round = $dn["round"];
            $daily->invest = $dn["invest"];
            $daily->flag = 1;
            $daily->isread = 0;
            $daily->is_pub = 0;
            $daily->priority = isset($dn["priority"]) ? $dn["priority"] : 9;
            $status = $daily->save();
            if ($status) {
            } else {
                $this->parase_log($dn["link"]);
            }
        }
    }
}
