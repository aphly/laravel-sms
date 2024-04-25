<?php

namespace Aphly\LaravelSms\Models;

use AlibabaCloud\Tea\Utils\Utils\RuntimeOptions;
use Aphly\Laravel\Exceptions\ApiException;
use Aphly\Laravel\Libs\Verifier;
use Aphly\Laravel\Models\Model;
use Aphly\LaravelSms\Jobs\SendJob;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use AlibabaCloud\SDK\Dysmsapi\V20170525\Dysmsapi;
use Darabonba\OpenApi\Models\Config;
use AlibabaCloud\SDK\Dysmsapi\V20170525\Models\SendSmsRequest;

class Sms extends Model
{
    use HasFactory;
    protected $table = 'sms';
    public $timestamps = false;

    protected $fillable = [
        'phone','site_id','sms_code','expire_at','created_at','res'
    ];



    static public function _check(){
        return Verifier::handle(request()->all(),[
            'phone'=>'required|digits:11',
            'sms_code' => 'required|digits_between:4,6',
        ],[
            'phone.required'=>'手机号缺少',
            'phone.digits'=>'手机号格式错误',
            'sms_code.required'=>'验证码缺少',
            'sms_code.digits_between'=>'验证码格式错误',
        ]);
    }

    static public function check(){
        $arr = self::_check();
        $info = Sms::where('phone',$arr['phone'])->orderBy('created_at','desc')->firstOrError();
        if($info->sms_code==$arr['sms_code']){
            if($info->expire_at>time()){
                throw new ApiException(['code'=>0,'msg'=>'验证通过']);
            }else{
                throw new ApiException(['code'=>2,'msg'=>'验证码过期']);
            }
        }else{
            throw new ApiException(['code'=>1,'msg'=>'无效验证码']);
        }
    }

    public function send($args){
        if($args['type']){
            SendJob::dispatch($args);
        }else{
            SendJob::dispatchSync($args);
        }
    }

    public static function main($args,$err=false){
        $client = self::createClient($args['key_id'], $args['key_secret']);
        $sendSmsRequest = new SendSmsRequest([
            "phoneNumbers" => $args['phone'],
            "signName" => $args['sign_name'],
            "templateCode" => $args['template_code'],
            "templateParam" => $args['template_param'],
        ]);
        try {
            $res = $client->sendSmsWithOptions($sendSmsRequest, new RuntimeOptions([]));
        }catch (\Exception $error) {
            $res_str = $error->getMessage();
            Sms::where('id',$args['id'])->update(['res'=>$res_str]);
            if($err){
                throw new ApiException(['code'=>2,'msg'=> $res_str]);
            }
            return;
        }
        $res_body = $res->body;
        Sms::where('id',$args['id'])->update(['res'=>json_encode($res_body)]);
        if($err) {
            if($res->body->code=='ok'){
                throw new ApiException(['code' => 0, 'msg' => $res->body->message, 'data' => $res_body]);
            }else{
                throw new ApiException(['code'=>1,'msg'=>$res->body->message,'data'=>$res_body]);
            }
        }
    }

    public static function createClient($accessKeyId, $accessKeySecret){
        $config = new Config([
            "accessKeyId" => $accessKeyId,
            "accessKeySecret" => $accessKeySecret
        ]);
        $config->endpoint = "dysmsapi.aliyuncs.com";
        return new Dysmsapi($config);
    }
}
