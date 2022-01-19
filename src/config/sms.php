<?php
return [
    'driver'=>'aliyun',//现在只支持阿里云，后续支持腾讯云
    'queue'=>false,//默认关闭队列
    'iplimit'=>20,//每天同ip限制条数
    'phonelimit'=>5,//每天同手机号限制条数
    'expiretime'=>2,//默认验证码有效时间2分钟
    'aliyun' => [
        'sms_name' => '阿里云',
        'sms_url' => 'https://dysms.console.aliyun.com/dysms.htm',
        'accessKeyId' => '',
        'accessKeySecret' => '',
        'signName' => 'xxx',
    ],
    'qcloud' => [
        'sms_name' => '腾讯云',
        'sms_url' => 'https://cloud.tencent.com/product/sms',
        'appid' => '',
        'appkey' => '',
        'smsSign' => '',
    ],
    'templates' => [
        'aliyun' => [
            'verify_code' => 'SMS_00000000',
        ],
    ]
];
