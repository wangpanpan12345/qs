<?php

namespace App;

use Moloquent;
use Illuminate\Database\Eloquent\SoftDeletes;

class Notes extends Moloquent
{

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $collection = 'notes';
    protected $fillable = [

    ];

}
