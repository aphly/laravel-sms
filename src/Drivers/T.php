<?php

namespace Aphly\LaravelSms\Drivers;

use Aphly\LaravelSms\Contracts\SmsContracts;

class T implements SmsContracts
{
    function send($mobile){
        return $mobile.'T_vvvv';
    }
}
