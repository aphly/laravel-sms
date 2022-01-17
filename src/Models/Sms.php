<?php

namespace Aphly\LaravelSms\Models;

use Aphly\Laravel\Exceptions\ApiException;
use Aphly\Laravel\Libs\Helper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sms extends Model
{
    use HasFactory;
    protected $table = 'sms';
    protected $primaryKey = 'phone';
    public $timestamps = false;

    public function phoneLimit($phone,$smscode){
        $info = self::find($phone);
        $time = time();
        if($info){
            if(Helper::is_today($info->lasttime)){
                if($info['times']<config('sms.phonelimit')){
                    $info->times=$info->times+1;
                }else{
                    throw new ApiException(['code'=>1001,'msg'=>'每天同手机号限制'.config('sms.phonelimit').'条']);
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
            $info->createtime = $time;
        }
        $info->smscode=$smscode;
        $info->expiretime= $time+config('sms.expiretime')*60;
        return $info->save();
    }

    public function check($phone,$smscode){
        if(isset($phone) && isset($smscode)){
            $info = self::find($phone);
            if($info){
                if($info['smscode']==$smscode){
                    if($info['expiretime']>time()){
                        throw new ApiException(['code'=>1020,'msg'=>'验证通过']);
                    }else{
                        throw new ApiException(['code'=>1022,'msg'=>'验证码过期']);
                    }
                }else{
                    throw new ApiException(['code'=>1021,'msg'=>'无效验证码']);
                }
            }else{
                throw new ApiException(['code'=>1023,'msg'=>'无效手机号']);
            }
        }else{
            throw new ApiException(['code'=>1024,'msg'=>'缺少参数']);
        }
    }
}
