<?php
/**
 * Created by PhpStorm.
 * User: wangpan
 * Date: 17/4/9
 * Time: 下午4:55
 */

namespace App\Media;

use App\DailyNews;
use App\Notes;
use App\Companies;
use Carbon\Carbon;

class SphinxXmlpipe
{

    private $xmlWriter;
    private $fields = array();
    private $attributes = array();
    private $documents = array();
    private $id = 1;

    public function setFields($fields)
    {
        $this->fields = $fields;
    }

    public function setAttributes($attributes)
    {
        $this->attributes = $attributes;
    }

    public function beginOutput()
    {
        //create a new xml document
        $this->xmlWriter = new \XMLWriter();
        $this->xmlWriter->openMemory();
        $this->xmlWriter->setIndent(true);
        $this->xmlWriter->startDocument('1.0', 'UTF-8');

        $this->xmlWriter->startElement('sphinx:docset');
        $this->xmlWriter->startElement('sphinx:schema');

        // add fileds to the schma
        foreach ($this->fields as $field) {
            $this->xmlWriter->startElement('sphinx:field');
            $this->xmlWriter->writeAttribute('name', $field);
            $this->xmlWriter->endElement();
        }


        // add atttributes to the schema
        foreach ($this->attributes as $attributes) {
            $this->xmlWriter->startElement('sphinx:attr');
            foreach ($attributes as $key => $value) {
                $this->xmlWriter->writeAttribute($key, $value);
            }
            $this->xmlWriter->endElement();
        }

        $this->xmlWriter->endElement(); // schema
    }

    public function addDocument($doc)
    {
        $this->xmlWriter->startElement('sphinx:document');
        $this->xmlWriter->writeAttribute('id', $this->id);
        $doc = $doc->toArray();
        foreach ($doc as $key => $value) {
            if ($key == "tags" && !empty($value)) {
                $value = implode(" ",$value);
            }
            if (is_array($value))
                continue;
            if ($key == "created_at") {
                $value = strtotime($value);
            }
            if ($key == "updated_at") {
                $value = strtotime($value);
            }
            $this->xmlWriter->startElement($key);
            $this->xmlWriter->text($value);
            $this->xmlWriter->endElement();
        }
        $this->id = $this->id + 1;
        $this->xmlWriter->endElement(); // document
    }

    public function endOutput()
    {
        // end sphinx:docset
        $this->xmlWriter->endElement();
        $this->xmlWriter->endDocument();
        $this->save($this->xmlWriter->outputMemory());
        echo $this->xmlWriter->outputMemory();
    }

    public function xmlpipe2()
    {
        $this->setfields(array(
            '_id',
            'title',
            'tags',
            'excerpt',
            'company',
            'person',

        ));

        $this->setAttributes(array(
            array(
                'name' => 'user_id',
                'type' => 'string',
//                'bits' => '32',
//                'default' => '1',
            ),
            array(
                'name' => 'source',
                'type' => 'string',
            ),
            array(
                'name' => 'created_at',
                'type' => 'timestamp',
            ),
//            array(
//                'name' => 'qi_score',
//                'type' => 'int',
//                'bits' => '16',
//                'default' => '10',
//            ),
        ));

        $this->beginOutput();

//        Companies::chunk(200, function ($companies) {
//            foreach ($companies as $K => $V) {
//                $this->addDocument($V);
//            }
//        });
        DailyNews::chunk(200, function ($news) {
            foreach ($news as $K => $V) {
                $this->addDocument($V);
            }
        });
//        $mNotes = Notes::all();
////        $count = $mNotes->count();
//        foreach ($mNotes as $K => $V) {
//            $this->addDocument($V);
//        }
//        $limit = c('XMLPIPE_BOOKS_COUNT_PER_TIME');
//        $tCont = (int)$count / $limit;
//        $oCount = $count % $limit;
//        if ($tCont > 0) {
//            do {
//                $books = $mNotes->field('book_id,book_name', '_id=>0')->limit($limit)->select();
//                foreach ($books as $book) {
//                    $this->addDocument($book);
//                }
//                unset($books);
//                $tCont--;
//            } while ($tCont > 0);
//
//            $books = $mBook->field('book_id,book_name', '_id=>0')->limit($oCount)->select();
//            foreach ($books as $book) {
//                $this->addDocument($book);
//            }
//            unset($books);
//        } else {
//            $books = $mBook->field('book_id,book_name', '_id=>0')->limit($oCount)->select();
//            foreach ($books as $book) {
//                $this->addDocument($book);
//            }
//            unset($books);
//        }


//
        $this->endOutput();

//        echo $this->xmlWriter->outputMemory(TRUE);
    }

    public function save($data)
    {
        $path = storage_path('app/nn.xml');

//        chmod(dirname(__FILE__), 0777); // 以最高操作权限操作当前目录
// 打开b.php文件，这里采用的是a+，也可以用a，a+为可读可写，a为只写，如果b.php不能存在则会创建它
        $file = fopen($path, 'a+'); // a模式就是一种追加模式，如果是w模式则会删除之前的内容再添加
// 获取需要写入的内容
// 写入追加的内容
        fwrite($file, $data);
// 关闭b.php文件
        fclose($file);
// 销毁文件资源句柄变量
        unset($file);
    }

}