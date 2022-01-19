<?php

namespace Aphly\LaravelSms\Models;

use Aphly\Laravel\Exceptions\ApiException;
use Aphly\Laravel\Libs\Helper;
use Aphly\LaravelSms\Jobs\SendJob;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use AlibabaCloud\SDK\Dysmsapi\V20170525\Dysmsapi;
use Darabonba\OpenApi\Models\Config;
use AlibabaCloud\SDK\Dysmsapi\V20170525\Models\SendSmsRequest;

class Sms extends Model
{
    use HasFactory;
    protected $table = 'sms';
    protected $primaryKey = 'phone';
    public $timestamps = false;

    public function phoneLimit($phone,$smscode){
        self::_check($phone,$smscode);
        $info = self::find($phone);
        $time = time();
        if($info){
            if(Helper::is_today($info->lasttime)){
                if($info['times']<config('sms.phonelimit')){
                    $info->times=$info->times+1;
                }else{
                    throw new ApiException(['code'=>10002,'msg'=>'每天同手机号限制'.config('sms.phonelimit').'条']);
                }
            }else{
                $info->times=1;
                $info->lasttime = $time;
            }
            $info->total=$info->total+1;
        }else{
            $info = new self;
            $info->phone=$phone;
            $info->total=1;
            $info->times=1;
            $info->lasttime = $time;
        }
        $info->smscode=$smscode;
        $info->expiretime= $time+config('sms.expiretime')*60;
        return $info->save();
    }

    static public function _check($phone,$smscode){
        if(empty($phone) || !Helper::is_phone($phone) || empty($smscode)){
            throw new ApiException(['code'=>10003,'data'=>'','msg'=>'手机号格式错误']);
        }
    }

    public function check($phone,$smscode){
        self::_check($phone,$smscode);
        $info = self::find($phone);
        if($info){
            if($info['smscode']==$smscode){
                if($info['expiretime']>time()){
                    throw new ApiException(['code'=>10011,'msg'=>'验证通过']);
                }else{
                    throw new ApiException(['code'=>10012,'msg'=>'验证码过期']);
                }
            }else{
                throw new ApiException(['code'=>10013,'msg'=>'无效验证码']);
            }
        }else{
            throw new ApiException(['code'=>10014,'msg'=>'无效手机号']);
        }
    }

    public function send($phone,$smscode){
        if(config('sms.queue')){
            SendJob::dispatch(['phone'=>$phone,'smscode'=>$smscode]);
        }else{
            SendJob::dispatchSync(['phone'=>$phone,'smscode'=>$smscode]);
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

    public static function main($args){
        $client = self::createClient(config('sms.aliyun.accessKeyId'), config('sms.aliyun.accessKeySecret'));
        $sendSmsRequest = new SendSmsRequest([
            "phoneNumbers" => $args['phone'],
            "signName" => config('sms.aliyun.signName'),
            "templateCode" => config('sms.templates.aliyun.verify_code'),
            "templateParam" => '{"code":"'.$args['$smscode'].'"}',
        ]);
        $client->sendSms($sendSmsRequest);
    }
}
