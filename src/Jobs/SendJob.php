<?php

namespace Aphly\LaravelSms\Jobs;

use Aphly\LaravelSms\Models\Sms;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;


class SendJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 2;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    private $arr;
    public function __construct($arr)
    {
        $this->arr = $arr;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        (new Sms)->main($this->arr);
    }


}
