<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use Moloquent;
use Elasticquent\ElasticquentTrait;

class CompanyIC extends Moloquent
{
    use SoftDeletes;
    use ElasticquentTrait;
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    protected $collection = 'companies_ic';

}
