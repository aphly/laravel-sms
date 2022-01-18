<?php

namespace Aphly\LaravelSms\Drivers;

use Aphly\LaravelSms\Contracts\SmsContracts;
use Aphly\LaravelSms\Models\Sms;

class Qcloud implements SmsContracts
{
    function send($phone){

    }

    function sendCode($mobile,$smscode){
        return $mobile;
    }

    public function check($phone,$smscode){
        (new Sms)->check($phone,$smscode);
    }
}
