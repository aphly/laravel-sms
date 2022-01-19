<?php

namespace Aphly\LaravelSms\Contracts;

interface SmsContracts
{
    public function sendCode($phone,$smscode);
    public function check($phone,$smscode);
}
