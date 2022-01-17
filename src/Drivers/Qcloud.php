<?php

namespace Aphly\LaravelSms\Drivers;

use Aphly\LaravelSms\Contracts\SmsContracts;

class Qcloud implements SmsContracts
{
    function send($phone){

    }

    function sendCode($mobile,$smscode){
        return $mobile;
    }
}
