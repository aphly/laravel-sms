<?php

namespace Aphly\LaravelSms\Drivers;

use Aphly\LaravelSms\Contracts\SmsContracts;

class Ali implements SmsContracts
{
    function send($mobile){
        return $mobile.'ali_vvvv';
    }
}
