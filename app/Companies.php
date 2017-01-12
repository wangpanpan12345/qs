<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use Moloquent;

class Companies extends Moloquent
{
    use SoftDeletes;
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    protected $collection = 'companies';

    public function founders()
    {
//        return $this->hasOne('App\Founder','founder_id','_id');
        return \App\Founders::find($this->founder_id);
    }


    public function score_company($company)
    {
        $score = 0;
        $keys_5 = array('avatar', 'des', 'detail', 'industry', 'location', 'website', 'tags',
            'time', 'phone', 'email');
        $keys_10 = array('raiseFunds', 'keytec', 'trends', 'products', 'founder_id');
        foreach ($company as $sk => $sv) {
            if (in_array($sk, $keys_5) && !empty($sv)) {
                $score = $score + 5;
            } elseif (in_array($sk, $keys_10) && !empty($sv)) {
                $score = $score + 10;
            }
        }
        return $score;
    }


}
