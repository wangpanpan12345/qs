<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use Moloquent;

class DailyNews extends Moloquent
{
    //
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
        "person"
    ];
    protected $collection = 'dailynews';

    public function users()
    {
        return $this->hasOne('App\User', '_id', 'user_id');
    }
    public function companies()
    {
        return $this->hasOne('App\Companies', 'name', 'company');
//        return $this->hasMany('App\Companies','company', 'name');
//        return $this->hasMany('App\Companies', 'company', 'name');
//        return $this->hasMany('App\Companies', 'name', 'company');
    }

}
