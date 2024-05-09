<?php

namespace Aphly\LaravelSms\Models;

use AlibabaCloud\Tea\Utils\Utils\RuntimeOptions;
use AlibabaCloud\SDK\Dysmsapi\V20170525\Dysmsapi;
use Darabonba\OpenApi\Models\Config;
use AlibabaCloud\SDK\Dysmsapi\V20170525\Models\SendSmsRequest;

class Aliyun
{
    public static function createClient($accessKeyId, $accessKeySecret){
        $config = new Config([
            "accessKeyId" => $accessKeyId,
            "accessKeySecret" => $accessKeySecret
        ]);
        $config->endpoint = "dysmsapi.aliyuncs.com";
        return new Dysmsapi($config);
    }

    public static function send($args){
        $client = self::createClient($args['key_id'], $args['key_secret']);
        $sendSmsRequest = new SendSmsRequest([
            "phoneNumbers" => $args['phone'],
            "signName" => $args['sign_name'],
            "templateCode" => $args['template_code'],
            "templateParam" => $args['template_param'],
        ]);
        return $client->sendSmsWithOptions($sendSmsRequest, new RuntimeOptions([]));
    }
}
