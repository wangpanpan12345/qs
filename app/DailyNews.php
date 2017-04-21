<?php

namespace App;

use App\Lib\Fix;
use Illuminate\Database\Eloquent\SoftDeletes;
use Moloquent;
use Elasticquent\ElasticquentTrait;
class DailyNews extends Moloquent
{

    //
    use ElasticquentTrait;
    use SoftDeletes;
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at', 'created_at'];
    protected $fillable = [
        'user_id',
        'priority',
        'title',
        "link",
        "source",
        "company",
        "created_at",
        "pub_date",
        "tags",
        "company",
        "excerpt",
        "is_pub",
        "isread",
        "flag",
        "user_id",
        "priority",
        "person",
        "group"
    ];
    protected $collection = 'dailynews';

    public function users()
    {
        return $this->hasOne('App\User', '_id', 'user_id');
    }

    public function companies()
    {
        $_id_array = [];
        if (isset($this->company) && !empty($this->company)) {
            foreach ($this->company as $key => $value) {
                if(isset($value["_id"])&&$value["_id"]!=""){
                    $_id_array[] = $value["_id"];
                }

            }
            $r = \App\Companies::whereIn("_id", $_id_array)->get();
//            dd($r->toArray());
            return $r;
        } else {
            return [];
        }
//        return $this->hasOne('App\Companies', 'name', 'company');
    }
    function getIndexName()
    {
        return 'qisu';
    }

}
