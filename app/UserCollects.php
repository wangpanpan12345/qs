<?php

namespace App;

use Moloquent;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserCollects extends Moloquent
{

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $collection = 'user_collects';
    protected $fillable = [
        "type",
        "cid"
    ];
    public function dailynews()
    {
        return $this->hasOne('App\DailyNews', '_id', 'cid');
    }

}
