<?php

namespace App;

use Moloquent;
use Illuminate\Database\Eloquent\SoftDeletes;

class JobTopic extends Moloquent
{

    use SoftDeletes;
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];
    protected $collection = 'job_topic';
    protected $fillable = ['touser', 'topic', "c_status", "headline", "flag", "fromuser", "tags"];

    public function user()
    {
        if (isset($this->touser)) {
            $id = $this->touser;
            return \App\User::where("_id", $id)->get();
        }
        return [];
    }

}
