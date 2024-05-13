**laravel sms短信**<br>

采用阿里云、腾讯云最新短信接口<br>

环境<br>
php8.2+<br>
laravel10+<br>

安装<br>
`composer require alibabacloud/dysmsapi-20170525` <br>
`composer require tencentcloud/tencentcloud-sdk-php` <br>
`composer require aphly/laravel-sms` <br>
`php artisan vendor:publish --provider="Aphly\LaravelSms\SmsServiceProvider"` <br>

队列<br>
`php artisan queue:work --queue=sms_vip,sms`

1、发送短信<br>
`post /sms/send`<br>
10001 每天同IP限制条数<br>
10002 每天同手机号限制条数<br>
11000 手机号或验证码错误<br>

2、验证短信<br>
`post /sms/check`<br>
0 验证通过<br>
2 验证码过期<br>
1 无效验证码<br>
11000 无效手机号<br>

示例<br>
`function sign($input,$app_key){
return md5(md5($input['app_id'].$input['phone'].$input['sms_code'].$app_key).$input['timestamp']);
}

Route::get('/sms/send', function () {
$phone = '1111';
$sms_code = '66666';
$app_key = 'yBgx0Vk8kTIRoRo3PgTRL9fFNIrmADTt';
$input = [
'phone'=>$phone,
'sms_code'=>$sms_code,
'app_id'=>'2024042695714480',
'timestamp'=>time()
];
$input['sign'] = sign($input,$app_key);
$response = Http::post('http://xx/sms/send',$input);
dd($response->body());
});

Route::get('/sms/check', function () {
$phone = '11111';
$sms_code = '66666';
$app_key = 'yBgx0Vk8kTIRoRo3PgTRL9fFNIrmADTt';
$input = [
'phone'=>$phone,
'sms_code'=>$sms_code,
'app_id'=>'2024042695714480',
'timestamp'=>time()
];
$input['sign'] = sign($input,$app_key);
$response = Http::post('http://xx/sms/check',$input);
dd($response->body());
});`
