<?php
/**
 * Created by PhpStorm.
 * User: wangpan
 * Date: 16/12/15
 * Time: 上午10:23
 */

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\NameCard;

class NameCardController
{

    //服务器调用地址
    protected $apiurl = 'http://www.yunmaiocr.com/SrvXMLAPI';
    //用户名
    protected $user = '1012550a-9b7c-439a-a83c-43aee2d76b29';
    //密码
    protected $pwd = 'oQHpOpyLgaukLpcFqUqVgDuNXmKtOp';
    //头像存放地址
    protected $filepath = "";
    //头像存放名字
    protected $filename = "";

    //身份证
    public function namecard(Request $request)
    {
        $path = $request["path"];
        //echo '<meta charset="utf-8">';
        $paswd = strtoupper(md5($this->pwd));
        $key = $this->getNonceStr();
        $dtime = time();
        $verify = strtoupper(md5('namecard.scan' . $this->user . $key . $dtime . $paswd));
        $p = pathinfo($path);
//        return $p["basename"];
//        $picturedata = $this->getUrlImg($path);
        $s_path = storage_path("namecard");
        $picturedata = $this->getLoclImg($s_path . '/' . $p["basename"]);
        $data['action'] = 'namecard.scan';
        $data['client'] = $this->user;
        $data['system'] = 'geekheal';
        $data['password'] = $paswd;
        $data['key'] = $key;
        $data['time'] = $dtime;
        $data['ocrlang'] = 2;
        $data['verify'] = $verify;
        $data['file'] = $picturedata;
        $data['ext'] = 'jpg';
        $data['json'] = '1';//返回结果是否转成json格式，不传及默认是xml格式，为1时：转换成json格式
        //数组转换成xml 数据

        $xml = $this->ToXml($data);
        $rest = $this->postXmlCurl($xml, $this->apiurl);
        return $rest;
        dd($rest);
        //xml数据 转换成数组
        $arrxml = $this->FromXml($rest);

        if ($this->saveImg($arrxml['data']['item']) != FALSE) {
            echo 'save img success';
        }
        return $arrxml;
    }

    public function add(Request $r)
    {

        $data = [
            "name" => $r["name"],
            "company" => $r["company"],
            "department" => $r["department"],
            "jobtitle" => $r["jobtitle"],
            "tel_main" => preg_replace("/(\s+)/", ' ', $r["tel_main"]),
            "tel_mobile" => preg_replace("/(\s+)/", ' ', $r["tel_mobile"]),
            "tel_inter" => preg_replace("/(\s+)/", ' ', $r["tel_inter"]),
            "fax" => $r["fax"],
            "email" => $r["email"],
            "pager" => $r["pager"],
            "web" => $r["web"],
            "address" => $r["address"],
            "postcode" => $r["postcode"],
            "qq" => $r["qq"],
        ];
        $is_in = NameCard::where('name', '=', $data['name'])->where('company', '=', $data['company'])->get();

        if ($is_in->count()) {

            return ["error" => 6, "o" => $is_in->count()];
        }


        $o = NameCard::create($data);
        if ($o) {
            return ["error" => 0, "o" => $o];
        } else {
            return ["error" => 4, "o" => $o];
        }
    }

    public function name_card_list(){
        $limit = 30;
        $projections = ['*'];
        $namecards = NameCard::paginate($limit, $projections);
        return view('admin.namecard_list', ['namecards' => $namecards]);
    }


    /**
     * 本地图片转换成二进制数据
     * $fileurl-本地地址
     * @param string $fileurl
     * @return string
     */
    function getLoclImg($fileurl)
    {
        $PSize = filesize($fileurl);
        $picturedata = fread(fopen($fileurl, "r"), $PSize);
        return $picturedata;
    }

    /**
     * 远程图片转换成二进制数据
     * $url-图片url地址
     * @param string $url
     * @return string
     */
    function getUrlImg($url)
    {
        $res = $this->httpGet($url);
        return $res;
    }

    /**
     * 将base64字符串保存为图片,失败返回false，成功返回字节数
     * @param string $img64
     * @param string $filename
     * @throws WxPayException
     * @return boolen
     */
    function saveImg($img64, $filename = '/usr/local/laravel/storage/app/demo.jpg')
    {
        if (empty($img64)) {
            echo 'img Base64 data cannot be empty';
            exit;
        }
        $img = base64_decode($img64);
        $res = file_put_contents($filename, $img);//返回的是字节数
        return $res;
    }

    /**
     * 将xml转为array
     * @param string $xml
     * @throws WxPayException
     */
    public function FromXml($xml)
    {
        if (!$xml) {
            throw new WxPayException("xml数据异常！");
        }
        //将XML转为array
        //禁止引用外部xml实体
        libxml_disable_entity_loader(true);
        $values = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
        return $values;
    }

    /**
     * 输出xml字符
     * @throws WxPayException
     **/
    public function ToXml($values)
    {
        if (!is_array($values) || count($values) <= 0) {
            E("数组数据异常！");
        }
        $xml = "<xml>";
        foreach ($values as $key => $val) {
            if (is_numeric($val)) {
                $xml .= "<" . $key . ">" . $val . "</" . $key . ">";
            } else {
                $xml .= "<" . $key . ">" . $val . "</" . $key . ">";
            }
        }
        $xml .= "</xml>";
        return $xml;
    }

    /**
     *
     * 产生随机字符串，不长于32位
     * @param int $length
     * @return 产生的随机字符串
     */
    public static function getNonceStr($length = 32)
    {
        $chars = "abcdefghijklmnopqrstuvwxyz0123456789";
        $str = "";
        for ($i = 0; $i < $length; $i++) {
            $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }
        return $str;
    }

    protected function httpGet($url)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 500);
        // 为保证第三方服务器与微信服务器之间数据传输的安全性，所有微信接口采用https方式调用，必须使用下面2行代码打开ssl安全校验。
        // 如果在部署过程中代码在此处验证失败，请到 http://curl.haxx.se/ca/cacert.pem 下载新的证书判别文件。
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, true);
        curl_setopt($curl, CURLOPT_URL, $url);

        $res = curl_exec($curl);
        curl_close($curl);

        return $res;
    }

    /**
     * 以post方式提交xml到对应的接口url
     *
     * @param string $xml 需要post的xml数据
     * @param string $url url
     * @param bool $useCert 是否需要证书，默认不需要
     * @param int $second url执行超时时间，默认30s
     * @throws WxPayException
     */
    private static function postXmlCurl($xml, $url, $second = 30)
    {
        $ch = curl_init();
        //设置超时
        curl_setopt($ch, CURLOPT_TIMEOUT, $second);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, TRUE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);//严格校验
        //设置header
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        //要求结果为字符串且输出到屏幕上
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        //post提交方式
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
        //运行curl
        $data = curl_exec($ch);
        //返回结果
        if ($data) {
            curl_close($ch);
            return $data;
        } else {
            $error = curl_errno($ch);
            curl_close($ch);
            E("curl出错，错误码:$error");
        }
    }

}

//$aa = new NameCardController();
//$aa->namecard();