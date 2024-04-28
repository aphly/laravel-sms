<?php

namespace Aphly\LaravelSms\Jobs;

use Aphly\LaravelSms\Models\Sms;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SmsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    //public $tries = 2;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $timeout = 30;

    private $arr;

    //php artisan queue:work --queue=sms_vip,sms

    public function __construct($arr)
    {
        $this->arr = $arr;
        if(isset($arr['queue_priority']) && $arr['queue_priority']==1){
            $this->onQueue('sms_vip');
        }else{
            $this->onQueue('sms');
        }
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Sms::main($this->arr);
    }


}
