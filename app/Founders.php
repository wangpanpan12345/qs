<?php

namespace App;

use Moloquent;
use Elasticquent\ElasticquentTrait;
class Founders extends Moloquent
{
    use ElasticquentTrait;
    //
    protected $dates = ['deleted_at',"created_at','updated_at"];
    protected $collection = 'founders';

    public function company()
    {
        return $this->embedsOne('\App\Companies','_id','founder_id');
    }


}
