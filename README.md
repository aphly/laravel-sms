**laravel sms短信**<br>

1、采用阿里云最新短信接口<br>
2、后续增加腾讯云短信<br>

环境<br>
php8.2+<br>
laravel10+<br>

安装<br>
`composer require alibabacloud/dysmsapi-20170525` <br>
`composer require aphly/laravel-sms` <br>
`php artisan vendor:publish --provider="Aphly\LaravelSms\SmsServiceProvider"` <br>

1、发送短信<br>
`post /sms/send`<br>
10001 每天同IP限制条数<br>
10002 每天同手机号限制条数<br>
11000 手机号或验证码错误<br>
`$phone = '111111111';
$sms_code = '4444';
$app_key = 'VRvs2ZdNTAXlf67lqpb49ueCDIspMpMA';
$input = [
'phone'=>$phone,
'sms_code'=>$sms_code,
'app_id'=>'2024042523241507',
'timestamp'=>time()
];
$input['sign'] = sign($input,$app_key);
$response = Http::post('http://xx/sms/send',$input);
dd($response->body());`

`sign : md5(md5($input['app_id'].$input['phone'].$input['sms_code'].$app_key).$input['timestamp'])`

2、验证短信<br>
`get /sms/check`<br>
0 验证通过<br>
2 验证码过期<br>
1 无效验证码<br>
11000 无效手机号<br>
`$response = Http::get('http://xx/sms/check?phone=111111111&sms_code=1111');
dd($response->body());`
