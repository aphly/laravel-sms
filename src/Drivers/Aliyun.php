<?php

namespace Aphly\LaravelSms\Drivers;

use Aphly\LaravelSms\Contracts\SmsContracts;

use Aphly\LaravelSms\Models\Sms;
use Aphly\LaravelSms\Models\SmsLog;

class Aliyun implements SmsContracts
{

    function sendCode($phone,$smscode){
        (new SmsLog)->ipLimit();
        $sms = new Sms;
        $sms->phoneLimit($phone,$smscode);
        $sms->send($phone,$smscode);
    }

    public function check($phone,$smscode){
        (new Sms)->check($phone,$smscode);
    }

}
