<?php

namespace App;

use Moloquent;

class Founders extends Moloquent
{
    //
    protected $collection = 'founders';

    public function company()
    {
        return $this->embedsOne('\App\Companies','_id','founder_id');
    }


}
