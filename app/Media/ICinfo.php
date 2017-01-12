<?php
/**
 * Created by PhpStorm.
 * User: wangpan
 * Date: 16/12/26
 * Time: 下午4:41
 */

namespace App\Media;


class ICinfo
{
    public function builder($url = "", $func = "")
    {

        $client = Client::getInstance();
        $client->getEngine()->addOption('--load-images=false');
        $client->getEngine()->addOption('--ignore-ssl-errors=true');
        $client->getProcedureCompiler()->disableCache();
        $client->isLazy();
        $client->getEngine()->setPath('/usr/local/bin/phantomjs');
        $timeout = 30000;
        $delay = 90;

        /**
         * @see JonnyW\PhantomJs\Http\Request
         **/
        $request = $client->getMessageFactory()->createRequest($url, 'GET');
        $request->addHeader("User-Agent", "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_10_5) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/54.0.2840.98 Safari/537.36");
        $request->setTimeout($timeout);
        $request->setDelay($delay);

        /**
         * @see JonnyW\PhantomJs\Http\Response
         **/
        $response = $client->getMessageFactory()->createResponse();

        // Send the request
        $client->send($request, $response);
        $html = str_get_html($response->getContent());

        if ($response->getStatus() == 200) {
            call_user_func(array($this, $func), $html);
        } elseif ($response->getStatus() == 408 || $response->getStatus() == 301 || $response->getStatus() == 302) {
            call_user_func(array($this, $func), $html);
        } else {
            $this->parase_log($response->getStatus());
        }
    }

    /**
     * http://www.tianyancha.com/company/39676350
     * http://www.tianyancha.com/IcpList/39676350.json 网站备案
     * http://www.tianyancha.com/tm/getTmList.json?id=39676350&pageNum=1&ps=100 商标信息
     * http://www.tianyancha.com/extend/getPatentList.json?companyName=杭州泰格医药科技股份有限公司&pn=1&ps=100 专利
     *
     * @param $html
     */

    public function get_ICinfo($html){
        $baseInfo = $html->find(".nav-main-baseInfo",0);
    }
}