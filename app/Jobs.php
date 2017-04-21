<?php

namespace App;

use Moloquent;
use Illuminate\Database\Eloquent\SoftDeletes;

class Jobs extends Moloquent
{

    use SoftDeletes;
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];
    protected $collection = 'jobs';
    protected $fillable = ['user','jid',"status","table","subject"];

    public function company()
    {
        return $this->hasOne('App\Companies', '_id', 'jid');
    }

}
