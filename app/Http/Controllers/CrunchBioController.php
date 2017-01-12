<?php
/**
 * Created by PhpStorm.
 * User: wangpan
 * Date: 16/11/21
 * Time: 上午10:31
 */

namespace App\Http\Controllers;
ini_set('max_execution_time', 0);
use App\Companies;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Http\Requests;
use Illuminate\Support\Facades\Storage;
use phpDocumentor\Reflection\DocBlock\Tags\Var_;
use Psy\Exception\ErrorException;
use Illuminate\Database\Eloquent\Collection;
use TijsVerkoyen\CssToInlineStyles\Exception;


class CrunchBioController
{
    public $pachong_name = "medical-crunch";

    public function parase_starter()
    {
        $files = scandir("/Users/wangpan/DataScraperWorks/" . $this->pachong_name);

        foreach ($files as $k => $v) {
            if (strpos($v, $this->pachong_name) === false)
                continue;
            if ($this->check_file('/usr/local/laravel/storage/app/parase_log.txt', '/Users/wangpan/DataScraperWorks/' . $this->pachong_name . '/' . $v))
                continue;
            $this->paraser_crunchbase_bio('/Users/wangpan/DataScraperWorks/' . $this->pachong_name . '/' . $v);
        }
    }

    /**
     * 解析抓取到的crunchbase 生物技术类的xml文件,并将其存入MongoDB数据库
     * @param string $file 文件名
     */
    public function paraser_crunchbase_bio($file = "")
    {


//        $file = '/usr/local/laravel/storage/app/crunch-detail_249713795_1990031743副本.xml';

        $doc = new \DOMDocument();
        try {
            $doc->load($file);
        } catch (ErrorException $e) {
            echo 'Message: ' . $e->getMessage();
        }
        $path = $doc->getElementsByTagName("fullpath")->item(0)->nodeValue;
//        dd($path);
        $item = array();
        /**
         * overview
         */
        $bio = $doc->getElementsByTagName("medical")->item(0);
//        dd($bio);
        $items = $bio->getElementsByTagName("item");
        foreach ($items as $k => $v) {

            $name = $v->getElementsByTagName("name")->item(0)->nodeValue;
            $avatar = $v->getElementsByTagName("avatar")->item(0)->nodeValue;

            $tags = $v->getElementsByTagName("tags")->item(0)->nodeValue;
            $tags = explode(":", $tags);
            $tags = $tags[1];;
            $tags = explode(',', $tags);
            $_tags = array();
            foreach ($tags as $K => $V) {
                $_tags[] = trim($V);
            }

            $cpyLink = $v->getElementsByTagName("name-url")->item(0)->nodeValue;
            $cpyLink = trim("https://www.crunchbase.com" . $cpyLink);

            $item["name"] = $name;
            $item["avatar"] = $avatar;
            $item["cpyDetailLink"] = $cpyLink;
            $item["tags"] = $_tags;

//        dd($item);

            $company = Companies::where('cpyDetailLink', '=', $cpyLink)->get();
//        $company = Companies::where('name', '=', 'Pfizer')->get();
//        var_dump($item);
//        dd($company);
//        dd($company->count()==0);
            if ($company->count() == 0) {
                $company = new Companies();
                $company->name = $name;
                $company->avatar = $avatar;
                $company->tags = $_tags;
                $company->cpyDetailLink = $cpyLink;
                $status = $company->save();
                if ($status) {
                    $this->parase_log($cpyLink, "info");
                    echo $file . "&nbsp;&nbsp;&nbsp;&nbsp success in db.</br>";
                } else {
                    $this->parase_log($cpyLink, "error");
                }
//                dd($company);

            } else {
                $company = $company[0];
                $company->tags = $_tags;
                $status = $company->save();
                if($status){
                    echo $file . "&nbsp;&nbsp;&nbsp;&nbsp success in db.</br>";
                }else{
                    echo $file . "&nbsp;&nbsp;&nbsp;&nbsp already exist.</br>";
                }

                $this->parase_log($cpyLink, "error");
            }

        }

    }

    /**
     * 写入日志
     * @param string $pam 日志内容
     * @param string $level 日志级别
     */
    function parase_log($pam = '', $level = "info")
    {
        if ($level == "error") {
            $fp = fopen("/usr/local/laravel/storage/app/exist.txt", "a+"); //文件被清空后再写入
        } else {
            $fp = fopen("/usr/local/laravel/storage/app/bio_list", "a+"); //文件被清空后再写入
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

    /**
     * 比对日志
     * @param $file_cache 日志文件
     * @param $file   待比对内容
     * @return bool   返回值
     */
    function check_file($file_cache, $file)
    {
        if (file_exists($file_cache) && is_readable($file_cache)) {
            $f = fopen($file_cache, "r");
            while (!feof($f)) {
                $line = fgets($f);
                if (preg_replace("/(\s+)/", '', $line) == $file)
                    return true;
            }
            return false;
            fclose($f);
        }
    }

    public function tag_fix()
    {
//        global $tag_array;
//        Companies::distinct('tags')->chunk(200, function ($tags) {
//            $ss = Collection::make($tags)->pluck('tags')->all();
//            foreach ($ss as $k => $v) {
//                foreach ($v as $sk => $sv) {
//                    global $tag_array;
//                    $tag_array[] = $sv;
//
//                }
//
//            }
//
//        });
        Companies::where('tags','HealthCare')->chunk(200, function ($cs) {
            foreach ($cs as $k => $v)
                dd($v);
                foreach ($v->tags as $t => $s) {
//                    if (preg_match("/^[A-Z]([a-z][A-Z]+)/", $s))
                        var_dump($s);
                }

//            dd($cs);
        });
    }


}