<?php
return [
    'driver'=>'aliyun',
    'iplimit'=>20,
    'aliyun' => [
        'sms_name' => '阿里云',
        'sms_url' => 'https://dysms.console.aliyun.com/dysms.htm',
        'accessKeyId' => env('SMS_ALIYUN_ACCESSKEYID'),
        'accessKeySecret' => env('SMS_ALIYUN_ACCESSKEYSECRET'),
        'signName' => env('SMS_ALIYUN_SIGNNAME'),
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
    ],
    'international' => [
        'gateways' => [
            'aliyun' => [
                'signName' => env('SMS_ALIYUN_INTERNATIONAL_SIGNNAME'),
            ],
        ],
        'templates' => [
            'aliyun' => [
                'verify_code' => env('SMS_ALIYUN_INTERNATIONAL_VERIFICATION_CODE', 'SMS_00000000'),
            ],
        ],
    ]
];
