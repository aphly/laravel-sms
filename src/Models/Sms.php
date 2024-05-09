<?php

namespace Aphly\LaravelSms\Models;


use Aphly\Laravel\Exceptions\ApiException;
use Aphly\Laravel\Libs\Verifier;
use Aphly\Laravel\Models\Model;
use Aphly\LaravelSms\Jobs\SmsJob;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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

    function driverSmscode($driver,$sms_code)
    {
        if($driver->id==1){
            return'{"code":"'.$sms_code.'"}';
        }else{
            return (string)$sms_code;
        }
    }

    public static function main($args,$throw=false){
        if($throw) {
            try {
                if ($args['driver']->id == 1) {
                    $res = Aliyun::send($args);
                } else if ($args['driver']->id == 2) {
                    $res = Tencent::send($args);
                } else {
                    $res_str = '目前只支持阿里、腾讯';
                    throw new ApiException(['code' => 2, 'msg' => $res_str]);
                }
            } catch (\Exception $error) {
                $res_str = $error->getMessage();
                throw new ApiException(['code' => 2, 'msg' => $res_str]);
            }
            if ($args['driver']->id == 1) {
                if ($res->body->code == 'OK') {
                    throw new ApiException(['code' => 0, 'msg' => $res->body->message, 'data' => $res->body]);
                } else {
                    throw new ApiException(['code' => 1, 'msg' => $res->body->message, 'data' => $res->body]);
                }
            } else if ($args['driver']->id == 2) {
                $res_arr = json_decode($res, true);
                if ($res_arr['SendStatusSet'][0]['Code'] == 'Ok') {
                    throw new ApiException(['code' => 0, 'msg' => $res_arr['SendStatusSet'][0]['Code'], 'data' => $res_arr]);
                } else {
                    throw new ApiException(['code' => 1, 'msg' => $res_arr['SendStatusSet'][0]['Code'], 'data' => $res_arr]);
                }
            }
        }else{
            try {
                if($args['driver']->id==1) {
                    $res = Aliyun::send($args);
                }else if($args['driver']->id==2){
                    $res = Tencent::send($args);
                }else{
                    $res_str = '目前只支持阿里、腾讯';
                    return Sms::where('id',$args['id'])->update(['res'=>$res_str,'status'=>2]);
                }
            }catch (\Exception $error) {
                $res_str = $error->getMessage();
                return Sms::where('id',$args['id'])->update(['res'=>'Exception: '.$res_str,'status'=>2]);
            }
            if($args['driver']->id==1) {
                if ($res->body->code == 'OK') {
                    return Sms::where('id',$args['id'])->update(['res'=>json_encode($res->body),'status'=>1]);
                } else {
                    return Sms::where('id',$args['id'])->update(['res'=>json_encode($res->body),'status'=>2]);
                }
            }else if($args['driver']->id==2){
                $res_arr = json_decode($res, true);
                if ($res_arr['SendStatusSet'][0]['Code'] == 'Ok') {
                    return Sms::where('id',$args['id'])->update(['res'=>$res,'status'=>1]);
                }else{
                    return Sms::where('id',$args['id'])->update(['res'=>$res,'status'=>2]);
                }
            }
        }

    }



}
