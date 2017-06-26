<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use Moloquent;
use Elasticquent\ElasticquentTrait;

class KnowledgeCanned extends Moloquent
{
    use ElasticquentTrait;

    //
//    use SoftDeletes;
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at', 'created_at'];
    protected $fillable = [
        'content',
        'tags',
        'type',
        "flag",
        "user_id",
        "priority",
        "group"
    ];
    protected $collection = 'knowledge_canned';

//    public function tags()
//    {
//        return $this->belongsTo('App\Tags', 'tags', 'name');
//    }

    public function users()
    {
        return $this->hasOne('App\User', '_id', 'user_id');
    }


}
