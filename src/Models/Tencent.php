<?php

namespace Aphly\LaravelSms\Models;

use TencentCloud\Common\Credential;
use TencentCloud\Common\Profile\ClientProfile;
use TencentCloud\Common\Profile\HttpProfile;
use TencentCloud\Sms\V20210111\SmsClient;
use TencentCloud\Sms\V20210111\Models\SendSmsRequest;

class Tencent
{
    static function send($args)
    {
            $cred = new Credential($args['key_id'], $args['key_secret']);
            $httpProfile = new HttpProfile();
            $httpProfile->setEndpoint("sms.tencentcloudapi.com");
            $clientProfile = new ClientProfile();
            $clientProfile->setHttpProfile($httpProfile);
            $client = new SmsClient($cred, "ap-nanjing", $clientProfile);
            $req = new SendSmsRequest();
            $params = [
                "PhoneNumberSet" => [$args['phone'] ] ,
                "SmsSdkAppId" => $args['driver']->sdk_app_id,
                "SignName" => $args['sign_name'],
                "TemplateId" => $args['template_code'],
                "TemplateParamSet" => [$args['template_param']]
            ];
            $req->fromJsonString(json_encode($params));
            $resp = $client->SendSms($req);
            return $resp->toJsonString();
    }
}
