<?php
/**
 * Created by PhpStorm.
 * User: wangpan
 * Date: 17/5/17
 * Time: 上午9:59
 */

namespace App\Lib;


class QLogs
{

    public function write_log($pam = '', $level = "search")
    {
        $fp = fopen(storage_path("app/" . $level), "a+");
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

    /**
     * @param $file
     */
    function read_file_array($file_cache)
    {
        $result = array();
        if (file_exists($file_cache) && is_readable($file_cache)) {
            $f = fopen($file_cache, "r");
            while (!feof($f)) {
                $line = fgets($f);
                $result[] = trim($line);
            }
        }
        return $result;
    }

}