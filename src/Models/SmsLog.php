<?php

namespace Aphly\LaravelSms\Models;

use Aphly\Laravel\Exceptions\ApiException;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SmsLog extends Model
{
    use HasFactory;
    protected $table = 'sms_log';
    protected $primaryKey = 'ip';
    public $timestamps = false;

    public function iplimit($ip){
        $info =  self::find($ip);
        if($info){
            if(Common::is_today($info->todaytime)){
                if($info['times']<config('sms.iplimit')){
                    $info->times=$info->times+1;
                }else{
                    throw new ApiException(['code'=>1011,'msg'=>'每天同IP限制'.config('sms.iplimit').'条']);
                }
            }else{
                $info->times=1;
                $info->todaytime = TIMESTAMP;
            }
        }else{
            $this->ip=$ip;
            $this->times=1;
            $this->todaytime = TIMESTAMP;
            $this->createtime = TIMESTAMP;
        }
        if($this->save()){
            return true;
        }else{
            throw new ApiException(['code'=>1010,'msg'=>'错误']);
        }
    }
}
