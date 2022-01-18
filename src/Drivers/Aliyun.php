<?php

namespace Aphly\LaravelSms\Drivers;

use Aphly\Laravel\Exceptions\ApiException;
use Aphly\LaravelSms\Contracts\SmsContracts;
use AlibabaCloud\SDK\Dysmsapi\V20170525\Dysmsapi;
use Aphly\LaravelSms\Models\Sms;
use Aphly\LaravelSms\Models\SmsLog;
use Darabonba\OpenApi\Models\Config;
use AlibabaCloud\SDK\Dysmsapi\V20170525\Models\SendSmsRequest;

class Aliyun implements SmsContracts
{
    function send($phone){}

    function sendCode($phone,$smscode){
        (new SmsLog)->ipLimit();
        (new Sms)->phoneLimit($phone,$smscode);
        $arr = ['phone'=>$phone,'code'=>$smscode];
        self::main($arr);
        throw new ApiException(['code'=>10003,'data'=>$arr,'msg'=>'发送成功']);
    }

    public function check($phone,$smscode){
        (new Sms)->check($phone,$smscode);
    }

    public static function createClient($accessKeyId, $accessKeySecret){
        $config = new Config([
            "accessKeyId" => $accessKeyId,
            "accessKeySecret" => $accessKeySecret
        ]);
        $config->endpoint = "dysmsapi.aliyuncs.com";
        return new Dysmsapi($config);
    }

    public static function main($args){
        $client = self::createClient(config('sms.aliyun.accessKeyId'), config('sms.aliyun.accessKeySecret'));
        $sendSmsRequest = new SendSmsRequest([
            "phoneNumbers" => $args['phone'],
            "signName" => config('sms.aliyun.signName'),
            "templateCode" => config('sms.templates.aliyun.verify_code'),
            "templateParam" => '{"code":"'.$args['code'].'"}',
        ]);
        $client->sendSms($sendSmsRequest);
    }
}
