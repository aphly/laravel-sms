<?php

namespace Aphly\LaravelSms\Models;

use Aphly\Laravel\Exceptions\ApiException;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sms extends Model
{
    use HasFactory;
    protected $table = 'sms';
    protected $primaryKey = 'phone';
    public $timestamps = false;

    public function check($request,$return=false){
        $get = $request->only(['phone','smscode']);
        if(isset($get['phone']) && isset($get['smscode'])){
            $info = self::find($get['phone']);
            if($info){
                if($info['expiretime']>time()){
                    if($info['smscode']==$get['smscode']){
                        if($return){
                            return $info['phone'];
                        }else{
                            throw new ApiException(['code'=>1020,'msg'=>'验证通过']);
                        }
                    }else{
                        throw new ApiException(['code'=>1021,'msg'=>'无效验证码']);
                    }
                }else{
                    throw new ApiException(['code'=>1022,'msg'=>'验证码过期']);
                }
            }else{
                throw new ApiException(['code'=>1023,'msg'=>'无效手机号']);
            }
        }else{
            throw new ApiException(['code'=>1024,'msg'=>'缺少参数']);
        }

    }
}
