<?php
namespace App\Http\Controllers;

use App\Founders;
use App\Media\SphinxXmlpipe;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use SphinxClient;
use App\Companies;

ini_set('max_execution_time', 0);
ini_set('memory_limit', '-1');

/**
 * Excel相关操作的工具类,需要时使用,与项目无关
 * Class ExcelController
 * @package App\Http\Controllers
 */
class ExcelController extends Controller
{
    public function export_invest()
    {


        $o_data = '[
    {
        "provinceId": 7202,
        "hName": "安徽省立医院",
        "hGrade": "三级甲等",
        "hType": ""
    },
    {
        "provinceId": 7202,
        "hName": "安徽医科大学第一附属医院",
        "hGrade": "三级甲等",
        "hType": ""
    },
    {
        "provinceId": 7232,
        "hName": "安康市中心医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7202,
        "hName": "安庆市立医院",
        "hGrade": "三级甲等",
        "hType": ""
    },
    {
        "provinceId": 7226,
        "hName": "安顺市人民医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7210,
        "hName": "安阳市人民医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7210,
        "hName": "安阳市肿瘤医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7190,
        "hName": "鞍山钢铁集团公司总医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7190,
        "hName": "鞍山市中心医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7218,
        "hName": "百色市人民医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7202,
        "hName": "蚌埠市三人民医院",
        "hGrade": "三级甲等",
        "hType": ""
    },
    {
        "provinceId": 7202,
        "hName": "蚌埠医学院第一附属医院",
        "hGrade": "三级甲等",
        "hType": ""
    },
    {
        "provinceId": 7188,
        "hName": "包头市中心医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7232,
        "hName": "宝鸡市中心医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7184,
        "hName": "保定市第二医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7184,
        "hName": "保定市第一中心医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7184,
        "hName": "保定涿州市医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7218,
        "hName": "北海市人民医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7192,
        "hName": "北华大学附属医院",
        "hGrade": "三级甲等",
        "hType": ""
    },
    {
        "provinceId": 7180,
        "hName": "北京博爱医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7180,
        "hName": "北京大学第六医院",
        "hGrade": "三级甲等",
        "hType": " 专科医院 "
    },
    {
        "provinceId": 7180,
        "hName": "北京大学第三医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7180,
        "hName": "北京大学第一医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7180,
        "hName": "北京大学口腔医院",
        "hGrade": "三级甲等",
        "hType": " 专科医院 "
    },
    {
        "provinceId": 7180,
        "hName": "北京大学人民医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7216,
        "hName": "北京大学深圳医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7180,
        "hName": "北京回龙观医院",
        "hGrade": "三级甲等",
        "hType": " 专科医院 "
    },
    {
        "provinceId": 7180,
        "hName": "北京积水潭医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7180,
        "hName": "北京肿瘤医院",
        "hGrade": "三级甲等",
        "hType": " 专科医院 "
    },
    {
        "provinceId": 7190,
        "hName": "本溪钢铁（集团）总医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7190,
        "hName": "本溪市中心医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7226,
        "hName": "毕节市人民医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7208,
        "hName": "滨州医学院附属医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7184,
        "hName": "沧州市人民医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7184,
        "hName": "沧州市中西医结合医院",
        "hGrade": "三级甲等",
        "hType": " 中西医结合医院"
    },
    {
        "provinceId": 7184,
        "hName": "沧州市中心医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7214,
        "hName": "常德市第一人民医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7198,
        "hName": "常州市第二人民医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7198,
        "hName": "常州市第一人民医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7202,
        "hName": "巢湖市第一人民医院",
        "hGrade": "三级甲等",
        "hType": ""
    },
    {
        "provinceId": 7190,
        "hName": "朝阳市中心医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7214,
        "hName": "郴州市第一人民医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7224,
        "hName": "成都大学附属医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7224,
        "hName": "成都市第二人民医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7224,
        "hName": "成都市第三人民医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7224,
        "hName": "成都市中西医结合医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7224,
        "hName": "成都医学院附属医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7224,
        "hName": "成都中医药大学附属医院",
        "hGrade": "三级甲等",
        "hType": ""
    },
    {
        "provinceId": 7184,
        "hName": "承德市中心医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7184,
        "hName": "承德医学院附属医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7202,
        "hName": "池州市人民医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7188,
        "hName": "赤峰市医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7202,
        "hName": "滁州市第一人民医院",
        "hGrade": "三级甲等",
        "hType": ""
    },
    {
        "provinceId": 7224,
        "hName": "川北医学院附属医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7224,
        "hName": "达州市中西医结合医院",
        "hGrade": "三级甲等",
        "hType": ""
    },
    {
        "provinceId": 7224,
        "hName": "达州市中心医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7190,
        "hName": "大连大学附属新华医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7190,
        "hName": "大连大学附属中山医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7190,
        "hName": "大连市第三人民医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7190,
        "hName": "大连市友谊医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7190,
        "hName": "大连市中心医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7190,
        "hName": "大连医科大学附属第二医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7190,
        "hName": "大连医科大学附属第一医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7194,
        "hName": "大庆龙南医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7194,
        "hName": "大庆市第三医院",
        "hGrade": "三级甲等",
        "hType": " 专科医院 "
    },
    {
        "provinceId": 7194,
        "hName": "大庆市人民医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7194,
        "hName": "大庆油田总医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7186,
        "hName": "大同煤矿集团有限责任公司总医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7186,
        "hName": "大同市第三人民医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7186,
        "hName": "大同市第五人民医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7190,
        "hName": "丹东市第一医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7190,
        "hName": "丹东市中心医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7224,
        "hName": "德阳市人民医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7212,
        "hName": "东风汽车公司总医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7216,
        "hName": "东莞市东华医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7216,
        "hName": "东莞市妇幼保健院",
        "hGrade": "三级甲等",
        "hType": " 妇幼保健院"
    },
    {
        "provinceId": 7216,
        "hName": "东莞市厚街医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7216,
        "hName": "东莞市康华医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7216,
        "hName": "东莞市人民医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7216,
        "hName": "东莞市石龙人民医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7216,
        "hName": "东莞市太平人民医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7198,
        "hName": "东南大学附属中大医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7188,
        "hName": "鄂尔多斯市中心医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7212,
        "hName": "鄂州市中心医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7212,
        "hName": "恩施土家族苗族自治州中心医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7216,
        "hName": "佛山市禅城区中心医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7216,
        "hName": "佛山市第二人民医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7216,
        "hName": "佛山市第一人民医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7216,
        "hName": "佛山市妇幼保健院",
        "hGrade": "三级甲等",
        "hType": " 妇幼保健院"
    },
    {
        "provinceId": 7216,
        "hName": "佛山市高明区人民医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7216,
        "hName": "佛山市南海区妇幼保健院",
        "hGrade": "三级甲等",
        "hType": " 妇幼保健院"
    },
    {
        "provinceId": 7216,
        "hName": "佛山市南海区人民医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7216,
        "hName": "佛山市顺德区第一人民医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7216,
        "hName": "佛山市顺德区妇幼保健院",
        "hGrade": "三级甲等",
        "hType": " 妇幼保健院"
    },
    {
        "provinceId": 7204,
        "hName": "福建省福州结核病防治院",
        "hGrade": "三级甲等",
        "hType": ""
    },
    {
        "provinceId": 7204,
        "hName": "福建省福州神经精神病防治院",
        "hGrade": "三级甲等",
        "hType": ""
    },
    {
        "provinceId": 7204,
        "hName": "福建省妇幼保健院",
        "hGrade": "三级甲等",
        "hType": ""
    },
    {
        "provinceId": 7204,
        "hName": "福建省立医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7204,
        "hName": "福建省肿瘤医院",
        "hGrade": "三级甲等",
        "hType": ""
    },
    {
        "provinceId": 7204,
        "hName": "福建医科大学附属第二医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7204,
        "hName": "福建医科大学附属第一医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7204,
        "hName": "福建医科大学附属协和医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7204,
        "hName": "福州市传染病医院",
        "hGrade": "三级甲等",
        "hType": ""
    },
    {
        "provinceId": 7204,
        "hName": "福州市第二医院",
        "hGrade": "三级甲等",
        "hType": ""
    },
    {
        "provinceId": 7204,
        "hName": "福州市第一医院",
        "hGrade": "三级甲等",
        "hType": ""
    },
    {
        "provinceId": 7190,
        "hName": "抚顺矿务局总医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7190,
        "hName": "抚顺市中心医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7206,
        "hName": "抚州市第一人民医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7190,
        "hName": "阜新矿业集团有限责任公司总医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7190,
        "hName": "阜新市中心医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7202,
        "hName": "阜阳市人民医院",
        "hGrade": "三级甲等",
        "hType": ""
    },
    {
        "provinceId": 7196,
        "hName": "复旦大学附属儿科医院",
        "hGrade": "三级甲等",
        "hType": " 专科医院 "
    },
    {
        "provinceId": 7196,
        "hName": "复旦大学附属妇产科医院",
        "hGrade": "三级甲等",
        "hType": " 专科医院 "
    },
    {
        "provinceId": 7196,
        "hName": "复旦大学附属华山医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7196,
        "hName": "复旦大学附属眼耳鼻喉科医院",
        "hGrade": "三级甲等",
        "hType": " 专科医院 "
    },
    {
        "provinceId": 7196,
        "hName": "复旦大学附属中山医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7196,
        "hName": "复旦大学附属肿瘤医院",
        "hGrade": "三级甲等",
        "hType": " 专科医院 "
    },
    {
        "provinceId": 7234,
        "hName": "甘肃省康复中心医院",
        "hGrade": "三级甲等",
        "hType": ""
    },
    {
        "provinceId": 7234,
        "hName": "甘肃省肿瘤医院",
        "hGrade": "三级甲等",
        "hType": ""
    },
    {
        "provinceId": 7206,
        "hName": "赣南医学院第一附属医院",
        "hGrade": "三级甲等",
        "hType": ""
    },
    {
        "provinceId": 7206,
        "hName": "赣州市立医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7206,
        "hName": "赣州市人民医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7216,
        "hName": "广东省第二人民医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7216,
        "hName": "广东省妇幼保健院",
        "hGrade": "三级甲等",
        "hType": " 妇幼保健院"
    },
    {
        "provinceId": 7216,
        "hName": "广东省农垦中心医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7216,
        "hName": "广东省人民医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7216,
        "hName": "广东药学院附属第一医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7216,
        "hName": "广东医学院附属医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7218,
        "hName": "广西医科大学第一附属医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7218,
        "hName": "广西医科大学附属口腔医院",
        "hGrade": "三级甲等",
        "hType": " 专科医院 "
    },
    {
        "provinceId": 7218,
        "hName": "广西壮族自治区工人医院",
        "hGrade": "三级甲等",
        "hType": " 专科医院 "
    },
    {
        "provinceId": 7218,
        "hName": "广西壮族自治区民族医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7218,
        "hName": "广西壮族自治区南溪山医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7218,
        "hName": "广西壮族自治区人民医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7218,
        "hName": "广西壮族自治区肿瘤医院",
        "hGrade": "三级甲等",
        "hType": " 专科医院 "
    },
    {
        "provinceId": 7224,
        "hName": "广元市精神卫生中心",
        "hGrade": "三级甲等",
        "hType": " 专科医院 "
    },
    {
        "provinceId": 7224,
        "hName": "广元市中心医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7216,
        "hName": "广州市第一人民医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7216,
        "hName": "广州市番禺区中心医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7216,
        "hName": "广州市妇女儿童医疗中心",
        "hGrade": "三级甲等",
        "hType": " 专科医院 "
    },
    {
        "provinceId": 7216,
        "hName": "广州市红十字会医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7216,
        "hName": "广州市精神病医院",
        "hGrade": "三级甲等",
        "hType": " 专科医院 "
    },
    {
        "provinceId": 7216,
        "hName": "广州医科大学附属第二医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7216,
        "hName": "广州医科大学附属第三医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7216,
        "hName": "广州医科大学附属第一医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7218,
        "hName": "贵港市人民医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7226,
        "hName": "贵阳市第二人民医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7226,
        "hName": "贵阳市第四人民医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7226,
        "hName": "贵阳市第一人民医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7226,
        "hName": "贵阳市妇幼保健院",
        "hGrade": "三级甲等",
        "hType": " 妇幼保健院"
    },
    {
        "provinceId": 7226,
        "hName": "贵阳市口腔医院",
        "hGrade": "三级甲等",
        "hType": " 专科医院 "
    },
    {
        "provinceId": 7226,
        "hName": "贵阳医学院第二附属医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7226,
        "hName": "贵阳医学院第三附属医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7226,
        "hName": "贵阳医学院附属医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7226,
        "hName": "贵阳中医学院第二附属医院",
        "hGrade": "三级甲等",
        "hType": " 中西医结合医院"
    },
    {
        "provinceId": 7226,
        "hName": "贵州省骨科医院",
        "hGrade": "三级甲等",
        "hType": " 专科医院 "
    },
    {
        "provinceId": 7226,
        "hName": "贵州省人民医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7226,
        "hName": "贵州省肿瘤医院",
        "hGrade": "三级甲等",
        "hType": " 专科医院 "
    },
    {
        "provinceId": 7218,
        "hName": "桂林市第二人民医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7218,
        "hName": "桂林市人民医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7218,
        "hName": "桂林医学院附属医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7194,
        "hName": "哈尔滨二四二医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7194,
        "hName": "哈尔滨市第四医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7194,
        "hName": "哈尔滨市第一医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7194,
        "hName": "哈尔滨医科大学附属第二医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7194,
        "hName": "哈尔滨医科大学附属第三医院",
        "hGrade": "三级甲等",
        "hType": " 专科医院 "
    },
    {
        "provinceId": 7194,
        "hName": "哈尔滨医科大学附属第四医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7194,
        "hName": "哈尔滨医科大学附属第一医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7220,
        "hName": "海口市人民医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7220,
        "hName": "海南省农垦三亚医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7220,
        "hName": "海南省农垦总医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7220,
        "hName": "海南省人民医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7220,
        "hName": "海南医学院附属医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7184,
        "hName": "邯郸市第一医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7184,
        "hName": "邯郸市中心医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7232,
        "hName": "汉中市中心医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7200,
        "hName": "杭州市第六人民医院",
        "hGrade": "三级甲等",
        "hType": " 专科医院 "
    },
    {
        "provinceId": 7200,
        "hName": "杭州市第七人民医院",
        "hGrade": "三级甲等",
        "hType": " 专科医院 "
    },
    {
        "provinceId": 7200,
        "hName": "杭州市第一人民医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7200,
        "hName": "杭州市红十字会医院",
        "hGrade": "三级甲等",
        "hType": " 中西医结合医院"
    },
    {
        "provinceId": 7202,
        "hName": "合肥市第二人民医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7202,
        "hName": "合肥市第一人民医院",
        "hGrade": "三级甲等",
        "hType": ""
    },
    {
        "provinceId": 7184,
        "hName": "河北北方学院附属第一医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7184,
        "hName": "河北大学附属医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7184,
        "hName": "河北工程大学附属医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7184,
        "hName": "河北联合大学附属医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7184,
        "hName": "河北省儿童医院",
        "hGrade": "三级甲等",
        "hType": " 专科医院 "
    },
    {
        "provinceId": 7184,
        "hName": "河北省人民医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7184,
        "hName": "河北医科大学第二医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7184,
        "hName": "河北医科大学第三医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7184,
        "hName": "河北医科大学第四医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7184,
        "hName": "河北医科大学第一医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7218,
        "hName": "河池市人民医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7210,
        "hName": "河南大学淮河医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7210,
        "hName": "河南科技大学第一附属医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7210,
        "hName": "河南省精神病医院",
        "hGrade": "三级甲等",
        "hType": " 专科医院 "
    },
    {
        "provinceId": 7210,
        "hName": "河南省人民医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7210,
        "hName": "河南省肿瘤医院",
        "hGrade": "三级甲等",
        "hType": " 专科医院 "
    },
    {
        "provinceId": 7194,
        "hName": "黑龙江省农垦红兴隆管理局中心医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7194,
        "hName": "黑龙江省农垦建三江管理局中心医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7194,
        "hName": "黑龙江省农垦总局总医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7194,
        "hName": "黑龙江省森工总医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7194,
        "hName": "黑龙江省医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7184,
        "hName": "衡水市人民医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7214,
        "hName": "衡阳市中心医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7190,
        "hName": "葫芦岛市中心医院",
        "hGrade": "三级甲等",
        "hType": ""
    },
    {
        "provinceId": 7212,
        "hName": "湖北江汉油田总医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7212,
        "hName": "湖北省妇幼保健医院",
        "hGrade": "三级甲等",
        "hType": " 专科医院 "
    },
    {
        "provinceId": 7212,
        "hName": "湖北省中山医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7212,
        "hName": "湖北省中医医院",
        "hGrade": "三级甲等",
        "hType": ""
    },
    {
        "provinceId": 7212,
        "hName": "湖北省肿瘤医院",
        "hGrade": "三级甲等",
        "hType": " 专科医院 "
    },
    {
        "provinceId": 7214,
        "hName": "湖南省儿童医院",
        "hGrade": "三级甲等",
        "hType": " 专科医院 "
    },
    {
        "provinceId": 7214,
        "hName": "湖南省人民医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7214,
        "hName": "湖南省肿瘤医院",
        "hGrade": "三级甲等",
        "hType": " 专科医院 "
    },
    {
        "provinceId": 7200,
        "hName": "湖州市第三人民医院",
        "hGrade": "三级甲等",
        "hType": " 专科医院 "
    },
    {
        "provinceId": 7200,
        "hName": "湖州市中心医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7184,
        "hName": "华北石油管理局总医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7196,
        "hName": "华东医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7212,
        "hName": "华中科技大学同济医学院附属梨园医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7212,
        "hName": "华中科技大学同济医学院附属同济医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7212,
        "hName": "华中科技大学同济医学院附属协和医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7214,
        "hName": "怀化市第一人民医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7198,
        "hName": "淮安市第一人民医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7198,
        "hName": "淮安市妇幼保健院",
        "hGrade": "三级甲等",
        "hType": " 专科医院 "
    },
    {
        "provinceId": 7202,
        "hName": "淮北矿工总医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7202,
        "hName": "淮北市人民医院",
        "hGrade": "三级甲等",
        "hType": ""
    },
    {
        "provinceId": 7212,
        "hName": "黄冈市中心医院",
        "hGrade": "三级甲等",
        "hType": " 专科医院 "
    },
    {
        "provinceId": 7202,
        "hName": "黄山市人民医院",
        "hGrade": "三级甲等",
        "hType": ""
    },
    {
        "provinceId": 7212,
        "hName": "黄石市中心医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7216,
        "hName": "惠州市中心人民医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7194,
        "hName": "鸡西矿业集团总医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7194,
        "hName": "鸡西市人民医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7206,
        "hName": "吉安市第三人民医院",
        "hGrade": "三级甲等",
        "hType": " 专科医院 "
    },
    {
        "provinceId": 7206,
        "hName": "吉安市中心人民医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7192,
        "hName": "吉化集团公司总医院",
        "hGrade": "三级甲等",
        "hType": ""
    },
    {
        "provinceId": 7192,
        "hName": "吉林大学第二医院",
        "hGrade": "三级甲等",
        "hType": ""
    },
    {
        "provinceId": 7192,
        "hName": "吉林大学第一医院",
        "hGrade": "三级甲等",
        "hType": ""
    },
    {
        "provinceId": 7192,
        "hName": "吉林大学口腔医院",
        "hGrade": "三级甲等",
        "hType": ""
    },
    {
        "provinceId": 7192,
        "hName": "吉林大学中日联谊医院",
        "hGrade": "三级甲等",
        "hType": ""
    },
    {
        "provinceId": 7192,
        "hName": "吉林省肝胆病医院",
        "hGrade": "三级甲等",
        "hType": ""
    },
    {
        "provinceId": 7192,
        "hName": "吉林省精神神经病医院",
        "hGrade": "三级甲等",
        "hType": ""
    },
    {
        "provinceId": 7192,
        "hName": "吉林省人民医院",
        "hGrade": "三级甲等",
        "hType": ""
    },
    {
        "provinceId": 7192,
        "hName": "吉林省肿瘤医院",
        "hGrade": "三级甲等",
        "hType": ""
    },
    {
        "provinceId": 7192,
        "hName": "吉林市人民医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7192,
        "hName": "吉林市中心医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7192,
        "hName": "吉林医药学院附属医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7192,
        "hName": "吉林油田总医院",
        "hGrade": "三级甲等",
        "hType": ""
    },
    {
        "provinceId": 7208,
        "hName": "济南市第四人民医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7208,
        "hName": "济南市中心医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7208,
        "hName": "济宁市第一人民医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7208,
        "hName": "济宁医学院附属医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7216,
        "hName": "暨南大学附属第一医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7184,
        "hName": "冀中能源峰峰集团有限公司总医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7194,
        "hName": "佳木斯大学附属第三医院",
        "hGrade": "三级甲等",
        "hType": " 专科医院 "
    },
    {
        "provinceId": 7194,
        "hName": "佳木斯大学附属第一医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7194,
        "hName": "佳木斯市中心医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7216,
        "hName": "江门市人民医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7216,
        "hName": "江门市中心医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7198,
        "hName": "江苏大学附属医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7198,
        "hName": "江苏省人民医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7198,
        "hName": "江苏省苏北人民医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7198,
        "hName": "江苏省肿瘤医院",
        "hGrade": "三级甲等",
        "hType": " 专科医院 "
    },
    {
        "provinceId": 7206,
        "hName": "江西省儿童医院",
        "hGrade": "三级甲等",
        "hType": " 专科医院 "
    },
    {
        "provinceId": 7206,
        "hName": "江西省精神病院",
        "hGrade": "三级甲等",
        "hType": " 专科医院 "
    },
    {
        "provinceId": 7206,
        "hName": "江西省皮肤病专科医院",
        "hGrade": "三级甲等",
        "hType": " 专科医院 "
    },
    {
        "provinceId": 7206,
        "hName": "江西省人民医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7206,
        "hName": "江西省胸科医院",
        "hGrade": "三级甲等",
        "hType": " 专科医院 "
    },
    {
        "provinceId": 7206,
        "hName": "江西省肿瘤医院",
        "hGrade": "三级甲等",
        "hType": " 专科医院 "
    },
    {
        "provinceId": 7210,
        "hName": "焦作市第二人民医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7210,
        "hName": "焦作市人民医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7216,
        "hName": "揭阳市人民医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7200,
        "hName": "金华市中心医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7190,
        "hName": "锦州市中心医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7186,
        "hName": "晋中市第一人民医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7212,
        "hName": "荆门市第一人民医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7212,
        "hName": "荆州市第一人民医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7212,
        "hName": "荆州市中心医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7206,
        "hName": "井冈山大学附属医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7206,
        "hName": "景德镇市第二人民医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7206,
        "hName": "景德镇市第三人民医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7206,
        "hName": "景德镇市第一人民医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7206,
        "hName": "九江市第三人民医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7206,
        "hName": "九江市第五人民医院",
        "hGrade": "三级甲等",
        "hType": " 专科医院 "
    },
    {
        "provinceId": 7206,
        "hName": "九江市第一人民医院",
        "hGrade": "三级甲等",
        "hType": ""
    },
    {
        "provinceId": 7206,
        "hName": "九江学院附属医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7234,
        "hName": "酒泉市人民医院",
        "hGrade": "三级甲等",
        "hType": ""
    },
    {
        "provinceId": 7210,
        "hName": "开封市第一人民医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7184,
        "hName": "开滦总医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7216,
        "hName": "开平市中心医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7240,
        "hName": "克拉玛依市中心医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7228,
        "hName": "昆明医科大学第二附属医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7228,
        "hName": "昆明医科大学第一附属医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7234,
        "hName": "兰州大学第二医院",
        "hGrade": "三级甲等",
        "hType": ""
    },
    {
        "provinceId": 7234,
        "hName": "兰州大学第一医院",
        "hGrade": "三级甲等",
        "hType": ""
    },
    {
        "provinceId": 7234,
        "hName": "兰州市第一人民医院",
        "hGrade": "三级甲等",
        "hType": ""
    },
    {
        "provinceId": 7184,
        "hName": "廊坊市人民医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7224,
        "hName": "乐山市人民医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7200,
        "hName": "丽水市中心医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7198,
        "hName": "连云港市第一人民医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7198,
        "hName": "连云港市妇幼保健院",
        "hGrade": "三级甲等",
        "hType": " 专科医院 "
    },
    {
        "provinceId": 7224,
        "hName": "凉山州第一人民医院",
        "hGrade": "三级甲等",
        "hType": ""
    },
    {
        "provinceId": 7190,
        "hName": "辽宁省金秋医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7190,
        "hName": "辽宁省人民医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7190,
        "hName": "辽宁省肿瘤医院",
        "hGrade": "三级甲等",
        "hType": " 专科医院 "
    },
    {
        "provinceId": 7190,
        "hName": "辽宁医学院附属第一医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7190,
        "hName": "辽阳市中心医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7208,
        "hName": "聊城市人民医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7186,
        "hName": "临汾市第四人民医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7186,
        "hName": "临汾市人民医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7208,
        "hName": "临沂市人民医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7218,
        "hName": "柳州市工人医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7218,
        "hName": "柳州市柳铁中心医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7218,
        "hName": "柳州市人民医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7218,
        "hName": "柳州医学高等专科学校第一附属医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7202,
        "hName": "六安市人民医院",
        "hGrade": "三级甲等",
        "hType": ""
    },
    {
        "provinceId": 7226,
        "hName": "六盘水市人民医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7204,
        "hName": "龙岩市第一医院",
        "hGrade": "三级甲等",
        "hType": ""
    },
    {
        "provinceId": 7214,
        "hName": "娄底市中心医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7224,
        "hName": "泸州医学院附属医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7216,
        "hName": "罗定市人民医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7210,
        "hName": "洛阳市中心医院",
        "hGrade": "三级甲等",
        "hType": ""
    },
    {
        "provinceId": 7210,
        "hName": "漯河市中心医院",
        "hGrade": "三级甲等",
        "hType": ""
    },
    {
        "provinceId": 7186,
        "hName": "吕梁市人民医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7202,
        "hName": "马鞍山市人民医院",
        "hGrade": "三级甲等",
        "hType": ""
    },
    {
        "provinceId": 7202,
        "hName": "马鞍山市中心医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7216,
        "hName": "茂名市人民医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7216,
        "hName": "梅州市人民医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7224,
        "hName": "绵阳市第三人民医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7224,
        "hName": "绵阳市中心医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7194,
        "hName": "牡丹江市妇女儿童医院",
        "hGrade": "三级甲等",
        "hType": " 专科医院 "
    },
    {
        "provinceId": 7194,
        "hName": "牡丹江市康安医院",
        "hGrade": "三级甲等",
        "hType": " 专科医院 "
    },
    {
        "provinceId": 7194,
        "hName": "牡丹江医学院第二附属医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7194,
        "hName": "牡丹江医学院红旗医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7206,
        "hName": "南昌大学第二附属医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7206,
        "hName": "南昌大学第四附属医院",
        "hGrade": "三级甲等",
        "hType": ""
    },
    {
        "provinceId": 7206,
        "hName": "南昌大学第一附属医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7206,
        "hName": "南昌大学附属口腔医院",
        "hGrade": "三级甲等",
        "hType": " 专科医院 "
    },
    {
        "provinceId": 7206,
        "hName": "南昌市传染病医院",
        "hGrade": "三级甲等",
        "hType": " 专科医院 "
    },
    {
        "provinceId": 7206,
        "hName": "南昌市第三医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7206,
        "hName": "南昌市第一医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7224,
        "hName": "南充市中心医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7216,
        "hName": "南方医科大学南方医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7216,
        "hName": "南方医科大学珠江医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7214,
        "hName": "南华大学附属第二医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7214,
        "hName": "南华大学附属第一医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7214,
        "hName": "南华大学附属南华医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7198,
        "hName": "南京脑科医院",
        "hGrade": "三级甲等",
        "hType": " 专科医院 "
    },
    {
        "provinceId": 7198,
        "hName": "南京市第二医院",
        "hGrade": "三级甲等",
        "hType": " 专科医院 "
    },
    {
        "provinceId": 7198,
        "hName": "南京市第一医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7198,
        "hName": "南京市儿童医院",
        "hGrade": "三级甲等",
        "hType": " 专科医院 "
    },
    {
        "provinceId": 7198,
        "hName": "南京市妇幼保健院",
        "hGrade": "三级甲等",
        "hType": " 专科医院 "
    },
    {
        "provinceId": 7198,
        "hName": "南京市鼓楼医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7198,
        "hName": "南京市口腔医院",
        "hGrade": "三级甲等",
        "hType": " 专科医院 "
    },
    {
        "provinceId": 7198,
        "hName": "南京医科大学第二附属医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7198,
        "hName": "南京医科大学附属口腔医院",
        "hGrade": "三级甲等",
        "hType": " 专科医院 "
    },
    {
        "provinceId": 7218,
        "hName": "南宁市第二人民医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7218,
        "hName": "南宁市第一人民医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7204,
        "hName": "南平市第一医院",
        "hGrade": "三级甲等",
        "hType": " 乡镇卫生院"
    },
    {
        "provinceId": 7198,
        "hName": "南通大学附属医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7198,
        "hName": "南通市第三人民医院",
        "hGrade": "三级甲等",
        "hType": " 专科医院 "
    },
    {
        "provinceId": 7198,
        "hName": "南通市第一人民医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7198,
        "hName": "南通市肿瘤医院",
        "hGrade": "三级甲等",
        "hType": " 专科医院 "
    },
    {
        "provinceId": 7210,
        "hName": "南阳市中心医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7224,
        "hName": "内江市第一人民医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7224,
        "hName": "内江市中医医院",
        "hGrade": "三级甲等",
        "hType": ""
    },
    {
        "provinceId": 7188,
        "hName": "内蒙古包钢医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7188,
        "hName": "内蒙古科技大学包头医学院第一附属医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7188,
        "hName": "内蒙古林业总医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7188,
        "hName": "内蒙古民族大学附属医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7188,
        "hName": "内蒙古医学院第二附属医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7188,
        "hName": "内蒙古医学院附属人民医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7188,
        "hName": "内蒙古医学院附属医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7188,
        "hName": "内蒙古自治区妇幼保健院(内蒙古自治区妇女儿童医院)",
        "hGrade": "三级甲等",
        "hType": " 专科医院 "
    },
    {
        "provinceId": 7188,
        "hName": "内蒙古自治区国际蒙医医院",
        "hGrade": "三级甲等",
        "hType": " 民族医院 "
    },
    {
        "provinceId": 7188,
        "hName": "内蒙古自治区人民医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7200,
        "hName": "宁波市第一医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7200,
        "hName": "宁波市妇女儿童医院",
        "hGrade": "三级甲等",
        "hType": " 专科医院 "
    },
    {
        "provinceId": 7200,
        "hName": "宁波市医疗中心李惠利医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7204,
        "hName": "宁德市闽东医院",
        "hGrade": "三级甲等",
        "hType": ""
    },
    {
        "provinceId": 7238,
        "hName": "宁夏回族自治区人民医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7238,
        "hName": "宁夏医科大学总医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7224,
        "hName": "攀枝花第三人民医院",
        "hGrade": "三级甲等",
        "hType": " 专科医院 "
    },
    {
        "provinceId": 7224,
        "hName": "攀枝花钢铁有限责任公司职工总医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7224,
        "hName": "攀枝花市中西医结合医院",
        "hGrade": "三级甲等",
        "hType": ""
    },
    {
        "provinceId": 7224,
        "hName": "攀枝花市中心医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7190,
        "hName": "盘锦辽河油田总医院",
        "hGrade": "三级甲等",
        "hType": ""
    },
    {
        "provinceId": 7190,
        "hName": "盘锦市第二人民医院",
        "hGrade": "三级甲等",
        "hType": ""
    },
    {
        "provinceId": 7210,
        "hName": "平顶山市第一人民医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7234,
        "hName": "平凉市人民医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7206,
        "hName": "萍乡市人民医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7204,
        "hName": "莆田市第一医院",
        "hGrade": "三级甲等",
        "hType": ""
    },
    {
        "provinceId": 7204,
        "hName": "莆田学院附属医院",
        "hGrade": "三级甲等",
        "hType": ""
    },
    {
        "provinceId": 7210,
        "hName": "濮阳市安阳地区医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7210,
        "hName": "濮阳市人民医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7210,
        "hName": "濮阳市油田总医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7216,
        "hName": "普宁市人民医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7194,
        "hName": "七台河矿业精煤(集团)有限责任公司总医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7194,
        "hName": "七台河市人民医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7194,
        "hName": "齐齐哈尔市第一医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7194,
        "hName": "齐齐哈尔医学院第二附属医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7194,
        "hName": "齐齐哈尔医学院第一附属医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7194,
        "hName": "齐齐哈尔医学院附属第三医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7226,
        "hName": "黔东南苗族侗族自治州人民医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7226,
        "hName": "黔南布依族苗族自治州人民医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7226,
        "hName": "黔西南布依族苗族自治州人民医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7218,
        "hName": "钦州市第二人民医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7218,
        "hName": "钦州市第一人民医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7184,
        "hName": "秦皇岛市第一医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7208,
        "hName": "青岛大学医学院附属医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7208,
        "hName": "青岛市市立医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7208,
        "hName": "青岛市中心医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7236,
        "hName": "青海大学附属医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7236,
        "hName": "青海红十字医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7236,
        "hName": "青海省藏医院",
        "hGrade": "三级甲等",
        "hType": " 民族医院 "
    },
    {
        "provinceId": 7236,
        "hName": "青海省第三人民医院",
        "hGrade": "三级甲等",
        "hType": ""
    },
    {
        "provinceId": 7236,
        "hName": "青海省第四人民医院",
        "hGrade": "三级甲等",
        "hType": " 专科医院 "
    },
    {
        "provinceId": 7236,
        "hName": "青海省妇女儿童医院",
        "hGrade": "三级甲等",
        "hType": " 专科医院 "
    },
    {
        "provinceId": 7236,
        "hName": "青海省人民医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7236,
        "hName": "青海省心脑血管病专科医院",
        "hGrade": "三级甲等",
        "hType": " 专科医院 "
    },
    {
        "provinceId": 7216,
        "hName": "清远市人民医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7234,
        "hName": "庆阳市人民医院",
        "hGrade": "三级甲等",
        "hType": ""
    },
    {
        "provinceId": 7204,
        "hName": "泉州市第一医院",
        "hGrade": "三级甲等",
        "hType": ""
    },
    {
        "provinceId": 7232,
        "hName": "三二○一医院",
        "hGrade": "三级甲等",
        "hType": ""
    },
    {
        "provinceId": 7204,
        "hName": "三明市第一医院",
        "hGrade": "三级甲等",
        "hType": ""
    },
    {
        "provinceId": 7204,
        "hName": "厦门大学附属第一医院",
        "hGrade": "三级甲等",
        "hType": ""
    },
    {
        "provinceId": 7204,
        "hName": "厦门大学附属厦门眼科中心（厦门眼科中心）",
        "hGrade": "三级甲等",
        "hType": ""
    },
    {
        "provinceId": 7204,
        "hName": "厦门大学附属中山医院",
        "hGrade": "三级甲等",
        "hType": ""
    },
    {
        "provinceId": 7204,
        "hName": "厦门市妇幼保健院",
        "hGrade": "三级甲等",
        "hType": ""
    },
    {
        "provinceId": 7204,
        "hName": "厦门市仙岳医院",
        "hGrade": "三级甲等",
        "hType": " 专科医院 "
    },
    {
        "provinceId": 7208,
        "hName": "山东大学第二医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7208,
        "hName": "山东大学齐鲁医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7208,
        "hName": "山东省立医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7208,
        "hName": "山东省千佛山医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7208,
        "hName": "山东省肿瘤医院",
        "hGrade": "三级甲等",
        "hType": " 专科医院 "
    },
    {
        "provinceId": 7186,
        "hName": "山西晋城无烟煤矿业集团有限责任公司总医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7186,
        "hName": "山西潞安矿业(集团)有限责任公司总医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7186,
        "hName": "山西省儿童医院",
        "hGrade": "三级甲等",
        "hType": " 专科医院 "
    },
    {
        "provinceId": 7186,
        "hName": "山西省汾阳医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7186,
        "hName": "山西省晋城市人民医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7186,
        "hName": "山西省人民医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7186,
        "hName": "山西省荣军精神康宁医院",
        "hGrade": "三级甲等",
        "hType": " 专科医院 "
    },
    {
        "provinceId": 7186,
        "hName": "山西省太原精神病医院",
        "hGrade": "三级甲等",
        "hType": " 专科医院 "
    },
    {
        "provinceId": 7186,
        "hName": "山西省心血管病医院",
        "hGrade": "三级甲等",
        "hType": " 专科医院 "
    },
    {
        "provinceId": 7186,
        "hName": "山西省眼科医院",
        "hGrade": "三级甲等",
        "hType": " 专科医院 "
    },
    {
        "provinceId": 7186,
        "hName": "山西省运城市中心医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7186,
        "hName": "山西省长治市人民医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7186,
        "hName": "山西省职业病医院",
        "hGrade": "三级甲等",
        "hType": " 专科医院 "
    },
    {
        "provinceId": 7186,
        "hName": "山西省肿瘤医院",
        "hGrade": "三级甲等",
        "hType": " 专科医院 "
    },
    {
        "provinceId": 7186,
        "hName": "山西医科大学第二医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7186,
        "hName": "山西医科大学第一医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7232,
        "hName": "陕西省结核病防治院",
        "hGrade": "三级甲等",
        "hType": ""
    },
    {
        "provinceId": 7232,
        "hName": "陕西省人民医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7232,
        "hName": "陕西省肿瘤医院",
        "hGrade": "三级甲等",
        "hType": " 专科医院 "
    },
    {
        "provinceId": 7216,
        "hName": "汕头大学医学院第一附属医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7216,
        "hName": "汕头市中心医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7196,
        "hName": "上海交通大学医学院附属第九人民医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7196,
        "hName": "上海交通大学医学院附属仁济医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7196,
        "hName": "上海交通大学医学院附属瑞金医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7196,
        "hName": "上海交通大学医学院附属上海儿童医学中心",
        "hGrade": "三级甲等",
        "hType": " 专科医院 "
    },
    {
        "provinceId": 7196,
        "hName": "上海交通大学医学院附属新华医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7196,
        "hName": "上海市第六人民医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7196,
        "hName": "上海市第十人民医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7196,
        "hName": "上海市第一妇婴保健院",
        "hGrade": "三级甲等",
        "hType": " 专科医院 "
    },
    {
        "provinceId": 7196,
        "hName": "上海市第一人民医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7196,
        "hName": "上海市东方医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7196,
        "hName": "上海市儿童医院",
        "hGrade": "三级甲等",
        "hType": " 专科医院 "
    },
    {
        "provinceId": 7196,
        "hName": "上海市肺科医院",
        "hGrade": "三级甲等",
        "hType": " 专科医院 "
    },
    {
        "provinceId": 7196,
        "hName": "上海市公共卫生临床中心",
        "hGrade": "三级甲等",
        "hType": " 专科医院 "
    },
    {
        "provinceId": 7196,
        "hName": "上海市精神卫生中心",
        "hGrade": "三级甲等",
        "hType": " 专科医院 "
    },
    {
        "provinceId": 7196,
        "hName": "上海市同济医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7196,
        "hName": "上海市胸科医院",
        "hGrade": "三级甲等",
        "hType": " 专科医院 "
    },
    {
        "provinceId": 7206,
        "hName": "上饶市第五人民医院",
        "hGrade": "三级甲等",
        "hType": " 专科医院 "
    },
    {
        "provinceId": 7206,
        "hName": "上饶市人民医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7216,
        "hName": "韶关市第一人民医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7214,
        "hName": "邵阳市中心医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7200,
        "hName": "绍兴市妇幼保健院",
        "hGrade": "三级甲等",
        "hType": " 专科医院 "
    },
    {
        "provinceId": 7200,
        "hName": "绍兴市人民医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7216,
        "hName": "深圳市宝安区人民医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7216,
        "hName": "深圳市第二人民医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7216,
        "hName": "深圳市龙岗中心医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7216,
        "hName": "深圳市南山区人民医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7216,
        "hName": "深圳市人民医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7190,
        "hName": "沈阳市第五人民医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7190,
        "hName": "沈阳医学院奉天医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7190,
        "hName": "沈阳医学院沈洲医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7224,
        "hName": "省骨科医院",
        "hGrade": "三级甲等",
        "hType": ""
    },
    {
        "provinceId": 7234,
        "hName": "省人民医院",
        "hGrade": "三级甲等",
        "hType": ""
    },
    {
        "provinceId": 7208,
        "hName": "胜利油田中心医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7212,
        "hName": "十堰市人民医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7212,
        "hName": "十堰市太和医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7184,
        "hName": "石家庄市第四医院",
        "hGrade": "三级甲等",
        "hType": " 专科医院 "
    },
    {
        "provinceId": 7184,
        "hName": "石家庄市第一医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7180,
        "hName": "首都儿科研究所附属儿童医院",
        "hGrade": "三级甲等",
        "hType": " 专科医院 "
    },
    {
        "provinceId": 7180,
        "hName": "首都医科大学附属北京安定医院",
        "hGrade": "三级甲等",
        "hType": " 专科医院 "
    },
    {
        "provinceId": 7180,
        "hName": "首都医科大学附属北京安贞医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7180,
        "hName": "首都医科大学附属北京朝阳医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7180,
        "hName": "首都医科大学附属北京地坛医院",
        "hGrade": "三级甲等",
        "hType": " 专科医院 "
    },
    {
        "provinceId": 7180,
        "hName": "首都医科大学附属北京儿童医院",
        "hGrade": "三级甲等",
        "hType": " 专科医院 "
    },
    {
        "provinceId": 7180,
        "hName": "首都医科大学附属北京妇产医院",
        "hGrade": "三级甲等",
        "hType": " 专科医院 "
    },
    {
        "provinceId": 7180,
        "hName": "首都医科大学附属北京口腔医院",
        "hGrade": "三级甲等",
        "hType": " 专科医院 "
    },
    {
        "provinceId": 7180,
        "hName": "首都医科大学附属北京世纪坛医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7180,
        "hName": "首都医科大学附属北京天坛医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7180,
        "hName": "首都医科大学附属北京同仁医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7180,
        "hName": "首都医科大学附属北京胸科医院",
        "hGrade": "三级甲等",
        "hType": " 专科医院 "
    },
    {
        "provinceId": 7180,
        "hName": "首都医科大学附属北京友谊医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7180,
        "hName": "首都医科大学附属北京佑安医院",
        "hGrade": "三级甲等",
        "hType": " 专科医院 "
    },
    {
        "provinceId": 7180,
        "hName": "首都医科大学宣武医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7224,
        "hName": "四川大学华西二院",
        "hGrade": "三级甲等",
        "hType": " 专科医院 "
    },
    {
        "provinceId": 7224,
        "hName": "四川大学华西医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7224,
        "hName": "四川省人民医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7224,
        "hName": "四川省肿瘤医院",
        "hGrade": "三级甲等",
        "hType": " 专科医院 "
    },
    {
        "provinceId": 7192,
        "hName": "四平市中心医院",
        "hGrade": "三级甲等",
        "hType": ""
    },
    {
        "provinceId": 7198,
        "hName": "苏州大学附属第一医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7198,
        "hName": "苏州大学附属儿童医院",
        "hGrade": "三级甲等",
        "hType": " 专科医院 "
    },
    {
        "provinceId": 7224,
        "hName": "遂宁市中心医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7186,
        "hName": "太原钢铁(集团)有限公司总医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7186,
        "hName": "太原市第三人民医院(太原市传染病医院)",
        "hGrade": "三级甲等",
        "hType": " 专科医院 "
    },
    {
        "provinceId": 7186,
        "hName": "太原市第四人民医院",
        "hGrade": "三级甲等",
        "hType": " 专科医院 "
    },
    {
        "provinceId": 7186,
        "hName": "太原市中心医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7208,
        "hName": "泰安市中心医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7182,
        "hName": "泰达国际心血管病医院",
        "hGrade": "三级甲等",
        "hType": " 专科医院 "
    },
    {
        "provinceId": 7198,
        "hName": "泰州市人民医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7184,
        "hName": "唐山市工人医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7184,
        "hName": "唐山市人民医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7182,
        "hName": "天津市传染病医院",
        "hGrade": "三级甲等",
        "hType": " 专科医院 "
    },
    {
        "provinceId": 7182,
        "hName": "天津市第三中心医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7182,
        "hName": "天津市第一中心医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7182,
        "hName": "天津市儿童医院",
        "hGrade": "三级甲等",
        "hType": " 专科医院 "
    },
    {
        "provinceId": 7182,
        "hName": "天津市环湖医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7182,
        "hName": "天津市口腔医院",
        "hGrade": "三级甲等",
        "hType": " 专科医院 "
    },
    {
        "provinceId": 7182,
        "hName": "天津市人民医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7182,
        "hName": "天津市天津医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7182,
        "hName": "天津市胸科医院",
        "hGrade": "三级甲等",
        "hType": " 专科医院 "
    },
    {
        "provinceId": 7182,
        "hName": "天津市眼科医院",
        "hGrade": "三级甲等",
        "hType": " 专科医院 "
    },
    {
        "provinceId": 7182,
        "hName": "天津市中心妇产科医院",
        "hGrade": "三级甲等",
        "hType": " 专科医院 "
    },
    {
        "provinceId": 7182,
        "hName": "天津市肿瘤医院",
        "hGrade": "三级甲等",
        "hType": " 专科医院 "
    },
    {
        "provinceId": 7182,
        "hName": "天津医科大学第二医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7182,
        "hName": "天津医科大学口腔医院",
        "hGrade": "三级甲等",
        "hType": " 专科医院 "
    },
    {
        "provinceId": 7182,
        "hName": "天津医科大学总医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7234,
        "hName": "天水市第一人民医院",
        "hGrade": "三级甲等",
        "hType": ""
    },
    {
        "provinceId": 7234,
        "hName": "天水市中西医医院",
        "hGrade": "三级甲等",
        "hType": ""
    },
    {
        "provinceId": 7190,
        "hName": "铁法煤业（集团）总医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7190,
        "hName": "铁岭市中心医院",
        "hGrade": "三级甲等",
        "hType": ""
    },
    {
        "provinceId": 7202,
        "hName": "铜陵市人民医院",
        "hGrade": "三级甲等",
        "hType": ""
    },
    {
        "provinceId": 7226,
        "hName": "铜仁市第一人民医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7202,
        "hName": "皖南医学院附属弋矶山医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7208,
        "hName": "潍坊市人民医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7180,
        "hName": "卫生部北京医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7180,
        "hName": "卫生部中日友好医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7232,
        "hName": "渭南市中心医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7200,
        "hName": "温州医学院附属第二医院（温州医学院附属育英儿童医院）",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7200,
        "hName": "温州医学院附属第一医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7200,
        "hName": "温州医学院附属眼视光医院",
        "hGrade": "三级甲等",
        "hType": " 专科医院 "
    },
    {
        "provinceId": 7240,
        "hName": "乌鲁木齐市友谊医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7198,
        "hName": "无锡市第二人民医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7198,
        "hName": "无锡市第四人民医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7198,
        "hName": "无锡市妇幼保健院",
        "hGrade": "三级甲等",
        "hType": " 专科医院 "
    },
    {
        "provinceId": 7198,
        "hName": "无锡市精神卫生中心",
        "hGrade": "三级甲等",
        "hType": " 专科医院 "
    },
    {
        "provinceId": 7198,
        "hName": "无锡市人民医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7202,
        "hName": "芜湖市第二人民医院",
        "hGrade": "三级甲等",
        "hType": ""
    },
    {
        "provinceId": 7218,
        "hName": "梧州市工人医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7212,
        "hName": "武汉大学口腔医院",
        "hGrade": "三级甲等",
        "hType": " 专科医院 "
    },
    {
        "provinceId": 7212,
        "hName": "武汉大学人民医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7212,
        "hName": "武汉大学中南医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7212,
        "hName": "武汉钢铁（集团）公司职工总医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7212,
        "hName": "武汉科技大学天佑医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7212,
        "hName": "武汉市第三医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7212,
        "hName": "武汉市妇女儿童医疗保健中心",
        "hGrade": "三级甲等",
        "hType": " 专科医院 "
    },
    {
        "provinceId": 7212,
        "hName": "武汉市普爱医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7212,
        "hName": "武汉市中西医结合医院",
        "hGrade": "三级甲等",
        "hType": " 中西医结合医院"
    },
    {
        "provinceId": 7212,
        "hName": "武汉市中心医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7232,
        "hName": "西安高新医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7232,
        "hName": "西安交通大学第一附属医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7232,
        "hName": "西安交通大学口腔医院",
        "hGrade": "三级甲等",
        "hType": " 专科医院 "
    },
    {
        "provinceId": 7232,
        "hName": "西安市第八医院",
        "hGrade": "三级甲等",
        "hType": " 专科医院 "
    },
    {
        "provinceId": 7232,
        "hName": "西安市第九医院",
        "hGrade": "三级甲等",
        "hType": ""
    },
    {
        "provinceId": 7232,
        "hName": "西安市第四医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7232,
        "hName": "西安市第五医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7232,
        "hName": "西安市第一医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7232,
        "hName": "西安市儿童医院",
        "hGrade": "三级甲等",
        "hType": " 专科医院 "
    },
    {
        "provinceId": 7232,
        "hName": "西安市红十字会医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7232,
        "hName": "西安市结核病胸部肿瘤医院",
        "hGrade": "三级甲等",
        "hType": " 专科医院 "
    },
    {
        "provinceId": 7232,
        "hName": "西安市精卫中心",
        "hGrade": "三级甲等",
        "hType": " 专科医院 "
    },
    {
        "provinceId": 7232,
        "hName": "西安市中心医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7230,
        "hName": "西藏自治区人民医院",
        "hGrade": "三级甲等",
        "hType": ""
    },
    {
        "provinceId": 7212,
        "hName": "咸宁市中心医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7232,
        "hName": "咸阳市中心医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7214,
        "hName": "湘潭市中心医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7212,
        "hName": "襄阳市第一人民医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7212,
        "hName": "襄阳市中心医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7212,
        "hName": "襄阳市中医医院",
        "hGrade": "三级甲等",
        "hType": ""
    },
    {
        "provinceId": 7212,
        "hName": "孝感市中心医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7186,
        "hName": "忻州市人民医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 21508,
        "hName": "新疆生产建设兵团医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7240,
        "hName": "新疆维吾尔自治区人民医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7240,
        "hName": "新疆医科大学第二附属医院",
        "hGrade": "三级甲等",
        "hType": ""
    },
    {
        "provinceId": 7240,
        "hName": "新疆医科大学第五附属医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7240,
        "hName": "新疆医科大学第一附属医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7240,
        "hName": "新疆医科大学附属肿瘤医院",
        "hGrade": "三级甲等",
        "hType": " 专科医院 "
    },
    {
        "provinceId": 7210,
        "hName": "新乡市中心医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7210,
        "hName": "新乡医学院第一附属医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7206,
        "hName": "新余市人民医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7184,
        "hName": "邢台市人民医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7226,
        "hName": "兴义市人民医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7198,
        "hName": "徐州市中心医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7198,
        "hName": "徐州医学院附属医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7224,
        "hName": "雅安市人民医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7208,
        "hName": "烟台毓璜顶医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7232,
        "hName": "延安大学附属医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7192,
        "hName": "延边大学附属医院",
        "hGrade": "三级甲等",
        "hType": ""
    },
    {
        "provinceId": 7198,
        "hName": "盐城市第一人民医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7198,
        "hName": "扬州市第一人民医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7216,
        "hName": "阳江市人民医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7186,
        "hName": "阳泉煤业(集团)有限责任公司总医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7186,
        "hName": "阳泉市第一人民医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7192,
        "hName": "一汽总医院（吉林大学第四医院）",
        "hGrade": "三级甲等",
        "hType": ""
    },
    {
        "provinceId": 7240,
        "hName": "伊犁哈萨克自治州友谊医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7224,
        "hName": "宜宾市第二人民医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7224,
        "hName": "宜宾市第一人民医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7212,
        "hName": "宜昌市第一人民医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7212,
        "hName": "宜昌市中心人民医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7206,
        "hName": "宜春市人民医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7214,
        "hName": "益阳市中心医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7238,
        "hName": "银川市第一人民医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7206,
        "hName": "鹰潭市人民医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7190,
        "hName": "营口市中心医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7214,
        "hName": "永州市中心医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7218,
        "hName": "右江民族医学院附属医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7232,
        "hName": "榆林市第二医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7232,
        "hName": "榆林市第一医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7218,
        "hName": "玉林市第一人民医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7228,
        "hName": "玉溪市人民医院",
        "hGrade": "三级甲等",
        "hType": ""
    },
    {
        "provinceId": 7228,
        "hName": "玉溪市中医医院",
        "hGrade": "三级甲等",
        "hType": ""
    },
    {
        "provinceId": 7214,
        "hName": "岳阳市一人民医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7216,
        "hName": "粤北人民医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7228,
        "hName": "云南省第一人民医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7208,
        "hName": "枣庄市立医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7216,
        "hName": "湛江中心人民医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7234,
        "hName": "张掖市人民医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7204,
        "hName": "漳州市医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7192,
        "hName": "长春市儿童医院",
        "hGrade": "三级甲等",
        "hType": ""
    },
    {
        "provinceId": 7192,
        "hName": "长春市妇产医院",
        "hGrade": "三级甲等",
        "hType": ""
    },
    {
        "provinceId": 7192,
        "hName": "长春市中心医院",
        "hGrade": "三级甲等",
        "hType": ""
    },
    {
        "provinceId": 7186,
        "hName": "长治医学院附属和济医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7186,
        "hName": "长治医学院附属和平医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7216,
        "hName": "肇庆市第一人民医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7200,
        "hName": "浙江大学医学院附属第二医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7200,
        "hName": "浙江大学医学院附属第一医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7200,
        "hName": "浙江大学医学院附属儿童医院",
        "hGrade": "三级甲等",
        "hType": " 专科医院 "
    },
    {
        "provinceId": 7200,
        "hName": "浙江大学医学院附属妇产科医院",
        "hGrade": "三级甲等",
        "hType": " 专科医院 "
    },
    {
        "provinceId": 7200,
        "hName": "浙江大学医学院附属邵逸夫医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7200,
        "hName": "浙江省口腔医院",
        "hGrade": "三级甲等",
        "hType": " 专科医院 "
    },
    {
        "provinceId": 7200,
        "hName": "浙江省人民医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7200,
        "hName": "浙江省台州医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7200,
        "hName": "浙江省肿瘤医院",
        "hGrade": "三级甲等",
        "hType": " 专科医院 "
    },
    {
        "provinceId": 7200,
        "hName": "浙江医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7198,
        "hName": "镇江市第四人民医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7198,
        "hName": "镇江市第一人民医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7210,
        "hName": "郑州大学第五附属医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7210,
        "hName": "郑州大学第一附属医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7210,
        "hName": "郑州市中心医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7196,
        "hName": "中国福利会国际和平妇幼保健院",
        "hGrade": "三级甲等",
        "hType": " 专科医院 "
    },
    {
        "provinceId": 7212,
        "hName": "中国葛洲坝集团中心医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7184,
        "hName": "中国石油天然气集团公司中心医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7190,
        "hName": "中国医科大学附属第四医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7190,
        "hName": "中国医科大学附属第一医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7190,
        "hName": "中国医科大学附属盛京医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7180,
        "hName": "中国医学科学院北京协和医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7180,
        "hName": "中国医学科学院阜外心血管病医院",
        "hGrade": "三级甲等",
        "hType": " 专科医院 "
    },
    {
        "provinceId": 7182,
        "hName": "中国医学科学院血液病医院(血液学研究所)",
        "hGrade": "三级甲等",
        "hType": " 专科医院 "
    },
    {
        "provinceId": 7180,
        "hName": "中国医学科学院整形外科医院",
        "hGrade": "三级甲等",
        "hType": " 专科医院 "
    },
    {
        "provinceId": 7180,
        "hName": "中国医学科学院肿瘤医院",
        "hGrade": "三级甲等",
        "hType": " 专科医院 "
    },
    {
        "provinceId": 7214,
        "hName": "中南大学湘雅二医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7214,
        "hName": "中南大学湘雅三医院",
        "hGrade": "三级甲等",
        "hType": ""
    },
    {
        "provinceId": 7214,
        "hName": "中南大学湘雅医院",
        "hGrade": "三级甲等",
        "hType": ""
    },
    {
        "provinceId": 7210,
        "hName": "中平能化医疗集团总医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7216,
        "hName": "中山大学附属第三医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7216,
        "hName": "中山大学附属第一医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7216,
        "hName": "中山大学附属肿瘤医院",
        "hGrade": "三级甲等",
        "hType": " 专科医院 "
    },
    {
        "provinceId": 7216,
        "hName": "中山大学孙逸仙纪念医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7216,
        "hName": "中山大学中山眼科中心",
        "hGrade": "三级甲等",
        "hType": " 专科医院 "
    },
    {
        "provinceId": 7216,
        "hName": "中山市博爱医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7216,
        "hName": "中山市人民医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7216,
        "hName": "中山市小榄人民医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7222,
        "hName": "重庆市第九人民医院",
        "hGrade": "三级甲等",
        "hType": ""
    },
    {
        "provinceId": 7222,
        "hName": "重庆市第三人民医院",
        "hGrade": "三级甲等",
        "hType": ""
    },
    {
        "provinceId": 7222,
        "hName": "重庆市第四人民医院",
        "hGrade": "三级甲等",
        "hType": ""
    },
    {
        "provinceId": 7222,
        "hName": "重庆市涪陵中心医院",
        "hGrade": "三级甲等",
        "hType": ""
    },
    {
        "provinceId": 7222,
        "hName": "重庆市三峡中心医院",
        "hGrade": "三级甲等",
        "hType": ""
    },
    {
        "provinceId": 7222,
        "hName": "重庆市肿瘤医院",
        "hGrade": "三级甲等",
        "hType": ""
    },
    {
        "provinceId": 7222,
        "hName": "重庆医科大学附属第二医院",
        "hGrade": "三级甲等",
        "hType": ""
    },
    {
        "provinceId": 7222,
        "hName": "重庆医科大学附属第一医院",
        "hGrade": "三级甲等",
        "hType": ""
    },
    {
        "provinceId": 7222,
        "hName": "重庆医科大学附属儿童医院",
        "hGrade": "三级甲等",
        "hType": ""
    },
    {
        "provinceId": 7222,
        "hName": "重庆医科大学附属口腔医院",
        "hGrade": "三级甲等",
        "hType": ""
    },
    {
        "provinceId": 7222,
        "hName": "重庆医科大学附属永川医院",
        "hGrade": "三级甲等",
        "hType": ""
    },
    {
        "provinceId": 7210,
        "hName": "周口市中心医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7216,
        "hName": "珠海市妇幼保健院",
        "hGrade": "三级甲等",
        "hType": " 妇幼保健院"
    },
    {
        "provinceId": 7216,
        "hName": "珠海市人民医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7214,
        "hName": "株洲市一医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7208,
        "hName": "淄博市中心医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7224,
        "hName": "自贡市第四人民医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7224,
        "hName": "自贡市第一人民医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7224,
        "hName": "自贡市精神卫生中心",
        "hGrade": "三级甲等",
        "hType": " 专科医院 "
    },
    {
        "provinceId": 7226,
        "hName": "遵义市第一人民医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    },
    {
        "provinceId": 7226,
        "hName": "遵义医学院附属口腔医院",
        "hGrade": "三级甲等",
        "hType": " 专科医院 "
    },
    {
        "provinceId": 7226,
        "hName": "遵义医学院附属医院",
        "hGrade": "三级甲等",
        "hType": " 综合医院"
    }
]';
        $o_data = '[{"provinceId":7210,"hName":"安钢集团总医院","hGrade":"三级未定等","hType":" 综合医院"},{"provinceId":7210,"hName":"安阳市心血管病医院","hGrade":"三级未定等","hType":""},{"provinceId":7180,"hName":"北京大学首钢医院","hGrade":"三级未定等","hType":" 综合医院"},{"provinceId":7180,"hName":"北京华信医院","hGrade":"三级未定等","hType":" 综合医院"},{"provinceId":7180,"hName":"北京京煤集团总医院","hGrade":"三级未定等","hType":" 综合医院"},{"provinceId":7180,"hName":"北京老年医院","hGrade":"三级未定等","hType":" 综合医院"},{"provinceId":7180,"hName":"北京市安康医院","hGrade":"三级未定等","hType":" 专科医院 "},{"provinceId":7180,"hName":"北京小汤山医院","hGrade":"三级未定等","hType":" 综合医院"},{"provinceId":7180,"hName":"北京燕化医院","hGrade":"三级未定等","hType":" 综合医院"},{"provinceId":7198,"hName":"常州市第三人民医院","hGrade":"三级未定等","hType":" 专科医院 "},{"provinceId":7190,"hName":"朝阳市第二医院","hGrade":"三级未定等","hType":" 综合医院"},{"provinceId":7216,"hName":"潮州市潮州医院","hGrade":"三级未定等","hType":" 综合医院"},{"provinceId":7216,"hName":"潮州市中心医院","hGrade":"三级未定等","hType":" 综合医院"},{"provinceId":7188,"hName":"赤峰市第二医院","hGrade":"三级未定等","hType":" 综合医院"},{"provinceId":7228,"hName":"大理学院附属医院","hGrade":"三级未定等","hType":" 综合医院"},{"provinceId":7190,"hName":"大连市第六人民医院","hGrade":"三级未定等","hType":" 专科医院 "},{"provinceId":7190,"hName":"大连市第七人民医院","hGrade":"三级未定等","hType":" 专科医院 "},{"provinceId":7194,"hName":"大庆市第二医院","hGrade":"三级未定等","hType":" 专科医院 "},{"provinceId":7194,"hName":"大庆市第四医院","hGrade":"三级未定等","hType":" 综合医院"},{"provinceId":7190,"hName":"丹东市人民医院","hGrade":"三级未定等","hType":" 综合医院"},{"provinceId":7208,"hName":"德州市第二人民医院","hGrade":"三级未定等","hType":" 综合医院"},{"provinceId":7228,"hName":"迪庆藏族自治州人民医院","hGrade":"三级未定等","hType":" 综合医院"},{"provinceId":7204,"hName":"福建省福州儿童医院","hGrade":"三级未定等","hType":""},{"provinceId":7204,"hName":"福建省龙岩市第三医院","hGrade":"三级未定等","hType":" 专科医院 "},{"provinceId":7204,"hName":"福建医科大学附属口腔医院","hGrade":"三级未定等","hType":" 专科医院 "},{"provinceId":7204,"hName":"福州市皮肤病防治院","hGrade":"三级未定等","hType":""},{"provinceId":7190,"hName":"阜新市第二人民医院（阜新市妇产医院）","hGrade":"三级未定等","hType":" 综合医院"},{"provinceId":7216,"hName":"高州市人民医院","hGrade":"三级未定等","hType":" 综合医院"},{"provinceId":7216,"hName":"广东省口腔医院","hGrade":"三级未定等","hType":" 专科医院 "},{"provinceId":7216,"hName":"广州市第八人民医院","hGrade":"三级未定等","hType":" 专科医院 "},{"provinceId":7216,"hName":"广州市第十二人民医院","hGrade":"三级未定等","hType":" 综合医院"},{"provinceId":7216,"hName":"广州市复大医疗投资管理有限公司复大肿瘤医院","hGrade":"三级未定等","hType":" 专科医院 "},{"provinceId":7216,"hName":"广州市胸科医院","hGrade":"三级未定等","hType":" 专科医院 "},{"provinceId":7216,"hName":"广州医学院附属肿瘤医院","hGrade":"三级未定等","hType":" 专科医院 "},{"provinceId":7216,"hName":"广州医学院口腔医院","hGrade":"三级未定等","hType":" 专科医院 "},{"provinceId":7226,"hName":"贵航贵阳医院","hGrade":"三级未定等","hType":" 综合医院"},{"provinceId":7226,"hName":"贵阳爱尔眼科医院有限公司","hGrade":"三级未定等","hType":" 专科医院 "},{"provinceId":7226,"hName":"贵阳市第三人民医院","hGrade":"三级未定等","hType":" 综合医院"},{"provinceId":7226,"hName":"贵阳市第五人民医院","hGrade":"三级未定等","hType":" 专科医院 "},{"provinceId":7226,"hName":"贵阳市肺科医院","hGrade":"三级未定等","hType":" 专科医院 "},{"provinceId":7226,"hName":"贵阳医学院附属白云医院","hGrade":"三级未定等","hType":" 综合医院"},{"provinceId":7226,"hName":"贵州电力职工医院","hGrade":"三级未定等","hType":" 综合医院"},{"provinceId":7226,"hName":"贵州盘江投资控股(集团)有限公司总医院","hGrade":"三级未定等","hType":" 综合医院"},{"provinceId":7226,"hName":"贵州省第二人民医院","hGrade":"三级未定等","hType":" 综合医院"},{"provinceId":7226,"hName":"贵州省遵义县人民医院","hGrade":"三级未定等","hType":" 综合医院"},{"provinceId":7226,"hName":"贵州整形美容外科医院","hGrade":"三级未定等","hType":" 专科医院 "},{"provinceId":7194,"hName":"哈尔滨市传染病院","hGrade":"三级未定等","hType":" 专科医院 "},{"provinceId":7194,"hName":"哈尔滨市第二医院","hGrade":"三级未定等","hType":" 综合医院"},{"provinceId":7194,"hName":"哈尔滨市第五医院","hGrade":"三级未定等","hType":" 综合医院"},{"provinceId":7194,"hName":"哈尔滨市骨伤医院","hGrade":"三级未定等","hType":" 专科医院 "},{"provinceId":7194,"hName":"哈尔滨市普宁医院","hGrade":"三级未定等","hType":" 专科医院 "},{"provinceId":7194,"hName":"哈尔滨市胸科医院","hGrade":"三级未定等","hType":" 专科医院 "},{"provinceId":7180,"hName":"航天中心医院","hGrade":"三级未定等","hType":" 综合医院"},{"provinceId":7184,"hName":"河北省民政总医院","hGrade":"三级未定等","hType":" 综合医院"},{"provinceId":7184,"hName":"河北省胸科医院","hGrade":"三级未定等","hType":" 专科疾病防治院"},{"provinceId":7184,"hName":"河北燕达医院","hGrade":"三级未定等","hType":" 综合医院"},{"provinceId":7210,"hName":"河南大学第一附属医院","hGrade":"三级未定等","hType":" 综合医院"},{"provinceId":7210,"hName":"河南科技大学第二附属医院","hGrade":"三级未定等","hType":" 综合医院"},{"provinceId":7210,"hName":"河南省传染病医院","hGrade":"三级未定等","hType":" 综合医院"},{"provinceId":7210,"hName":"河南省妇幼保健院","hGrade":"三级未定等","hType":" 综合医院"},{"provinceId":7210,"hName":"河南省口腔医院","hGrade":"三级未定等","hType":""},{"provinceId":7210,"hName":"河南省胸科医院","hGrade":"三级未定等","hType":" 专科医院 "},{"provinceId":7210,"hName":"河南石油勘探局职工医院","hGrade":"三级未定等","hType":" 综合医院"},{"provinceId":7210,"hName":"鹤壁市第一人民医院","hGrade":"三级未定等","hType":" 综合医院"},{"provinceId":7194,"hName":"黑龙江农垦神经精神病防治院","hGrade":"三级未定等","hType":" 专科疾病防治院"},{"provinceId":7194,"hName":"黑龙江省第三医院","hGrade":"三级未定等","hType":" 专科医院 "},{"provinceId":7194,"hName":"黑龙江省康复医院","hGrade":"三级未定等","hType":" 专科医院 "},{"provinceId":7194,"hName":"黑龙江省牡丹江神经精神病医院","hGrade":"三级未定等","hType":" 专科医院 "},{"provinceId":7194,"hName":"黑龙江省社会康复医院","hGrade":"三级未定等","hType":" 专科医院 "},{"provinceId":7194,"hName":"黑龙江省眼病防治研究所","hGrade":"三级未定等","hType":" 医学专科研究所 "},{"provinceId":7212,"hName":"湖北民族学院附属民大医院","hGrade":"三级未定等","hType":" 综合医院"},{"provinceId":7214,"hName":"湖南省脑科医院","hGrade":"三级未定等","hType":" 综合医院"},{"provinceId":7214,"hName":"湖南旺旺医院","hGrade":"三级未定等","hType":" 综合医院"},{"provinceId":7180,"hName":"华北电网有限公司北京电力医院","hGrade":"三级未定等","hType":" 综合医院"},{"provinceId":7214,"hName":"怀化市第三人民医院","hGrade":"三级未定等","hType":" 综合医院"},{"provinceId":7216,"hName":"惠州市第三人民医院","hGrade":"三级未定等","hType":" 综合医院"},{"provinceId":7216,"hName":"惠州市第一人民医院","hGrade":"三级未定等","hType":" 综合医院"},{"provinceId":7192,"hName":"吉林省妇幼保健院","hGrade":"三级未定等","hType":""},{"provinceId":7194,"hName":"佳木斯市精神病医院","hGrade":"三级未定等","hType":" 专科医院 "},{"provinceId":7210,"hName":"焦煤中央医院","hGrade":"三级未定等","hType":" 综合医院"},{"provinceId":7190,"hName":"锦州市第二医院","hGrade":"三级未定等","hType":" 综合医院"},{"provinceId":7190,"hName":"锦州市妇婴医院","hGrade":"三级未定等","hType":""},{"provinceId":7190,"hName":"锦州市康宁医院","hGrade":"三级未定等","hType":" 专科医院 "},{"provinceId":7210,"hName":"开封市第二人民医院","hGrade":"三级未定等","hType":" 综合医院"},{"provinceId":7210,"hName":"开封市儿童医院","hGrade":"三级未定等","hType":" 综合医院"},{"provinceId":7228,"hName":"昆明医科大学第三附属医院","hGrade":"三级未定等","hType":" 专科医院 "},{"provinceId":7190,"hName":"辽阳市第三人民医院","hGrade":"三级未定等","hType":" 综合医院"},{"provinceId":7208,"hName":"聊城市第二人民医院","hGrade":"三级未定等","hType":" 综合医院"},{"provinceId":7208,"hName":"聊城市第四人民医院","hGrade":"三级未定等","hType":" 专科医院 "},{"provinceId":7208,"hName":"临沂市精神卫生中心","hGrade":"三级未定等","hType":" 专科医院 "},{"provinceId":7208,"hName":"临沂市胸科医院","hGrade":"三级未定等","hType":" 专科医院 "},{"provinceId":7210,"hName":"洛阳东方医院","hGrade":"三级未定等","hType":" 综合医院"},{"provinceId":7210,"hName":"洛阳市第三人民医院","hGrade":"三级未定等","hType":" 综合医院"},{"provinceId":7210,"hName":"洛阳市第五人民医院","hGrade":"三级未定等","hType":" 专科医院 "},{"provinceId":7210,"hName":"洛阳市第一人民医院","hGrade":"三级未定等","hType":" 综合医院"},{"provinceId":7210,"hName":"洛阳市妇女儿童医疗保健中心","hGrade":"三级未定等","hType":" 专科医院 "},{"provinceId":7180,"hName":"煤炭总医院","hGrade":"三级未定等","hType":" 综合医院"},{"provinceId":7180,"hName":"民航总医院","hGrade":"三级未定等","hType":" 综合医院"},{"provinceId":7216,"hName":"南方医科大学第三附属医院","hGrade":"三级未定等","hType":" 综合医院"},{"provinceId":7198,"hName":"南京爱尔眼科医院","hGrade":"三级未定等","hType":" 专科医院 "},{"provinceId":7198,"hName":"南京医科大学眼科医院","hGrade":"三级未定等","hType":" 专科医院 "},{"provinceId":7198,"hName":"南京友谊整形外科医院","hGrade":"三级未定等","hType":" 专科医院 "},{"provinceId":7218,"hName":"南宁爱尔眼科医院","hGrade":"三级未定等","hType":" 专科医院 "},{"provinceId":7210,"hName":"南阳南石医院","hGrade":"三级未定等","hType":" 综合医院"},{"provinceId":7210,"hName":"南阳医学高等专科学校第一附属医院","hGrade":"三级未定等","hType":" 综合医院"},{"provinceId":7188,"hName":"内蒙古自治区精神卫生中心","hGrade":"三级未定等","hType":" 专科医院 "},{"provinceId":7188,"hName":"宁城县医院","hGrade":"三级未定等","hType":" 综合医院"},{"provinceId":7228,"hName":"怒江州人民医院","hGrade":"三级未定等","hType":" 综合医院"},{"provinceId":7210,"hName":"平顶山市第二人民医院","hGrade":"三级未定等","hType":" 综合医院"},{"provinceId":7216,"hName":"普宁华侨医院","hGrade":"三级未定等","hType":" 综合医院"},{"provinceId":7194,"hName":"齐齐哈尔市第一神经精神病医院","hGrade":"三级未定等","hType":" 专科医院 "},{"provinceId":7184,"hName":"秦皇岛市第二医院","hGrade":"三级未定等","hType":" 综合医院"},{"provinceId":7236,"hName":"青海省第五人民医院","hGrade":"三级未定等","hType":" 综合医院"},{"provinceId":7220,"hName":"琼海市人民医院","hGrade":"三级未定等","hType":" 乡镇卫生院"},{"provinceId":7212,"hName":"三峡大学仁和医院","hGrade":"三级未定等","hType":" 综合医院"},{"provinceId":7220,"hName":"三亚市人民医院","hGrade":"三级未定等","hType":" 综合医院"},{"provinceId":7204,"hName":"厦门科宏眼科医院","hGrade":"三级未定等","hType":""},{"provinceId":7204,"hName":"厦门市口腔医院","hGrade":"三级未定等","hType":""},{"provinceId":7204,"hName":"厦门长庚医院有限公司","hGrade":"三级未定等","hType":""},{"provinceId":7208,"hName":"山东省口腔医院","hGrade":"三级未定等","hType":" 专科医院 "},{"provinceId":7208,"hName":"山东省胸科医院","hGrade":"三级未定等","hType":" 专科医院 "},{"provinceId":7208,"hName":"山东省眼科医院","hGrade":"三级未定等","hType":" 专科医院 "},{"provinceId":7186,"hName":"山西大医院","hGrade":"三级未定等","hType":" 综合医院"},{"provinceId":7232,"hName":"陕西省第二人民医院","hGrade":"三级未定等","hType":""},{"provinceId":7232,"hName":"陕西省第四人民医院","hGrade":"三级未定等","hType":" 综合医院"},{"provinceId":7216,"hName":"汕头潮南民生医院","hGrade":"三级未定等","hType":" 综合医院"},{"provinceId":7216,"hName":"汕头大学 香港中文大学联合汕头国际眼科中心","hGrade":"三级未定等","hType":" 专科医院 "},{"provinceId":7216,"hName":"汕头大学医学院第二附属医院","hGrade":"三级未定等","hType":" 综合医院"},{"provinceId":7216,"hName":"汕头大学医学院附属肿瘤医院","hGrade":"三级未定等","hType":" 专科医院 "},{"provinceId":7216,"hName":"汕头市第二人民医院","hGrade":"三级未定等","hType":" 综合医院"},{"provinceId":7216,"hName":"汕尾市人民医院","hGrade":"三级未定等","hType":" 综合医院"},{"provinceId":7196,"hName":"上海市口腔病防治院","hGrade":"三级未定等","hType":" 专科医院 "},{"provinceId":7196,"hName":"上海市皮肤病医院","hGrade":"三级未定等","hType":" 专科医院 "},{"provinceId":7196,"hName":"上海市眼病防治中心","hGrade":"三级未定等","hType":" 专科医院 "},{"provinceId":7216,"hName":"深圳市第三人民医院","hGrade":"三级未定等","hType":" 专科医院 "},{"provinceId":7216,"hName":"深圳市儿童医院","hGrade":"三级未定等","hType":" 专科医院 "},{"provinceId":7216,"hName":"深圳市妇幼保健院","hGrade":"三级未定等","hType":" 妇幼保健院"},{"provinceId":7216,"hName":"深圳市康宁医院","hGrade":"三级未定等","hType":" 专科医院 "},{"provinceId":7216,"hName":"深圳市孙逸仙心血管医院","hGrade":"三级未定等","hType":" 专科医院 "},{"provinceId":7216,"hName":"深圳市眼科医院","hGrade":"三级未定等","hType":" 专科医院 "},{"provinceId":7190,"hName":"沈阳二四二医院","hGrade":"三级未定等","hType":" 综合医院"},{"provinceId":7190,"hName":"沈阳二四五医院","hGrade":"三级未定等","hType":" 综合医院"},{"provinceId":7190,"hName":"沈阳市第六人民医院","hGrade":"三级未定等","hType":" 专科医院 "},{"provinceId":7190,"hName":"沈阳市第一人民医院","hGrade":"三级未定等","hType":" 综合医院"},{"provinceId":7190,"hName":"沈阳市红十字会医院","hGrade":"三级未定等","hType":" 综合医院"},{"provinceId":7190,"hName":"沈阳维康医院","hGrade":"三级未定等","hType":" 综合医院"},{"provinceId":7184,"hName":"石家庄市第五医院","hGrade":"三级未定等","hType":" 专科医院 "},{"provinceId":7180,"hName":"首都医科大学附属复兴医院","hGrade":"三级未定等","hType":" 综合医院"},{"provinceId":7226,"hName":"首钢水城钢铁（集团）有限责任公司总医院","hGrade":"三级未定等","hType":" 综合医院"},{"provinceId":7194,"hName":"双鸭山市人民医院","hGrade":"三级未定等","hType":" 综合医院"},{"provinceId":7188,"hName":"通辽市科尔沁区第一人民医院","hGrade":"三级未定等","hType":" 综合医院"},{"provinceId":7196,"hName":"同济大学附属口腔医院","hGrade":"三级未定等","hType":" 专科医院 "},{"provinceId":7240,"hName":"乌鲁木齐市第四人民医院","hGrade":"三级未定等","hType":""},{"provinceId":7212,"hName":"武汉市第六医院","hGrade":"三级未定等","hType":" 综合医院"},{"provinceId":7212,"hName":"武汉亚洲心脏病医院","hGrade":"三级未定等","hType":" 专科医院 "},{"provinceId":7214,"hName":"湘潭市第一人民医院","hGrade":"三级未定等","hType":" 综合医院"},{"provinceId":7240,"hName":"新疆维吾尔自治区胸科医院","hGrade":"三级未定等","hType":" 专科医院 "},{"provinceId":7240,"hName":"新疆心脑血管病医院（有限公司）","hGrade":"三级未定等","hType":" 专科医院 "},{"provinceId":7210,"hName":"新乡市第二人民医院","hGrade":"三级未定等","hType":" 综合医院"},{"provinceId":7210,"hName":"新乡市第一人民医院","hGrade":"三级未评","hType":" 综合医院"},{"provinceId":7210,"hName":"信阳市中心医院","hGrade":"三级未定等","hType":" 综合医院"},{"provinceId":7184,"hName":"刑台医学高等专科学校第二附属医院","hGrade":"三级未定等","hType":" 综合医院"},{"provinceId":7184,"hName":"邢台市第三医院","hGrade":"三级未定等","hType":" 综合医院"},{"provinceId":7184,"hName":"邢台市眼科医院","hGrade":"三级未定等","hType":" 专科医院 "},{"provinceId":7210,"hName":"许昌市中心医院","hGrade":"三级未定等","hType":" 综合医院"},{"provinceId":7212,"hName":"宜昌市第二人民医院","hGrade":"三级未定等","hType":" 综合医院"},{"provinceId":7214,"hName":"永州职业技术学院附属医院","hGrade":"三级未定等","hType":" 综合医院"},{"provinceId":7228,"hName":"玉溪市第二人民医院","hGrade":"三级未定等","hType":" 专科医院 "},{"provinceId":7214,"hName":"岳阳市二人民医院","hGrade":"三级未定等","hType":" 综合医院"},{"provinceId":7216,"hName":"云浮市人民医院","hGrade":"三级未定等","hType":" 综合医院"},{"provinceId":7228,"hName":"云南省传染病专科医院","hGrade":"三级未定等","hType":" 专科医院 "},{"provinceId":7208,"hName":"枣庄市立第二医院","hGrade":"三级未定等","hType":" 专科医院 "},{"provinceId":7208,"hName":"枣庄市王开传染病医院","hGrade":"三级未定等","hType":" 专科医院 "},{"provinceId":7214,"hName":"张家界市人民医院","hGrade":"三级未定等","hType":" 综合医院"},{"provinceId":7214,"hName":"长沙市第三医院","hGrade":"三级未定等","hType":" 综合医院"},{"provinceId":7214,"hName":"长沙市第四医院","hGrade":"三级未定等","hType":" 综合医院"},{"provinceId":7210,"hName":"郑州大学第二附属医院","hGrade":"三级未定等","hType":" 综合医院"},{"provinceId":7210,"hName":"郑州人民医院","hGrade":"三级未定等","hType":" 综合医院"},{"provinceId":7210,"hName":"郑州市妇幼保健院","hGrade":"三级未评","hType":" 妇幼保健院"},{"provinceId":7210,"hName":"郑州市心血管病医院","hGrade":"三级未定等","hType":" 专科医院 "},{"provinceId":7190,"hName":"中国医科大学附属口腔医院","hGrade":"三级未定等","hType":" 专科医院 "},{"provinceId":7216,"hName":"中山大学附属第六医院","hGrade":"三级未定等","hType":" 综合医院"},{"provinceId":7216,"hName":"中山大学附属口腔医院","hGrade":"三级未定等","hType":" 专科医院 "},{"provinceId":7216,"hName":"珠海市第二人民医院","hGrade":"三级未定等","hType":" 综合医院"},{"provinceId":7214,"hName":"株洲恺德心血管病医院","hGrade":"三级未定等","hType":" 专科医院 "},{"provinceId":7214,"hName":"株洲市三三一医院","hGrade":"三级未定等","hType":" 综合医院"},{"provinceId":7226,"hName":"遵义市妇女儿童院","hGrade":"三级未定等","hType":" 专科医院 "}]';
        $o_data = '[{"provinceId":7224,"hName":"阿坝州人民医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7188,"hName":"巴彦淖尔市医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7224,"hName":"巴中市中心医院","hGrade":"三级乙等","hType":""},{"provinceId":7192,"hName":"白山市中心医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7234,"hName":"白银市第一人民医院","hGrade":"三级乙等","hType":""},{"provinceId":7232,"hName":"宝鸡市人民医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7228,"hName":"保山市人民医院","hGrade":"三级乙等","hType":""},{"provinceId":7190,"hName":"本溪市金山医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7208,"hName":"滨州市人民医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7208,"hName":"滨州市中心医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7224,"hName":"成都市第六人民医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7188,"hName":"赤峰学院附属医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7228,"hName":"楚雄州人民医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7200,"hName":"慈溪市妇幼保健院","hGrade":"三级乙等","hType":" 专科医院 "},{"provinceId":7200,"hName":"慈溪市人民医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7228,"hName":"大理白族自治州人民医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7186,"hName":"大同市第一人民医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7208,"hName":"单县中心医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7228,"hName":"德宏州医疗集团人民医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7208,"hName":"德州市人民医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7200,"hName":"东阳市人民医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7208,"hName":"东营市第二人民医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7188,"hName":"鄂尔多斯市蒙医医院","hGrade":"三级乙等","hType":" 民族医院 "},{"provinceId":7212,"hName":"鄂钢医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7208,"hName":"肥城矿业中心医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7206,"hName":"丰城市人民医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7216,"hName":"佛山市三水区人民医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7190,"hName":"抚顺煤矿脑科医院","hGrade":"三级乙等","hType":" 专科医院 "},{"provinceId":7190,"hName":"抚顺市第二医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7190,"hName":"抚顺市第三医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7190,"hName":"抚顺市第五医院","hGrade":"三级乙等","hType":" 专科医院 "},{"provinceId":7196,"hName":"复旦大学附属金山医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7196,"hName":"复旦大学附属中山医院青浦分院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7234,"hName":"甘肃省武威肿瘤医院","hGrade":"三级乙等","hType":""},{"provinceId":7224,"hName":"甘孜州人民医院","hGrade":"三级乙等","hType":""},{"provinceId":7192,"hName":"公主岭市妇幼保健院","hGrade":"三级乙等","hType":""},{"provinceId":7218,"hName":"广西壮族自治区龙潭医院","hGrade":"三级乙等","hType":" 专科医院 "},{"provinceId":7216,"hName":"广州市花都区人民医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7226,"hName":"贵阳白志祥骨科医院","hGrade":"三级乙等","hType":" 专科医院 "},{"provinceId":7226,"hName":"贵州航天医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7226,"hName":"贵州水矿控股集团有限责任公司总医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7184,"hName":"邯郸市中西医结合医院","hGrade":"三级乙等","hType":" 中西医结合医院"},{"provinceId":7200,"hName":"杭州市萧山区第一人民医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7216,"hName":"河源市人民医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7232,"hName":"核工业215医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7208,"hName":"菏泽市立医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7194,"hName":"黑龙江省林业第二医院肿瘤结核病医院","hGrade":"三级乙等","hType":" 专科医院 "},{"provinceId":7194,"hName":"黑龙江省农垦九三管理局中心医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7184,"hName":"衡水市第四人民医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7186,"hName":"侯马市人民医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7188,"hName":"呼和浩特第二医院","hGrade":"三级乙等","hType":" 专科医院 "},{"provinceId":7188,"hName":"呼和浩特第一医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7188,"hName":"呼伦贝尔市人民医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7212,"hName":"湖北省新华医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7200,"hName":"湖州市第一人民医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7198,"hName":"淮安市传染病医院","hGrade":"三级乙等","hType":" 专科医院 "},{"provinceId":7198,"hName":"淮安市第二人民医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7198,"hName":"淮安市第三人民医院","hGrade":"三级乙等","hType":" 专科医院 "},{"provinceId":7198,"hName":"淮安市肿瘤医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7202,"hName":"淮南新华医院","hGrade":"三级乙等","hType":""},{"provinceId":7212,"hName":"黄石市爱康医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7234,"hName":"嘉峪关市第一人民医院","hGrade":"三级乙等","hType":""},{"provinceId":7200,"hName":"金华市第二医院","hGrade":"三级乙等","hType":" 专科医院 "},{"provinceId":7200,"hName":"金华市人民医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7186,"hName":"晋中市第二人民医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7234,"hName":"靖远煤业集团有限责任公司总医院","hGrade":"三级乙等","hType":""},{"provinceId":7234,"hName":"酒钢医院","hGrade":"三级乙等","hType":""},{"provinceId":7208,"hName":"莱芜钢铁集团有限公司医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7234,"hName":"兰州石化总医院","hGrade":"三级乙等","hType":""},{"provinceId":7234,"hName":"兰州市第二人民医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7200,"hName":"乐清市人民医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7228,"hName":"丽江市人民医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7200,"hName":"丽水市第二人民医院","hGrade":"三级乙等","hType":" 专科医院 "},{"provinceId":7224,"hName":"凉山州第二人民医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7192,"hName":"辽源矿业（集团）有限责任公司职工总医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7192,"hName":"辽源市中心医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7228,"hName":"临沧市人民医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7234,"hName":"临夏州人民医院","hGrade":"三级乙等","hType":""},{"provinceId":7208,"hName":"临沂市沂水中心医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7204,"hName":"龙岩市第二医院","hGrade":"三级乙等","hType":""},{"provinceId":7218,"hName":"南宁市第四人民医院","hGrade":"三级乙等","hType":" 专科医院 "},{"provinceId":7204,"hName":"南平市人民医院","hGrade":"三级乙等","hType":""},{"provinceId":7198,"hName":"南通市妇幼保健院","hGrade":"三级乙等","hType":" 专科医院 "},{"provinceId":7188,"hName":"内蒙古科技大学包头医学院第二附属医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7200,"hName":"宁波市康宁医院","hGrade":"三级乙等","hType":" 专科医院 "},{"provinceId":7200,"hName":"宁波市眼科医院","hGrade":"三级乙等","hType":" 专科医院 "},{"provinceId":7200,"hName":"宁波市鄞州人民医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7188,"hName":"宁城县蒙医中医医院","hGrade":"三级乙等","hType":" 民族医院 "},{"provinceId":7200,"hName":"平湖市第一人民医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7234,"hName":"平凉市第二人民医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7206,"hName":"萍乡市第二人民医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7228,"hName":"普洱市人民医院","hGrade":"三级乙等","hType":""},{"provinceId":7228,"hName":"普洱市中医医院","hGrade":"三级乙等","hType":""},{"provinceId":7208,"hName":"青岛市海慈医疗集团（海慈医院）","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7208,"hName":"青岛市胶州中心医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7234,"hName":"庆阳市中医医院","hGrade":"三级乙等","hType":""},{"provinceId":7200,"hName":"衢州市中医医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7204,"hName":"泉州市第三医院","hGrade":"三级乙等","hType":" 专科医院 "},{"provinceId":7208,"hName":"日照市人民医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7208,"hName":"荣成市人民医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7200,"hName":"瑞安市人民医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7204,"hName":"三明市第二医院","hGrade":"三级乙等","hType":""},{"provinceId":7204,"hName":"厦门市第二医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7204,"hName":"厦门市第三医院","hGrade":"三级乙等","hType":""},{"provinceId":7186,"hName":"山西省长治市第二人民医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7216,"hName":"汕尾逸挥基金医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7232,"hName":"商洛市中心医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7210,"hName":"商丘市第一人民医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7196,"hName":"上海交通大学医学院附属第三人民医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7196,"hName":"上海交通大学医学院附属新华医院崇明分院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7196,"hName":"上海市第五人民医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7196,"hName":"上海市奉贤区中心医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7196,"hName":"上海市普陀区中心医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7196,"hName":"上海市杨浦区中心医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7214,"hName":"邵阳市第一人民医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7200,"hName":"绍兴第二医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7200,"hName":"绍兴市第六人民医院","hGrade":"三级乙等","hType":" 专科医院 "},{"provinceId":7200,"hName":"绍兴市第七人民医院","hGrade":"三级乙等","hType":" 专科医院 "},{"provinceId":7224,"hName":"省中西医结合医院","hGrade":"三级乙等","hType":""},{"provinceId":7224,"hName":"四川绵阳四0四医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7192,"hName":"四平市第一人民医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7198,"hName":"苏州市第五人民医院","hGrade":"三级乙等","hType":" 专科医院 "},{"provinceId":7198,"hName":"苏州市立医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7212,"hName":"随州市中心医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7200,"hName":"台州市第一人民医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7200,"hName":"台州市立医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7208,"hName":"泰山医学院附属医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7208,"hName":"滕州市中心人民医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7182,"hName":"天津市安定医院","hGrade":"三级乙等","hType":" 专科医院 "},{"provinceId":7182,"hName":"天津市宝坻区人民医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7182,"hName":"天津市第三医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7182,"hName":"天津市第四医院","hGrade":"三级乙等","hType":" 专科医院 "},{"provinceId":7182,"hName":"天津市第四中心医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7182,"hName":"天津市第五中心医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7182,"hName":"天津市公安局安康医院","hGrade":"三级乙等","hType":" 专科医院 "},{"provinceId":7182,"hName":"天津市海河医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7182,"hName":"天津市蓟县人民医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7182,"hName":"天津市静海县医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7182,"hName":"天津市天和医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7182,"hName":"天津医科大学代谢病医院","hGrade":"三级乙等","hType":" 专科医院 "},{"provinceId":7234,"hName":"天水市中医医院","hGrade":"三级乙等","hType":""},{"provinceId":7192,"hName":"通化市中心医院","hGrade":"三级乙等","hType":""},{"provinceId":7188,"hName":"通辽市医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7232,"hName":"铜川矿务局中心医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7232,"hName":"铜川市人民医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7208,"hName":"威海市立医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7208,"hName":"威海市文登中心医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7208,"hName":"潍坊市益都中心医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7208,"hName":"潍坊医学院附属医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7200,"hName":"温岭市第一人民医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7200,"hName":"温州康宁医院","hGrade":"三级乙等","hType":" 专科医院 "},{"provinceId":7228,"hName":"文山州人民医院","hGrade":"三级乙等","hType":""},{"provinceId":7228,"hName":"文山州中医医院","hGrade":"三级乙等","hType":""},{"provinceId":7188,"hName":"乌兰察布市中心医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7198,"hName":"无锡市传染病医院","hGrade":"三级乙等","hType":" 专科医院 "},{"provinceId":7218,"hName":"梧州市红十字会医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7218,"hName":"梧州市人民医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7212,"hName":"武汉钢铁公司第二职工医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7212,"hName":"武汉市普仁医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7234,"hName":"武威市凉州区中医医院","hGrade":"三级乙等","hType":""},{"provinceId":7234,"hName":"武威市凉州医院","hGrade":"三级乙等","hType":""},{"provinceId":7234,"hName":"武威市人民医院","hGrade":"三级乙等","hType":""},{"provinceId":7234,"hName":"武威市中医医院","hGrade":"三级乙等","hType":""},{"provinceId":7232,"hName":"西安医学院第二附属医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7232,"hName":"西安医学院附属医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7186,"hName":"西山煤电（集团）有限责任公司职工总医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7188,"hName":"锡林郭勒盟医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7188,"hName":"锡盟蒙医研究所","hGrade":"三级乙等","hType":" 医学专科研究所 "},{"provinceId":7232,"hName":"咸阳市第一人民医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7214,"hName":"湘西自治州人民医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7206,"hName":"湘雅萍矿合作医院（萍矿总医院）","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7208,"hName":"新汶矿业集团有限责任公司中心医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7198,"hName":"宿迁市人民医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7198,"hName":"徐州市第一人民医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7202,"hName":"宣城地区人民医院","hGrade":"三级乙等","hType":""},{"provinceId":7208,"hName":"烟台市莱阳中心医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7208,"hName":"烟台市烟台山医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7232,"hName":"延安市人民医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7208,"hName":"兖矿集团有限公司总医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7186,"hName":"阳泉市第三人民医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7194,"hName":"伊春林业管理局中心医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7240,"hName":"伊犁哈萨克自治州奎屯医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7206,"hName":"宜春市第三人民医院","hGrade":"三级乙等","hType":" 专科医院 "},{"provinceId":7200,"hName":"义乌市中心医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7200,"hName":"余姚市人民医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7232,"hName":"榆林市星元医院","hGrade":"三级乙等","hType":" 专科医院 "},{"provinceId":7218,"hName":"玉林市红十字会医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7228,"hName":"云南省第二人民医院","hGrade":"三级乙等","hType":""},{"provinceId":7228,"hName":"云南省第三人民医院","hGrade":"三级乙等","hType":""},{"provinceId":7228,"hName":"云南省农垦总局第一职工医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7208,"hName":"枣庄矿业集团公司中心医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7208,"hName":"沾化县人民医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7212,"hName":"长江航运总医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7216,"hName":"肇庆市端州区妇幼保健院","hGrade":"三级乙等","hType":" 妇幼保健院"},{"provinceId":7200,"hName":"浙江萧山医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7198,"hName":"镇江市传染病医院","hGrade":"三级乙等","hType":" 专科医院 "},{"provinceId":7226,"hName":"中国贵航集团三〇二医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7208,"hName":"中国石化集团胜利石油管理局胜利医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7232,"hName":"中铁二十局集团有限公司医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7186,"hName":"中信机电制造公司总医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7200,"hName":"诸暨市人民医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7208,"hName":"淄博矿业集团有限责任公司中心医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7208,"hName":"淄博市第一医院","hGrade":"三级乙等","hType":" 综合医院"},{"provinceId":7224,"hName":"自贡市第三人民医院","hGrade":"三级乙等","hType":" 综合医院"}]';

        $o_data = '[{"provinceId":7190,"hName":"鞍山市第二医院","hGrade":"三级其他","hType":" 综合医院"},{"provinceId":7190,"hName":"鞍山市第三医院","hGrade":"三级其他","hType":" 综合医院"},{"provinceId":7190,"hName":"鞍山市第四医院","hGrade":"三级其他","hType":" 专科医院 "},{"provinceId":7190,"hName":"鞍山市妇儿医院（第五医院）","hGrade":"三级其他","hType":" 专科医院 "},{"provinceId":7190,"hName":"鞍山市精神康复医院","hGrade":"三级其他","hType":" 专科医院 "},{"provinceId":7190,"hName":"鞍山市康宁医院","hGrade":"三级其他","hType":" 专科医院 "},{"provinceId":7190,"hName":"鞍山市千山医院","hGrade":"三级其他","hType":" 专科医院 "},{"provinceId":7190,"hName":"鞍山市曙光医院","hGrade":"三级其他","hType":" 综合医院"},{"provinceId":7190,"hName":"鞍山市双山医院","hGrade":"三级其他","hType":" 综合医院"},{"provinceId":7190,"hName":"鞍山市长大医院","hGrade":"三级其他","hType":" 综合医院"},{"provinceId":7188,"hName":"包头市第四医院","hGrade":"三级其他","hType":" 综合医院"},{"provinceId":7190,"hName":"大连市儿童医院","hGrade":"三级其他","hType":" 专科医院 "},{"provinceId":7190,"hName":"大连市妇幼保健院（大连市妇产医院）","hGrade":"三级其他","hType":" 专科医院 "},{"provinceId":7190,"hName":"大连市结核病医院","hGrade":"三级其他","hType":" 专科医院 "},{"provinceId":7190,"hName":"大连市口腔医院","hGrade":"三级其他","hType":" 专科医院 "},{"provinceId":7190,"hName":"大连市皮肤病医院","hGrade":"三级其他","hType":" 专科医院 "},{"provinceId":7190,"hName":"抚顺市传染病医院","hGrade":"三级其他","hType":" 专科医院 "},{"provinceId":7192,"hName":"公主岭市中心医院","hGrade":"三级其他","hType":""},{"provinceId":7218,"hName":"广西壮族自治区桂东人民医院","hGrade":"三级其他","hType":" 综合医院"},{"provinceId":7210,"hName":"河南省南阳市第一人民医院","hGrade":"三级其他","hType":" 综合医院"},{"provinceId":7214,"hName":"衡阳市第一人民医院","hGrade":"三级其他","hType":" 综合医院"},{"provinceId":7212,"hName":"湖北省黄石市妇幼保健院","hGrade":"三级其他","hType":" 专科医院 "},{"provinceId":7214,"hName":"湖南师范大学附属湘东医院","hGrade":"三级其他","hType":" 综合医院"},{"provinceId":7210,"hName":"黄河三门峡医院","hGrade":"三级其他","hType":" 综合医院"},{"provinceId":7192,"hName":"吉林省前卫医院","hGrade":"三级其他","hType":""},{"provinceId":7210,"hName":"济源市人民医院","hGrade":"三级其他","hType":" 综合医院"},{"provinceId":7206,"hName":"江西中寰医院","hGrade":"三级其他","hType":" 综合医院"},{"provinceId":7234,"hName":"金川集团有限公司职工医院","hGrade":"三级其他","hType":""},{"provinceId":7190,"hName":"锦州市传染病医院","hGrade":"三级其他","hType":" 专科医院 "},{"provinceId":7228,"hName":"昆明医科大学附属口腔医院","hGrade":"三级其他","hType":" 专科医院 "},{"provinceId":7210,"hName":"漯河医学高等专科学校第二附属医院","hGrade":"三级其他","hType":" 综合医院"},{"provinceId":7210,"hName":"南阳市第二人民医院","hGrade":"三级其他","hType":" 综合医院"},{"provinceId":7188,"hName":"内蒙古北方重工业集团有限公司医院","hGrade":"三级其他","hType":" 综合医院"},{"provinceId":7188,"hName":"内蒙古第一机械集团有限公司医院","hGrade":"三级其他","hType":" 综合医院"},{"provinceId":7188,"hName":"内蒙古自治区第四医院","hGrade":"三级其他","hType":" 专科医院 "},{"provinceId":7192,"hName":"前郭尔罗斯蒙古族自治县医院","hGrade":"三级其他","hType":" 综合医院"},{"provinceId":7208,"hName":"青岛市肿瘤医院","hGrade":"三级其他","hType":" 专科医院 "},{"provinceId":7210,"hName":"三门峡市中心医院","hGrade":"三级其他","hType":""},{"provinceId":7190,"hName":"沈阳市儿童医院","hGrade":"三级其他","hType":" 专科医院 "},{"provinceId":7190,"hName":"沈阳市妇婴医院","hGrade":"三级其他","hType":" 专科医院 "},{"provinceId":7190,"hName":"沈阳市骨科医院","hGrade":"三级其他","hType":" 专科医院 "},{"provinceId":7190,"hName":"沈阳市精神卫生中心","hGrade":"三级其他","hType":" 专科医院 "},{"provinceId":7190,"hName":"沈阳市口腔医院","hGrade":"三级其他","hType":" 专科医院 "},{"provinceId":7190,"hName":"沈阳市胸科医院","hGrade":"三级其他","hType":" 专科医院 "},{"provinceId":7192,"hName":"松原市中心医院","hGrade":"三级其他","hType":" 综合医院"},{"provinceId":7208,"hName":"威海市经济技术开发区医院（威海市精神卫生中心）","hGrade":"三级其他","hType":" 专科医院 "},{"provinceId":7188,"hName":"乌海市人民医院","hGrade":"三级其他","hType":" 综合医院"},{"provinceId":7212,"hName":"武汉市精神卫生中心","hGrade":"三级其他","hType":" 专科医院 "},{"provinceId":7212,"hName":"武汉市医疗救治中心","hGrade":"三级其他","hType":" 综合医院"},{"provinceId":7212,"hName":"孝感市康复医院","hGrade":"三级其他","hType":" 专科医院 "},{"provinceId":7188,"hName":"兴安盟人民医院","hGrade":"三级其他","hType":" 综合医院"},{"provinceId":7192,"hName":"长春市人民医院","hGrade":"三级其他","hType":""},{"provinceId":7200,"hName":"浙江新安国际医院","hGrade":"三级其他","hType":" 综合医院"},{"provinceId":7210,"hName":"郑州市第一人民医院","hGrade":"三级其他","hType":""},{"provinceId":7210,"hName":"郑州市儿童医院","hGrade":"三级其他","hType":" 综合医院"},{"provinceId":7190,"hName":"中国医科大学附属第一医院鞍山医院","hGrade":"三级其他","hType":" 综合医院"},{"provinceId":7216,"hName":"中山大学附属第五医院","hGrade":"三级其他","hType":" 综合医院"},{"provinceId":7210,"hName":"驻马店市第一人民医院","hGrade":"三级其他","hType":" 综合医院"},{"provinceId":7210,"hName":"驻马店市精神病院","hGrade":"三级其他","hType":""},{"provinceId":7210,"hName":"驻马店市中心医院","hGrade":"三级其他","hType":""}]';
//        var_dump(json_decode($o_data));
        $data_o = json_decode($o_data);
        foreach ($data_o as $K => $V) {
//            if($V->hName=="鸡西矿业集团总医院"){
            echo '<a target="_blank" href = "https://qs.geekheal.net/item/' . $V->hName . '">' . $V->hName . '</a></br>';
//            }
//            dd($V->hName);
        }
        dd();
        $data = array();
        foreach ($data_o->data as $k => $v) {
            if (($v->tel != "暂未收录" && ($v->address == "暂未收录") || $v->address == "")) {

                $sub_data = array($v->name, $v->tel, $v->address);
                $data[] = $sub_data;
            }
        }
//        var_dump($data);
//        $data = array(
//            array('data1', 'data2'),
//            array('data3', 'data4')
//        );

        Excel::create('test', function ($excel) use ($data) {

            $excel->sheet('Sheetname', function ($sheet) use ($data) {

                $sheet->fromArray($data);

            });

        })->export('xls');
    }

    public function export_order()
    {
        $file = storage_path("app/doctor.xls");

        Excel::load($file, function ($reader) {

            $reader->each(function ($sheet) {
                if ($sheet[3] != NULL && $sheet[3] != "姓名") {
                    $arr = array();
                    $arr["name"] = $sheet[3];
                    $arr["hospital"] = $sheet[4];
                    $arr["title"] = $sheet[6];
                    $arr["aspect"] = $sheet[5];
                    $arr["gender"] = $sheet[7];
//                    $this->doctor_indb($arr);
                }
                $sheet->each(function ($row) {
//                    var_dump($row);
//                    if($row[3]!=NULL&& $row[3]!="姓名"){
//                        $this->parase_log($row[3]);
//                    }
                });
//                }
                // Loop through all rows

            });

        });
    }

    public function my_dict()
    {
        Companies::chunk(200, function ($companys) {
            foreach ($companys as $k => $v) {
                if ($this->check_file('/usr/local/laravel/storage/app/entity_dict.txt', $v->name)) {
                    continue;
                }
                $this->parase_log($v->name);
            }

        });
    }

    public function parase_log($pam = '')
    {
//        $fp = fopen("/usr/local/laravel/storage/app/order_num.txt", "a+"); //文件被清空后再写入
        $fp = fopen("/usr/local/laravel/storage/app/entity_dict.txt", "a+"); //文件被清空后再写入
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

    public function doctor_indb($arr)
    {
        if ($arr["name"] == "梁建宏") {
            return false;
        }
        $person_doctor = new Founders();
        $person_doctor->name = $arr["name"];
        $person_doctor->workedCases = array([
            "name" => $arr["hospital"],
            "title" => $arr["title"],
            "des" => $arr["aspect"],
        ]);
        $person_doctor->gender = $arr["gender"];
        $person_doctor->tags = ["医生", "专家"];
        $person_doctor->patent = [];
        $person_doctor->projects = [];
        $person_doctor->edu_background = [];
        $person_doctor->founderCases = [];
        $person_doctor->paper = [];
        $person_doctor->des = "";
        $person_doctor->s_position = "";
        $person_doctor->mail = "";
        $person_doctor->tel = "";
        $person_doctor->avatar = "";
        $person_doctor->__v = 0;
        $tt = $person_doctor->save();
//        dd($tt);
    }

    public function coreseek()
    {

        $sphinxClient = new \SphinxClient();

        $sphinxClient->setServer('localhost', 9312);   // server = localhost,port = 9312.
        $sphinxClient->setMatchMode(SPH_MATCH_EXTENDED2);
        $sphinxClient->SetLimits(0, 100, 1000, 0);
        $sphinxClient->setMaxQueryTime(5000);  // set search time 5  seconds.
        $result = $sphinxClient->query("人工智能", 'news');
        dd($result);
        $ids = array();
        foreach ($result['matches'] as $k => $v) {
            $ids[] = $v["attrs"]["_id"];
        }
        $result_company = Companies::whereIn("_id", $ids)->get();

        $opts = array(
            'before_match' => '<b style="color:red">',
            'after_match' => '</b>',
        );
        foreach ($result_company as $k => $v) {
            if (isset($v->detail)) {
                $S[] = $v->detail;
                $r = $sphinxClient->BuildExcerpts($S, 'xml', '糖尿病', $opts);
                $v->detail = $r[0];
                var_dump($r);
            }

        }

        dd($result_company);
        if (isset($result['matches'])) {
            dd($result['matches']);
            $rel['time'] = $result['time'];
            $rel['matches'] = $result['matches'];
            return $rel;
        } else {
            $rel['time'] = $result['time'];
            return $rel;
        }
    }

    public function coreseek_xml()
    {
        $xml = new SphinxXmlpipe();
        $xml->xmlpipe2();
    }

    public function export_person_list()
    {
        \DB::collection('founder_total')->chunk(200, function ($persons) {

            foreach ($persons as $person) {
//                dd($person);
                $link = "https://www.crunchbase.com" . $person["_id"]["founderDetailLink"];
//                dd($link);
                $this->parase_log($link);
            }

        });
    }

}