<?php

namespace App;

use Moloquent;
use Illuminate\Database\Eloquent\SoftDeletes;
use Elasticquent\ElasticquentTrait;

class Logs extends Moloquent
{

    use ElasticquentTrait;
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $collection = 'logs';
    protected $fillable = [

    ];
    public function person()
    {
        return $this->hasOne('App\Founders', '_id', 'update_record_id');
    }
    public function company()
    {
        return $this->hasOne('App\Companies', '_id', 'update_record_id');
    }

}
