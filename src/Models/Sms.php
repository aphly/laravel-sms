<?php

namespace Aphly\LaravelSms\Models;

use AlibabaCloud\Tea\Utils\Utils\RuntimeOptions;
use Aphly\Laravel\Exceptions\ApiException;
use Aphly\Laravel\Libs\Verifier;
use Aphly\Laravel\Models\Model;
use Aphly\LaravelSms\Jobs\SendJob;
use Aphly\LaravelSms\Jobs\SmsJob;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use AlibabaCloud\SDK\Dysmsapi\V20170525\Dysmsapi;
use Darabonba\OpenApi\Models\Config;
use AlibabaCloud\SDK\Dysmsapi\V20170525\Models\SendSmsRequest;
use Illuminate\Support\Facades\Cache;

class Sms extends Model
{
    use HasFactory;
    protected $table = 'sms';
    //public $timestamps = false;

    protected $fillable = [
        'phone','site_id','sms_code','expire_at','res','type','queue_priority','status'
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

    static public function clearOverDays(int $days=30){
        $clear = Cache::get('clearSmsOverDays');
        if(!$clear){
            $seconds = 3600*24*$days;
            Cache::put('clearSmsOverDays',1,$seconds);
            self::where('created_at','<',time()-$seconds)->delete();
        }
    }

    public function send($args){
        if($args['type']){
            SmsJob::dispatch($args);
        }else{
            SmsJob::dispatchSync($args);
        }
    }

    public static function main($args,$throw=false){
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
            if($throw){
                throw new ApiException(['code'=>2,'msg'=> $res_str]);
            }else{
                return Sms::where('id',$args['id'])->update(['res'=>$res_str,'status'=>1]);
            }
        }
        if($throw) {
            if($res->body->code=='ok'){
                throw new ApiException(['code' => 0, 'msg' => $res->body->message, 'data' => $res->body]);
            }else{
                throw new ApiException(['code'=>1,'msg'=>$res->body->message,'data'=>$res->body]);
            }
        }else{
            return Sms::where('id',$args['id'])->update(['res'=>json_encode($res->body),'status'=>1]);
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
