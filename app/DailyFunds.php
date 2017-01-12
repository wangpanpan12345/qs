<?php
/**
 * Created by PhpStorm.
 * User: wangpan
 * Date: 16/12/28
 * Time: 上午9:22
 */

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use Moloquent;

class DailyFunds extends Moloquent
{
    use SoftDeletes;
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];
    protected $fillable = ['user_id','priority'];
    protected $collection = 'dailyfunds';

}