<?php

namespace App\Jobs;

use App\Media\DealFund;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Exception;

class CheckFunding implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;
    protected $builder;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($builder)
    {
        //
        $this->builder = $builder;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(DealFund $b)
    {
        $b->intoDb($this->builder);
    }

    public function failed(Exception $e)
    {
        /**
         * 写入日志
         */
        $this->parase_log($e);
    }

    public function parase_log($pam = '')
    {
        $fp = fopen(storage_path("app/daily_fund_failed.txt"), "a+");
        if ($fp) {
            $flag = fwrite($fp, $pam . "\r\n");
            if (!$flag) {
                echo "写入文件失败<br>";
            }
        } else {
            echo "打开文件失败";
        }
        fclose($fp);
    }

}
