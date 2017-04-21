<?php

namespace App;

use Moloquent;
use Illuminate\Database\Eloquent\SoftDeletes;

class WechatInfo extends Moloquent
{

    use SoftDeletes;
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];
    protected $collection = 'wechat_info';
    protected $fillable = [
        'biz', 'ctime', 'priority','group'
       ];

}
