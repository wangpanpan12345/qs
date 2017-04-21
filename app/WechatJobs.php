<?php

namespace App;

use Moloquent;
use Illuminate\Database\Eloquent\SoftDeletes;

class WechatJobs extends Moloquent
{

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $collection = 'wechat_jobs';
    protected $fillable = [
        'load', 'ctime','url',
       ];

}
