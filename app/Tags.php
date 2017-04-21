<?php

namespace App;

use Moloquent;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tags extends Moloquent
{

    use SoftDeletes;
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];
    protected $collection = 'tags';
    protected $fillable = ['name','source','alias','group_id'];

    public function sub(){
        return $this->hasMany('App\Tags','group_id', 'id');
    }

    public function knowledge()
    {
        return $this->hasMany('App\KnowledgeCanned', 'tags', 'name');
    }
}
