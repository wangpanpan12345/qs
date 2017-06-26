<?php

namespace App;

use Moloquent;
use Illuminate\Database\Eloquent\SoftDeletes;
use Elasticquent\ElasticquentTrait;
class Items extends Moloquent
{
    use ElasticquentTrait;
    use SoftDeletes;
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];
    protected $collection = 'items';
    protected $fillable = ['name', 'des', 'source', 'basic_info', 'extra_info'];
}
