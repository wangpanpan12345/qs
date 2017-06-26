<?php
/**
 * Created by PhpStorm.
 * User: wangpan
 * Date: 16/11/22
 * Time: 下午2:52
 */

namespace App\Http\Controllers;

use App\Companies;
use App\Founders;
use App\Items;
use App\Http\Requests;
use Illuminate\Routing\Controller;
use App\Http\Controllers\BaikeController;

class ItemController extends Controller
{
    public function item($name)
    {
        $c = Companies::complexSearch(array(
            'type' => 'companies',
            'body' => array(
                "query" => array(
                    "match" => array(
                        "name.keyword" => $name
                    )
                )
            ),
        ));
        $p = Founders::complexSearch(array(
            'type' => 'founders',
            'body' => array(
                "query" => array(
                    "match" => array(
                        "name.keyword" => $name
                    )
                )
            ),
        ));
        $i = Items::complexSearch(array(
            'type' => 'items',
            'body' => array(
                "query" => array(
                    "match" => array(
                        "name.keyword" => $name
                    )
                )
            ),
        ));
        $result_company = $c->getHits();
        $result_person = $p->getHits();
        $result_item = $i->getHits();
//        dd($result_item);
        if ($result_company["total"] == 0 && $result_person["total"] == 0 && $result_item["total"] == 0) {
            $baike = new BaikeController();
            $baike->add($name);
        }
        $result = [
            "c" => $result_company,
            "p" => $result_person,
            "i" => $result_item
        ];
        return view('item', $result);

        dd($result_person);

    }
}