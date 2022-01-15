<?php

namespace Aphly\LaravelSms\Facades;

use Aphly\LaravelSms\Contracts\SmsContracts;
use Illuminate\Support\Facades\Facade;

class Sms extends Facade
{
    protected static function getFacadeAccessor()
    {
        return SmsContracts::class;
    }
}
