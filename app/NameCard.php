<?php

namespace App;

use Moloquent;
use Illuminate\Database\Eloquent\SoftDeletes;

class NameCard extends Moloquent
{

    use SoftDeletes;
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];
    protected $collection = 'namecard';
    protected $fillable = [
        'name', 'company', 'department',
        'jobtitle', 'tel_main', 'tel_mobile',
        'tel_inter', 'fax', 'email',
        'pager', 'web', 'address',
        'postcode', 'qq'];

}
